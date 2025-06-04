<?php
$host = 'localhost';
$db = 'projek_capstonelaskarai';
$user = 'root'; // atau user database kamu
$pass = ''; // password database kamu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
