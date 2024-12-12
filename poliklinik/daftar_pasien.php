<?php
include 'koneksi.php';

// Proses Pendaftaran Pasien
$pesan = ""; // Inisialisasi pesan
$pesan_tipe = ""; // Inisialisasi tipe pesan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $no_ktp = htmlspecialchars(trim($_POST['no_ktp']));
    $poli = htmlspecialchars(trim($_POST['poli'])); // Ambil data poli

    // Validasi Input Kosong
    if (empty($nama) || empty($alamat) || empty($no_ktp) || empty($poli)) {
        $pesan = "Semua bidang harus diisi!";
        $pesan_tipe = "danger";
    } else {
        // Insert ke tabel pasien menggunakan prepared statements
        $query = "INSERT INTO pasien (nama, alamat, no_ktp, poli) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $alamat, $no_ktp, $poli);

        if (mysqli_stmt_execute($stmt)) {
            $pesan = "Terima kasih sudah mendaftar.";
            $pesan_tipe = "success";
        } else {
            $pesan = "Pendaftaran gagal. Silakan coba lagi!";
            $pesan_tipe = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn {
            border-radius: 20px;
        }
        .alert-success {
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h2 class="text-center mb-4">Pendaftaran Pasien</h2>

        <?php if (!empty($pesan)): ?>
            <div class="alert alert-<?= $pesan_tipe ?>"><?= $pesan ?></div>
        <?php endif; ?>

        <form method="POST" class="w-75 mx-auto">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>
            <div class="mb-3">
                <label>Nomor KTP</label>
                <input type="text" name="no_ktp" class="form-control" placeholder="Masukkan nomor KTP" required>
            </div>
            <div class="mb-3">
                <label>Poli</label>
                <select name="poli" class="form-control" required>
                    <option value="">Pilih Poli</option>
                    <option value="Poli Umum">Poli Umum</option>
                    <option value="Poli Gigi">Poli Gigi</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>

        <!-- Tombol Kembali ke Halaman Login -->
        <div class="text-center mt-3">
            <a href="LoginUser.php" class="btn btn-secondary w-100">Kembali ke Halaman Login</a>
        </div>
    </div>
</div>
</body>
</html>