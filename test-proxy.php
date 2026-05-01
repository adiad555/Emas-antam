<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'message' => 'Proxy server is running',
    'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown'
]);
?>