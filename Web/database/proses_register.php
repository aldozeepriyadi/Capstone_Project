<?php
include 'connect.php';

$name     = $_POST['name'];
$email    = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Cek apakah email atau username sudah ada
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR username = :username");
    $stmt->execute(['email' => $email, 'username' => $username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Email atau username sudah digunakan!'); window.location.href='../registrasi.php';</script>";
        exit;
    }

    // Insert data ke tabel users
    $stmt = $pdo->prepare("INSERT INTO users (name, email, username, password, role) VALUES (:name, :email, :username, :password, 'user')");
    $stmt->execute([
        'name'     => $name,
        'email'    => $email,
        'username' => $username,
        'password' => $hashed_password
    ]);

    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='../login.php';</script>";
} catch (PDOException $e) {
    echo "Gagal registrasi: " . $e->getMessage();
}
