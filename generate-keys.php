<?php
// Generate merchant RSA key pair (PKCS#8) simpan ke file
$config = array(
    "digest_alg" => "sha256",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);
$res = openssl_pkey_new($config);
openssl_pkey_export($res, $privKey, null, $config);
$pubKey = openssl_pkey_get_details($res)['key'];

// Simpan private key ke file (jangan dibaca publik)
file_put_contents('merchant_private.key', $privKey);

// Tampilkan public key untuk di-upload ke dashboard JAYAPAY
echo "<h3>Upload Public Key ini ke dashboard JAYAPAY:</h3>";
echo "<textarea rows='6' cols='80'>".$pubKey."</textarea>";
echo "<hr>";
echo "<p style='color:green'>Private key sudah disimpan di file <strong>merchant_private.key</strong> (jangan dihapus).</p>";
echo "<p>Setelah upload public key ke JAYAPAY, lanjutkan konfigurasi di <strong>jayapay-config.php</strong>.</p>";
?>