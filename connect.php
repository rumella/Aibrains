<?php
$servername = "sql310.infinityfree.com";
$username = "if0_37988276";
$password = "q9MEx8lZgy"; 
$dbname = "if0_37988276_logindb";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>