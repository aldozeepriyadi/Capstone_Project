<?php
include 'connect.php'; // koneksi via PDO

$identifier = $_POST['identifier'];
$password   = $_POST['password'];

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :identifier OR email = :identifier LIMIT 1");
    $stmt->execute(['identifier' => $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            echo "<script>alert('Login berhasil!'); window.location.href='../chatbot.php';</script>";
        } else {
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username atau email tidak ditemukan!'); window.location.href='login.php';</script>";
    }
} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
