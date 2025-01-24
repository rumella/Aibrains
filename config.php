<?php
// config.php

// Veritabanı bağlantısı ayarları
define('DB_HOST', 'sql310.infinityfree.com');
define('DB_USER', 'if0_37988276');
define('DB_PASSWORD', 'q9MEx8lZgy');
define('DB_NAME', 'if0_37988276_logindb');

// Veritabanı bağlantısı
function getDatabaseConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Bağlantı hatası: " . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4'); // UTF-8 karakter seti
    return $conn;
}
?>

