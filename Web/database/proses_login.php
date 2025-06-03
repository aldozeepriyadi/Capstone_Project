<?php
include 'connect.php'; 

$identifier = $_POST['identifier']; 
$password   = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$identifier' OR email='$identifier' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        echo "<script>alert('Login berhasil!'); window.location.href='/Capstone_project/Web/chatbot.php';</script>";
    } else {
        echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Username atau email tidak ditemukan!'); window.location.href='login.php';</script>";
}

mysqli_close($conn);
?>
