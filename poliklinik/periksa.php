<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=LoginUser.php");
    exit;
}

include 'koneksi.php';

// Tambah Data Periksa
if (isset($_POST['tambah'])) {
    $id_dokter = $_POST['id_dokter'];
    $id_pasien = $_POST['id_pasien'];
    $id_obat = $_POST['id_obat']; // Ambil id_obat dari form
    $tanggal = $_POST['tanggal'];
    $catatan = $_POST['catatan'];

    // Ambil harga obat berdasarkan id_obat
    $obat_query = "SELECT harga FROM obat WHERE id = '$id_obat'";
    $obat_result = mysqli_query($koneksi, $obat_query);
    $obat = mysqli_fetch_assoc($obat_result);
    $harga_obat = $obat['harga'];

    // Hitung biaya periksa (harga obat + 10.000 untuk jasa periksa)
    $biaya_periksa = $harga_obat + 10000;

    // Query untuk memasukkan data periksa
    $query = "INSERT INTO periksa (id_dokter, id_pasien, biaya_periksa, tanggal, catatan, id_obat) VALUES ('$id_dokter', '$id_pasien', '$biaya_periksa', '$tanggal', '$catatan', '$id_obat')";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=periksa.php");
}

// Edit Data Periksa
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $id_dokter = $_POST['id_dokter'];
    $id_pasien = $_POST['id_pasien'];
    $id_obat = $_POST['id_obat']; // Ambil id_obat dari form
    $tanggal = $_POST['tanggal'];
    $catatan = $_POST['catatan'];

    // Ambil harga obat berdasarkan id_obat
    $obat_query = "SELECT harga FROM obat WHERE id = '$id_obat'";
    $obat_result = mysqli_query($koneksi, $obat_query);
    $obat = mysqli_fetch_assoc($obat_result);
    $harga_obat = $obat['harga'];

    // Hitung biaya periksa (harga obat + 10.000 untuk jasa periksa)
    $biaya_periksa = $harga_obat + 10000;

    // Query untuk mengupdate data periksa
    $query = "UPDATE periksa SET id_dokter='$id_dokter', id_pasien='$id_pasien', biaya_periksa='$biaya_periksa', tanggal='$tanggal', catatan='$catatan', id_obat='$id_obat' WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=periksa.php");
}

// Hapus Data Periksa
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM periksa WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=periksa.php");
}

// Tampil Data Periksa
$query = "SELECT periksa.id, dokter.nama AS nama_dokter, pasien.nama AS nama_pasien, periksa.biaya_periksa, periksa.tanggal, periksa.catatan, obat.nama_obat 
          FROM periksa
          JOIN dokter ON periksa.id_dokter = dokter.id
          JOIN pasien ON periksa.id_pasien = pasien.id
          JOIN obat ON periksa.id_obat = obat.id";
$hasil = mysqli_query($koneksi, $query);

// Data Dokter, Pasien, dan Obat untuk Dropdown
$dokter_query = mysqli_query($koneksi, "SELECT * FROM dokter");
$pasien_query = mysqli_query($koneksi, "SELECT * FROM pasien");
$obat_query = mysqli_query($koneksi, "SELECT * FROM obat");

// Jika ada permintaan edit, ambil data periksa yang akan diedit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query_edit = "SELECT * FROM periksa WHERE id='$id'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $edit_data = mysqli_fetch_assoc($result_edit);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Periksa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
        }
        .form-control, .form-select {
            border-radius: 10px;
        }
        .btn {
            border-radius: 20px;
        }
        .btn-warning {
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h2 class="text-center"><?= isset($edit_data) ? 'Edit Data Periksa' : 'Tambah Data Periksa'; ?></h2>
        <!-- Form Tambah atau Edit Periksa -->
        <form method="POST" class="w-75 mx-auto">
            <input type="hidden" name="id" value="<?= isset($edit_data) ? $edit_data['id'] : ''; ?>">
            <div class="mb-3">
                <label>Dokter</label>
                <select name="id_dokter" class="form-select" required>
                    <option value="" disabled selected>Pilih Dokter</option>
                    <?php while ($dokter = mysqli_fetch_assoc($dokter_query)) {
                        $selected = isset($edit_data) && $edit_data['id_dokter'] == $dokter['id'] ? 'selected' : '';
                        echo "<option value='{$dokter['id']}' $selected>{$dokter['nama']}</option>";
                    } ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Pasien</label>
                <select name="id_pasien" class="form-select" required>
                    <option value="" disabled selected>Pilih Pasien</option>
                    <?php while ($pasien = mysqli_fetch_assoc($pasien_query)) {
                        $selected = isset($edit_data) && $edit_data['id_pasien'] == $pasien['id'] ? 'selected' : '';
                        echo "<option value='{$pasien['id']}' $selected>{$pasien['nama']}</option>";
                    } ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Biaya Periksa</label>
                <input type="number" name="biaya_periksa" class="form-control" required value="<?= isset($edit_data) ? $edit_data['biaya_periksa'] : ''; ?>" readonly>
            </div>
            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required value="<?= isset($edit_data) ? $edit_data['tanggal'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" required><?= isset($edit_data) ? $edit_data['catatan'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label>Obat</label>
                <select name="id_obat" class="form-select" required>
                    <option value="" disabled selected>Pilih Obat</option>
                    <?php while ($obat = mysqli_fetch_assoc($obat_query)) {
                        $selected = isset($edit_data) && $edit_data['id_obat'] == $obat['id'] ? 'selected' : '';
                        echo "<option value='{$obat['id']}' $selected>{$obat['nama_obat']}</option>";
                    } ?>
                </select>
            </div>
            <?php if (isset($edit_data)) { ?>
                <button type="submit" name="update" class="btn btn-success w-100">Update Data</button>
            <?php } else { ?>
                <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah Data</button>
            <?php } ?>
        </form>
    </div>

    <div class="mt-5">
        <h3 class="text-center mb-3">Data Periksa</h3>
        <!-- Tabel Data Periksa -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Dokter</th>
                    <th>Pasien</th>
                    <th>Biaya Periksa</th>
                    <th>Tanggal</th>
                    <th>Catatan</th>
                    <th>Obat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['nama_dokter']; ?></td>
                        <td><?= $row['nama_pasien']; ?></td>
                        <td>Rp <?= number_format($row['biaya_periksa'], 0, ',', '.'); ?></td>
                        <td><?= $row['tanggal']; ?></td>
                        <td><?= $row['catatan']; ?></td>
                        <td><?= $row['nama_obat']; ?></td>
                        <td>
                            <a href="index.php?page=periksa.php&edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="index.php?page=periksa.php&hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
