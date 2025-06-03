<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi - Healminds</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row shadow rounded-4 bg-white overflow-hidden w-100" style="max-width: 900px;">

            <!-- Gambar Ilustrasi -->
            <div
                class="col-md-6 d-none d-md-block bg-info p-4 text-white text-center d-flex flex-column justify-content-center">
                <h2 class="mb-4">Selamat Datang di Healminds</h2>
                <p>Teman bicara digital yang mendengarkanmu 24/7.</p>
                <img src="assets/3918491-removebg-preview.png" alt="Mental Health Illustration" class="img-fluid mt-3"
                    style="max-height: 300px;">
            </div>

            <!-- Form Login -->
            <div class="col-md-6 p-5">
                <h4 class="mb-4 text-center">Registrasi</h4>
                <form method="POST" action="database/proses_register.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Namaku Healminds" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="contoh123" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <a href="#" class="text-decoration-none small">Lupa kata sandi?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrasi</button>
                </form>
                <p class="text-center mt-4 mb-0 small">Sudah punya akun? <a href="login.php" class="text-primary">Masuk</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>