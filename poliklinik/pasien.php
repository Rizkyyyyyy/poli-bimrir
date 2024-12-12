<?php
// Pastikan hanya satu session_start() yang dipanggil
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=LoginUser.php");
    exit;
}

// Ambil tanggal sekarang
$tanggal_sekarang = date('Ymd');

// Tambah Data Pasien
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_rm_tiga_angka = $_POST['no_rm_tiga_angka']; // Hanya tiga angka terakhir yang bisa diubah
    $no_rm = $tanggal_sekarang . $no_rm_tiga_angka; // Gabungkan tanggal sekarang dengan tiga angka
    $no_ktp = $_POST['no_ktp'];
    $poli = $_POST['poli'];  // Menambahkan poli
    $query = "INSERT INTO pasien (nama, alamat, no_rm, no_ktp, poli) VALUES ('$nama', '$alamat', '$no_rm', '$no_ktp', '$poli')";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=pasien.php");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

// Edit Data Pasien
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_rm_tiga_angka = $_POST['no_rm_tiga_angka']; // Update tiga angka terakhir saja
    $no_rm = $tanggal_sekarang . $no_rm_tiga_angka; // Gabungkan kembali dengan tanggal sekarang
    $no_ktp = $_POST['no_ktp'];
    $poli = $_POST['poli'];  // Menambahkan poli
    $query = "UPDATE pasien SET nama='$nama', alamat='$alamat', no_rm='$no_rm', no_ktp='$no_ktp', poli='$poli' WHERE id='$id'";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=pasien.php");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

// Hapus Data Pasien
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM pasien WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=pasien.php");
}

// Tampil Data Pasien
$query = "SELECT * FROM pasien";
$hasil = mysqli_query($koneksi, $query);

// Jika ada permintaan edit, ambil data pasien yang akan diedit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query_edit = "SELECT * FROM pasien WHERE id='$id'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $edit_data = mysqli_fetch_assoc($result_edit);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>
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
        .btn-warning {
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h2 class="text-center"><?= isset($edit_data) ? 'Edit Pasien' : 'Tambah Pasien'; ?></h2>
        <!-- Form Tambah atau Edit Pasien -->
        <form method="POST" class="w-75 mx-auto">
            <input type="hidden" name="id" value="<?= isset($edit_data) ? $edit_data['id'] : ''; ?>">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required value="<?= isset($edit_data) ? $edit_data['nama'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required><?= isset($edit_data) ? $edit_data['alamat'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label>Nomor Rekam Medik</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $tanggal_sekarang ?>" readonly>
                    <input type="text" name="no_rm_tiga_angka" class="form-control" placeholder="3 digit angka" maxlength="3" pattern="\d{3}" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Nomor KTP</label>
                <input type="text" name="no_ktp" class="form-control" required value="<?= isset($edit_data) ? $edit_data['no_ktp'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label>Poli</label>
                <select name="poli" class="form-control" required>
                    <option value="Poli Umum" <?= isset($edit_data) && $edit_data['poli'] == 'Poli Umum' ? 'selected' : ''; ?>>Poli Umum</option>
                    <option value="Poli Gigi" <?= isset($edit_data) && $edit_data['poli'] == 'Poli Gigi' ? 'selected' : ''; ?>>Poli Gigi</option>
                </select>
            </div>
            <?php if (isset($edit_data)) { ?>
                <button type="submit" name="update" class="btn btn-success w-100">Update Pasien</button>
            <?php } else { ?>
                <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah Pasien</button>
            <?php } ?>
        </form>
    </div>

    <div class="mt-5">
        <h3 class="text-center mb-3">Data Pasien</h3>
        <!-- Tabel Data Pasien -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Nomor RM</th>
                    <th>Nomor KTP</th>
                    <th>Poli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['alamat']; ?></td>
                        <td><?= $row['no_rm']; ?></td>
                        <td><?= $row['no_ktp']; ?></td>
                        <td><?= $row['poli']; ?></td>
                        <td>
                            <a href="index.php?page=pasien.php&edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="index.php?page=pasien.php&hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
