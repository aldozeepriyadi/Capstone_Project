<?php
include 'connect.php'; // pastikan file ini berisi koneksi ke DB

// Ambil data dari form
$name     = $_POST['name'];
$email    = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' OR username='$username'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Email atau username sudah digunakan!'); window.location.href='register.php';</script>";
    exit;
}

$sql = "INSERT INTO users (name, email, username, password, role) VALUES ('$name', '$email', '$username', '$hashed_password', 'user')";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='/Capstone_project/Web/login.php';</script>";
} else {
    echo "Gagal registrasi: " . mysqli_error($conn);
}

mysqli_close($conn);
