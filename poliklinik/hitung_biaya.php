<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: login_dokter.php"); // Jika bukan dokter, redirect ke login
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $biaya_periksa = $_POST['biaya_periksa'];
    $id_pasien = $_POST['id_pasien'];

    // Simpan biaya periksa di database
    $query = "INSERT INTO biaya_periksa (id_pasien, biaya) VALUES ('$id_pasien', '$biaya_periksa')";
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Biaya periksa berhasil disimpan!";
    } else {
        $pesan = "Gagal menyimpan biaya periksa!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung Biaya Periksa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center">Hitung Biaya Periksa</h3>

    <?php if (isset($pesan)): ?>
        <div class="alert alert-info"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>ID Pasien</label>
            <input type="text" name="id_pasien" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Biaya Periksa</label>
            <input type="number" name="biaya_periksa" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Hitung dan Simpan Biaya</button>
    </form>
</div>
</body>
</html>
