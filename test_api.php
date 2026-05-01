<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'jayapay-config.php';

$orderNum = 'TEST_' . time();
$amount = 10000;
$method = 'QRIS';
$redirectUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/deposit.html';

$params = [
    'mchNo' => MERCHANT_NO,
    'orderNum' => $orderNum,
    'amount' => $amount,
    'method' => $method,
    'timestamp' => round(microtime(true) * 1000),
    'downNotifyUrl' => CALLBACK_URL,
    'downReturnUrl' => $redirectUrl,
];

// Fungsi signature
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

$params['sign'] = jayapay_sign($params, $merchant_private_key);

echo "<h3>Request ke JAYAPAY:</h3>";
echo "<pre>" . json_encode($params, JSON_PRETTY_PRINT) . "</pre>";

$apiUrl = $api_base . '/pay/prePay';
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "<h3>Response HTTP Code: $httpCode</h3>";
if ($curlError) echo "<h3>Curl Error: $curlError</h3>";
echo "<h3>Response dari JAYAPAY:</h3>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

$result = json_decode($response, true);
if ($result && isset($result['code'])) {
    if ($result['code'] == '9999') {
        echo "<h3 style='color:green'>SUKSES! cashierUrl: " . ($result['data']['cashierUrl'] ?? 'tidak ada') . "</h3>";
    } else {
        echo "<h3 style='color:red'>GAGAL: " . ($result['msg'] ?? 'Unknown error') . "</h3>";
    }
}
?>