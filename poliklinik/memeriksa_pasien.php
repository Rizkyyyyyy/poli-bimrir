<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: login_dokter.php"); // Jika bukan dokter, redirect ke login
    exit;
}

// Ambil daftar pasien dari database
$query_pasien = "SELECT id, nama FROM pasien";
$result_pasien = mysqli_query($koneksi, $query_pasien);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pasien = $_POST['id_pasien']; // Tetap menggunakan ID pasien untuk penyimpanan di database
    $catatan = $_POST['catatan'];

    // Insert catatan medis untuk pasien
    $query = "INSERT INTO catatan_medik (id_pasien, username_dokter, catatan) VALUES ('$id_pasien', '{$_SESSION['username']}', '$catatan')";
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Catatan medis berhasil ditambahkan!";
    } else {
        $pesan = "Gagal menambahkan catatan medis!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memeriksa Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center">Memeriksa Pasien</h3>

    <?php if (isset($pesan)): ?>
        <div class="alert alert-info"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Nama Pasien</label>
            <select name="id_pasien" class="form-control" required>
                <option value="">Pilih Nama Pasien</option>
                <?php while ($row = mysqli_fetch_assoc($result_pasien)): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Catatan Medis</label>
            <textarea name="catatan" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Simpan Catatan</button>
    </form>
</div>
</body>
</html>
