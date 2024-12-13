<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);  // Menambahkan proteksi SQL Injection
    $password = $_POST['password'];

    // Query untuk mencari dokter berdasarkan username
    $query = "SELECT * FROM dokter WHERE username='$username'";
    $result = mysqli_query($koneksi, $query);

    // Verifikasi jika dokter ditemukan
    if ($dokter = mysqli_fetch_assoc($result)) {
        // Verifikasi password
        if (password_verify($password, $dokter['password'])) {
            // Jika login berhasil, simpan session
            $_SESSION['username'] = $dokter['username'];
            $_SESSION['role'] = 'dokter';  // Atau bisa menyimpan role yang sesuai

            // Redirect ke halaman dashboard dokter
            header("Location: dashboard_dokter.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Dokter dengan username tersebut tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Login Dokter</h2>

    <!-- Menampilkan pesan error jika login gagal -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="form-group mt-3">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Login</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
