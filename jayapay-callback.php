<?php
// Webhook dari JAYAPAY setelah pembayaran sukses
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

require_once 'jayapay-config.php';

// Log untuk debugging
file_put_contents('callback_log.txt', date('Y-m-d H:i:s') . ' - ' . $rawInput . PHP_EOL, FILE_APPEND);

if (!$data) {
    http_response_code(400);
    exit('Invalid data');
}

// Verifikasi signature
$receivedSign = $data['sign'] ?? '';
unset($data['sign']);
if (!jayapay_verify($data, $receivedSign, $PLATFORM_PUBLIC_KEY)) {
    file_put_contents('callback_log.txt', "Signature verification failed\n", FILE_APPEND);
    http_response_code(401);
    exit('Sign gagal');
}

// Cek status transaksi
if (($data['status'] ?? '') === 'SUCCESS' && ($data['code'] ?? '') === '9999') {
    $orderNum = $data['orderNum'];
    $amount = intval($data['amount']);
    
    // Ambil nohp dari pending deposit di Firebase
    // Kita simpan pending deposit saat user mulai deposit (nanti di deposit.html kita simpan ke Firebase)
    // Untuk memudahkan, kita buat node /pendingDeposits/{orderNum} di Firebase
    
    require_once 'firebase-lib.php'; // Kita buat nanti atau langsung pakai REST API
    // Gunakan Firebase REST API
    $dbUrl = $firebase_config['databaseURL'];
    $pendingRef = $dbUrl . '/pendingDeposits/' . $orderNum . '.json';
    $pendingData = @file_get_contents($pendingRef);
    if ($pendingData) {
        $pending = json_decode($pendingData, true);
        if ($pending && isset($pending['nohp'])) {
            $nohp = $pending['nohp'];
            // Update saldo user
            $userRef = $dbUrl . '/users/' . $nohp . '/saldo.json';
            $currentSaldo = @file_get_contents($userRef);
            $currentSaldo = $currentSaldo ? intval($currentSaldo) : 0;
            $newSaldo = $currentSaldo + $amount;
            // Update saldo
            $ch = curl_init($userRef);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($newSaldo));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);
            
            // Catat deposit
            $depositRef = $dbUrl . '/deposits/' . $nohp . '/' . $orderNum . '.json';
            $depositData = [
                'amount' => $amount,
                'method' => $pending['method'],
                'status' => 'success',
                'date' => date('c')
            ];
            $ch = curl_init($depositRef);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($depositData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);
            
            // Hapus pending
            $ch = curl_init($pendingRef);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_exec($ch);
            curl_close($ch);
            
            file_put_contents('callback_log.txt', "Saldo updated for $nohp +$amount\n", FILE_APPEND);
        }
    }
}

// Response sukses ke JAYAPAY (harus return 'SUCCESS')
echo 'SUCCESS';
?>