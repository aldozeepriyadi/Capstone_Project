<?php
$host = 'localhost';
$db = 'projek_capstonelaskarai';
$user = 'root'; 
$pass = '';
$port = '3307';  // Menambahkan port

try {
    // Menambahkan port pada string DSN
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
