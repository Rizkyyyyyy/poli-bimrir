<?php
include 'koneksi.php';
session_start();

// Pastikan hanya dokter yang bisa mengakses halaman ini
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: login_dokter.php");
    exit;
}

// Ambil username dokter yang sedang login
$username_dokter = $_SESSION['username'];

// Ambil daftar pasien beserta nomor RM dari database
$query_pasien = "SELECT id, nama, no_rm FROM pasien";
$hasil_pasien = mysqli_query($koneksi, $query_pasien);

// Ambil daftar obat dari database
$query_obat = "SELECT nama_obat FROM obat";
$hasil_obat = mysqli_query($koneksi, $query_obat);

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_pasien = $_POST['id_pasien'];
    $no_rm = $_POST['no_rm']; // No RM otomatis diambil dari form yang diisi JS
    $sakit_yang_diderita = $_POST['sakit_yang_diderita'];
    $catatan_obat = $_POST['catatan_obat'];
    $biaya_periksa = $_POST['biaya_periksa'];

    // Query untuk menyimpan data jadwal periksa
    $query = "INSERT INTO jadwal_periksa (username_dokter, id_pasien, no_rm, sakit_yang_diderita, catatan_obat, biaya_periksa) 
              VALUES ('$username_dokter', '$id_pasien', '$no_rm', '$sakit_yang_diderita', '$catatan_obat', '$biaya_periksa')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Jadwal periksa berhasil dibuat!'); window.location.href='dokter_dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal membuat jadwal periksa.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Jadwal Periksa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Form untuk membuat jadwal periksa -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Buat Jadwal Periksa Pasien</h3>

    <form method="POST">
        <div class="mb-3">
            <label for="id_pasien" class="form-label">Nama Pasien</label>
            <select name="id_pasien" id="id_pasien" class="form-control" required onchange="isiNoRM()">
                <option value="">-- Pilih Pasien --</option>
                <?php while ($row_pasien = mysqli_fetch_assoc($hasil_pasien)) { ?>
                    <option value="<?= $row_pasien['id']; ?>" data-no-rm="<?= $row_pasien['no_rm']; ?>"><?= $row_pasien['nama']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="no_rm" class="form-label">Nomor Rekam Medis (RM)</label>
            <input type="text" class="form-control" id="no_rm" name="no_rm" readonly required>
        </div>
        <div class="mb-3">
            <label for="sakit_yang_diderita" class="form-label">Sakit yang Diderita</label>
            <textarea class="form-control" id="sakit_yang_diderita" name="sakit_yang_diderita" required></textarea>
        </div>
        <div class="mb-3">
            <label for="catatan_obat" class="form-label">Catatan Obat</label>
            <select name="catatan_obat" id="catatan_obat" class="form-control" required>
                <option value="">-- Pilih Obat --</option>
                <?php while ($row_obat = mysqli_fetch_assoc($hasil_obat)) { ?>
                    <option value="<?= $row_obat['nama_obat']; ?>"><?= $row_obat['nama_obat']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="biaya_periksa" class="form-label">Biaya Periksa</label>
            <input type="number" step="0.01" class="form-control" id="biaya_periksa" name="biaya_periksa" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100">Buat Jadwal Periksa</button>
    </form>
</div>

<script>
// Fungsi untuk mengisi nomor RM berdasarkan pasien yang dipilih
function isiNoRM() {
    var selectPasien = document.getElementById('id_pasien');
    var noRMInput = document.getElementById('no_rm');

    // Dapatkan option yang dipilih
    var selectedOption = selectPasien.options[selectPasien.selectedIndex];
    
    // Ambil data no_rm dari atribut data-no-rm di option yang dipilih
    var noRM = selectedOption.getAttribute('data-no-rm');

    // Isi input no_rm dengan nilai noRM
    noRMInput.value = noRM;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
