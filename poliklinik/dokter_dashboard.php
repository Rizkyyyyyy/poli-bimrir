<?php
include 'koneksi.php';
session_start();

// Pastikan hanya dokter yang bisa mengakses halaman ini
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: login.php"); // Arahkan ke halaman login jika bukan dokter
    exit;
}

$username = $_SESSION['username'];

// Ambil data dokter berdasarkan username
$query = "SELECT * FROM dokter WHERE username = '$username'"; // Mengambil data dokter berdasarkan username
$result = mysqli_query($koneksi, $query);

// Jika dokter ditemukan
if ($dokter = mysqli_fetch_assoc($result)) {
    $nama = $dokter['nama'];
    $spesialis = $dokter['spesialis'];
    $telepon = $dokter['telepon'];
    $kategori_poli = $dokter['kategori_poli'];
} else {
    echo "Data dokter tidak ditemukan.";
}

// Ambil data jadwal periksa dokter ini
$query_jadwal = "SELECT * FROM jadwal_periksa WHERE username_dokter = '$username'"; // Mengambil data jadwal periksa
$result_jadwal = mysqli_query($koneksi, $query_jadwal);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
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
                    <a class="nav-link" href="update_dokter.php">Perbarui Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="buat_jadwal.php">Buat Jadwal Periksa</a> <!-- Link ke buat_jadwal.php -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Konten Dashboard Dokter -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Selamat datang, Dr. <?= ($nama); ?>!</h3>
    <p class="text-center">Silakan perbarui data Anda jika diperlukan.</p>

    <!-- Tabel Data Dokter -->
    <h4>Data Dokter</h4>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Spesialis</th>
                <th>Telepon</th>
                <th>Kategori Poli</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= ($nama); ?></td>
                <td><?= ($spesialis); ?></td>
                <td><?= ($telepon); ?></td>
                <td><?= ($kategori_poli); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Tabel Data Jadwal Periksa -->
    <h4 class="mt-5">Jadwal Periksa Anda</h4>
    <?php if (mysqli_num_rows($result_jadwal) > 0): ?>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID Jadwal</th>
                    <th>No RM Pasien</th>
                    <th>Sakit yang Diderita</th>
                    <th>Catatan Obat</th>
                    <th>Biaya Periksa</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_jadwal)): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['no_rm']; ?></td>
                        <td><?= $row['sakit_yang_diderita']; ?></td>
                        <td><?= $row['catatan_obat']; ?></td>
                        <td><?= "Rp. " . number_format($row['biaya_periksa'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada jadwal periksa yang dibuat.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
