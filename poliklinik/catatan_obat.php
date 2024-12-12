<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: login_dokter.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pasien = $_POST['id_pasien'];
    $obat = $_POST['obat'];

    // Insert catatan obat
    $query = "INSERT INTO catatan_obat (id_pasien, username_dokter, obat) VALUES ('$id_pasien', '{$_SESSION['username']}', '$obat')";
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Catatan obat berhasil ditambahkan!";
    } else {
        $pesan = "Gagal menambahkan catatan obat!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center">Catatan Obat untuk Pasien</h3>

    <?php if (isset($pesan)): ?>
        <div class="alert alert-info"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>ID Pasien</label>
            <input type="text" name="id_pasien" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Obat</label>
            <textarea name="obat" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Berikan Catatan Obat</button>
    </form>
</div>
</body>
</html>
