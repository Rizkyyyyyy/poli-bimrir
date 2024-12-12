<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=LoginUser.php");
    exit;
}

include 'koneksi.php';

// Tambah data obat
if (isset($_POST['tambah'])) {
    $nama_obat = $_POST['nama_obat'];
    $jenis_obat = $_POST['jenis_obat'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $query = "INSERT INTO obat (nama_obat, jenis_obat, stok, harga) VALUES ('$nama_obat', '$jenis_obat', '$stok', '$harga')";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=obat.php");
    exit;
}

// Ambil data obat untuk di-edit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM obat WHERE id='$id'";
    $hasil_edit = mysqli_query($koneksi, $query);
    $obat = mysqli_fetch_assoc($hasil_edit);
}

// Update data obat
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_obat = $_POST['nama_obat'];
    $jenis_obat = $_POST['jenis_obat'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $query = "UPDATE obat SET nama_obat='$nama_obat', jenis_obat='$jenis_obat', stok='$stok', harga='$harga' WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=obat.php");
    exit;
}

// Hapus data obat
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM obat WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: index.php?page=obat.php");
    exit;
}

$query = "SELECT * FROM obat";
$hasil = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Obat</title>
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
        <h2 class="text-center"><?= isset($obat) ? 'Edit Obat' : 'Tambah Obat'; ?></h2>
        <form method="POST" class="w-75 mx-auto">
            <input type="hidden" name="id" value="<?= isset($obat) ? $obat['id'] : ''; ?>">
            <div class="mb-3">
                <label>Nama Obat</label>
                <input type="text" name="nama_obat" class="form-control" value="<?= isset($obat) ? $obat['nama_obat'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label>Jenis Obat</label>
                <input type="text" name="jenis_obat" class="form-control" value="<?= isset($obat) ? $obat['jenis_obat'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= isset($obat) ? $obat['stok'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" value="<?= isset($obat) ? $obat['harga'] : ''; ?>" required>
            </div>
            <?php if (isset($obat)): ?>
                <button type="submit" name="update" class="btn btn-success w-100">Update Obat</button>
            <?php else: ?>
                <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah Obat</button>
            <?php endif; ?>
        </form>
    </div>

    <div class="mt-5">
        <h3 class="text-center mb-3">Data Obat</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Obat</th>
                    <th>Jenis Obat</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['nama_obat']; ?></td>
                        <td><?= $row['jenis_obat']; ?></td>
                        <td><?= $row['stok']; ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="index.php?page=obat.php&edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="index.php?page=obat.php&hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
