<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=LoginUser.php");
    exit;
}

include 'koneksi.php';

// Variabel untuk menyimpan pesan sukses
$pesan_sukses = '';

// Menambahkan Poli
if (isset($_POST['tambah'])) {
    $kategori_poli = $_POST['kategori_poli'];

    $query = "INSERT INTO poli (kategori_poli) VALUES ('$kategori_poli')";
    if (mysqli_query($koneksi, $query)) {
        $pesan_sukses = "Data poli berhasil ditambahkan!";
    } else {
        $pesan_sukses = "Terjadi kesalahan saat menambahkan data poli.";
    }
}

// Mengupdate Poli
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $kategori_poli = $_POST['kategori_poli'];

    $query = "UPDATE poli SET kategori_poli='$kategori_poli' WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) {
        $pesan_sukses = "Data poli berhasil diupdate!";
    } else {
        $pesan_sukses = "Terjadi kesalahan saat mengupdate data poli.";
    }
}

// Menghapus Poli
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM poli WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) {
        $pesan_sukses = "Data poli berhasil dihapus!";
    } else {
        $pesan_sukses = "Terjadi kesalahan saat menghapus data poli.";
    }
}

// Menampilkan data poli
$query = "SELECT * FROM poli";
$hasil = mysqli_query($koneksi, $query);

// Jika ada permintaan edit, ambil data poli yang akan diedit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query_edit = "SELECT * FROM poli WHERE id='$id'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $edit_data = mysqli_fetch_assoc($result_edit);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Poli</title>
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
        <h2 class="text-center"><?= isset($edit_data) ? 'Edit Data Poli' : 'Tambah Data Poli'; ?></h2>

        <!-- Tampilkan pesan sukses -->
        <?php if ($pesan_sukses) { ?>
            <div class="alert alert-success"><?= $pesan_sukses; ?></div>
        <?php } ?>

        <!-- Form Tambah atau Edit Poli -->
        <form method="POST" class="w-75 mx-auto">
            <input type="hidden" name="id" value="<?= isset($edit_data) ? $edit_data['id'] : ''; ?>">
            <div class="mb-3">
                <label>Kategori Poli</label>
                <input type="text" name="kategori_poli" class="form-control" value="<?= isset($edit_data) ? $edit_data['kategori_poli'] : ''; ?>" required>
            </div>
            <?php if (isset($edit_data)) { ?>
                <button type="submit" name="update" class="btn btn-success w-100">Update Data Poli</button>
            <?php } else { ?>
                <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah Data Poli</button>
            <?php } ?>
        </form>
    </div>

    <div class="mt-5">
        <h3 class="text-center mb-3">Data Poli</h3>
        <!-- Tabel Data Poli -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kategori Poli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['kategori_poli']; ?></td>
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
