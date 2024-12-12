<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: login_dokter.php");
    exit;
}

// Ambil riwayat pasien berdasarkan ID pasien
$query = "SELECT * FROM riwayat_pasien WHERE username_dokter='{$_SESSION['username']}'";
$result = mysqli_query($koneksi, $query);
$riwayat_pasien = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center">Riwayat Pasien</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pasien</th>
                <th>Catatan Medis</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($riwayat_pasien as $index => $pasien): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= $pasien['id_pasien']; ?></td>
                    <td><?= $pasien['catatan']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
