<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'dokter';  // Role fixed for doctor

    // Query untuk mencari dokter berdasarkan username dan password
    $query = "SELECT * FROM dokter WHERE username='$username' AND role='$role'";
    $result = mysqli_query($koneksi, $query);
    $dokter = mysqli_fetch_assoc($result);

    // Verifikasi password
    if ($dokter && password_verify($password, $dokter['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: dashboard_dokter.php"); // Redirect ke halaman dashboard dokter
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
