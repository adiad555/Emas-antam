<?php
header('Content-Type: application/json');
require_once 'jayapay-config.php';

// Fungsi signature RSA (sort params, concat value, sign with private key)
function jayapay_sign($params, $privateKey) {
    ksort($params);
    $signStr = '';
    foreach ($params as $k => $v) {
        if ($v !== '' && $v !== null && $k !== 'sign') {
            $signStr .= $v;
        }
    }
    openssl_sign($signStr, $signature, $privateKey, OPENSSL_ALGO_SHA256);
    return base64_encode($signature);
}

// Fungsi verifikasi callback (pakai platform public key)
function jayapay_verify($params, $sign, $publicKey) {
    ksort($params);
    $signStr = '';
    foreach ($params as $k => $v) {
        if ($v !== '' && $v !== null && $k !== 'sign') {
            $signStr .= $v;
        }
    }
    return openssl_verify($signStr, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA256) === 1;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$action = $input['action'] ?? '';

// Create order
if ($action === 'create') {
    $orderNum = $input['orderNum'];
    $amount = intval($input['amount']);
    $method = $input['method'];   // QRIS, DANA, GOPAY_QRIS, OVO, SHOPEEPAY
    $redirectUrl = $input['redirectUrl'];
    
    // Parameter yang dikirim ke JAYAPAY (sesuai dokumen Indonesia)
    $params = [
        'mchNo' => MERCHANT_NO,
        'orderNum' => $orderNum,
        'amount' => $amount,
        'method' => $method,
        'timestamp' => round(microtime(true) * 1000), // milliseconds
        'downNotifyUrl' => CALLBACK_URL,
        'downReturnUrl' => $redirectUrl,
    ];
    
    // Generate signature
    $params['sign'] = jayapay_sign($params, $merchant_private_key);
    
    // Kirim ke API JAYAPAY (prePay)
    $apiUrl = $api_base . '/pay/prePay';
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode != 200) {
        echo json_encode(['success' => false, 'message' => 'HTTP Error: ' . $httpCode]);
        exit;
    }
    
    $result = json_decode($response, true);
    if ($result && isset($result['code']) && $result['code'] === '9999') {
        echo json_encode(['success' => true, 'response' => $result]);
    } else {
        echo json_encode(['success' => false, 'response' => $result]);
    }
    exit;
}

// Query order
if ($action === 'query') {
    $orderNum = $input['orderNum'];
    $params = [
        'mchNo' => MERCHANT_NO,
        'orderNum' => $orderNum,
        'timestamp' => round(microtime(true) * 1000),
    ];
    $params['sign'] = jayapay_sign($params, $merchant_private_key);
    
    $apiUrl = $api_base . '/pay/query';
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);
    
    echo $response;
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action']);
?>