<?php
include 'koneksi.php';

// Pastikan hanya dokter yang dapat mengakses halaman ini
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: login_dokter.php"); // Arahkan ke halaman login jika bukan dokter
    exit;
}

// Ambil username dari sesi
$username = $_SESSION['username'];

// Ambil data dokter dari database berdasarkan username
$query = "SELECT * FROM dokter WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);
$dokter = mysqli_fetch_assoc($result);

// Proses update data dokter jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $telepon = $_POST['telepon'];
    $kategori_poli = $_POST['kategori_poli'];

    // Query untuk update data dokter
    $updateQuery = "UPDATE dokter SET nama='$nama', spesialis='$spesialis', telepon='$telepon', kategori_poli='$kategori_poli' WHERE username='$username'";

    if (mysqli_query($koneksi, $updateQuery)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='dokter_dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Dokter Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dokter_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Form Update Data Dokter -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Update Data Dokter</h3>

    <!-- Form untuk edit data dokter -->
    <form method="POST">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($dokter['nama']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="spesialis" class="form-label">Spesialis</label>
            <input type="text" class="form-control" id="spesialis" name="spesialis" value="<?= htmlspecialchars($dokter['spesialis']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="telepon" name="telepon" value="<?= htmlspecialchars($dokter['telepon']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="kategori_poli" class="form-label">Kategori Poli</label>
            <input type="text" class="form-control" id="kategori_poli" name="kategori_poli" value="<?= htmlspecialchars($dokter['kategori_poli']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Perbarui Data</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
