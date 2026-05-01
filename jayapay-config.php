<?php
// === KONFIGURASI FINAL JAYAPAY ===
define('MERCHANT_NO', 'JAYA2L20090');
define('ENVIRONMENT', 'production');
define('COUNTRY_CODE', 'id');

// Platform public key dari dashboard (sudah diperbaiki, tanpa spasi)
$PLATFORM_PUBLIC_KEY = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCRRaLenTRnrKfMWhJPKb/joWTJ
g6g3UVbLn+KgWUZ1eQtlZaAjAyyQitI2wRQWKmCldGYAfwlj0xmUwzFmsqR0+/+g
9bzX+Ppn4SBEjYxsCMLbyp64PeSP/xUPiy5t7lfl4Au17BR8OsI/kUIjWP/0YOVz
QIswyAIzIl0DSlxlIwIDAQAB
-----END PUBLIC KEY-----
EOD;

// Merchant private key (sudah disimpan di file merchant_private.key)
$merchant_private_key = file_get_contents('merchant_private.key');
if (!$merchant_private_key) {
    die("❌ merchant_private.key tidak ditemukan. Pastikan file sudah diupload.");
}

// API URL
$api_base = "https://global-{$COUNTRY_CODE}-openapi.jayapayment.com/{$COUNTRY_CODE}";

// Callback URL (otomatis menyesuaikan domain)
define('CALLBACK_URL', 'https://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/jayapay-callback.php');
define('FIREBASE_DB_URL', 'https://lginvest-776cf-default-rtdb.firebaseio.com');
?>