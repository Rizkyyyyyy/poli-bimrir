<?php 
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
} 
if (!isset($_SESSION['username'])) { 
    header("Location: index.php?page=LoginUser.php"); 
    exit; 
} 

include 'koneksi.php'; 

// Tambah data dokter 
if (isset($_POST['tambah'])) { 
    $nama = $_POST['nama']; 
    $spesialis = $_POST['spesialis']; 
    $telepon = $_POST['telepon']; 
    $kategori_poli = $_POST['kategori_poli']; 
    $query = "INSERT INTO dokter (nama, spesialis, telepon, kategori_poli) VALUES ('$nama', '$spesialis', '$telepon', '$kategori_poli')"; 
    mysqli_query($koneksi, $query); 
    // Mendapatkan id dokter terakhir yang ditambahkan 
    $id_dokter = mysqli_insert_id($koneksi); 
    
    // Menambahkan jadwal jika ada input jadwal 
    if (isset($_POST['hari']) && isset($_POST['jam_mulai']) && isset($_POST['jam_selesai'])) { 
        $hari = $_POST['hari']; 
        $jam_mulai = $_POST['jam_mulai']; 
        $jam_selesai = $_POST['jam_selesai']; 
        $query_jadwal = "INSERT INTO jadwal (id_dokter, hari, jam_mulai, jam_selesai) 
                        VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai')"; 
        mysqli_query($koneksi, $query_jadwal); 
    } 

    header("Location: index.php?page=dokter.php"); 
    exit; 
} 

// Ambil data dokter untuk di-edit 
if (isset($_GET['edit'])) { 
    $id = $_GET['edit']; 
    $query = "SELECT * FROM dokter WHERE id='$id'"; 
    $hasil_edit = mysqli_query($koneksi, $query); 
    $dokter = mysqli_fetch_assoc($hasil_edit); 
} 

// Update data dokter 
if (isset($_POST['update'])) { 
    $id = $_POST['id']; 
    $nama = $_POST['nama']; 
    $spesialis = $_POST['spesialis']; 
    $telepon = $_POST['telepon']; 
    $kategori_poli = $_POST['kategori_poli']; 
    $query = "UPDATE dokter SET nama='$nama', spesialis='$spesialis', telepon='$telepon', kategori_poli='$kategori_poli' WHERE id='$id'"; 
    mysqli_query($koneksi, $query); 
    
    // Menambahkan atau memperbarui jadwal 
    if (isset($_POST['hari']) && isset($_POST['jam_mulai']) && isset($_POST['jam_selesai'])) { 
        $hari = $_POST['hari']; 
        $jam_mulai = $_POST['jam_mulai']; 
        $jam_selesai = $_POST['jam_selesai']; 
        $query_jadwal = "INSERT INTO jadwal (id_dokter, hari, jam_mulai, jam_selesai) 
                        VALUES ('$id', '$hari', '$jam_mulai', '$jam_selesai')"; 
        mysqli_query($koneksi, $query_jadwal); 
    } 

    header("Location: index.php?page=dokter.php"); 
    exit; 
} 

// Hapus data dokter 
if (isset($_GET['hapus'])) { 
    $id = $_GET['hapus']; 
    $query = "DELETE FROM dokter WHERE id='$id'"; 
    mysqli_query($koneksi, $query); 
    
    // Hapus jadwal terkait 
    $query_jadwal = "DELETE FROM jadwal WHERE id_dokter='$id'"; 
    mysqli_query($koneksi, $query_jadwal); 

    header("Location: index.php?page=dokter.php"); 
    exit; 
} 

// Ambil data dokter beserta jadwal 
$query_dokter = "SELECT d.*, j.hari, j.jam_mulai, j.jam_selesai FROM dokter d LEFT JOIN jadwal j ON d.id = j.id_dokter"; 
$hasil_dokter = mysqli_query($koneksi, $query_dokter); 
?> 

<!DOCTYPE html> 
<html lang="id"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Data Dokter</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <style> 
        body { 
            background-color: #f8f9fa; 
        } 
        .card { 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        } 
        .table th { 
            background-color: #007bff; 
            color: white; 
            text-align: center; 
        } 
        .table td { 
            text-align: center; 
        } 
        .btn-warning { 
            color: #fff; 
        } 
        .form-control { 
            border-radius: 10px; 
        } 
        .btn { 
            border-radius: 20px; 
        } 
    </style> 
</head> 
<body> 
<div class="container mt-5"> 
    <!-- Form untuk Tambah/Edit Dokter --> 
    <div class="card p-4"> 
        <h2 class="text-center mb-4"><?= isset($dokter) ? 'Edit Dokter' : 'Tambah Dokter'; ?></h2> 
        <form method="POST" class="w-75 mx-auto"> 
            <div class="mb-3"> 
                <label>Nama</label> 
                <input type="text" name="nama" class="form-control" value="<?= isset($dokter) ? $dokter['nama'] : ''; ?>" required> 
            </div> 
            <div class="mb-3"> 
                <label>Spesialis</label> 
                <input type="text" name="spesialis" class="form-control" value="<?= isset($dokter) ? $dokter['spesialis'] : ''; ?>" required> 
            </div> 
            <div class="mb-3"> 
                <label>Telepon</label> 
                <input type="text" name="telepon" class="form-control" value="<?= isset($dokter) ? $dokter['telepon'] : ''; ?>" required> 
            </div> 
            <div class="mb-3"> 
                <label>Kategori Poli</label> 
                <select name="kategori_poli" class="form-control" required> 
                    <option value="Poli Umum" <?= isset($dokter) && $dokter['kategori_poli'] == 'Poli Umum' ? 'selected' : ''; ?>>Poli Umum</option> 
                    <option value="Poli Gigi" <?= isset($dokter) && $dokter['kategori_poli'] == 'Poli Gigi' ? 'selected' : ''; ?>>Poli Gigi</option> 
                </select> 
            </div> 

            <!-- Form Jadwal Dokter (di bawah kategori poli) --> 
            <div class="mb-3"> 
                <label>Hari</label> 
                <select name="hari" class="form-control"> 
                    <option value="Senin">Senin</option> 
                    <option value="Selasa">Selasa</option> 
                    <option value="Rabu">Rabu</option> 
                    <option value="Kamis">Kamis</option> 
                    <option value="Jumat">Jumat</option> 
                    <option value="Sabtu">Sabtu</option> 
                    <option value="Minggu">Minggu</option> 
                </select> 
            </div> 
            <div class="mb-3"> 
                <label>Jam Mulai</label> 
                <input type="time" name="jam_mulai" class="form-control"> 
            </div> 
            <div class="mb-3"> 
                <label>Jam Selesai</label> 
                <input type="time" name="jam_selesai" class="form-control"> 
            </div> 

            <!-- Button Submit --> 
            <button type="submit" name="tambah" class="btn btn-primary w-100"><?= isset($dokter) ? 'Update Dokter' : 'Tambah Dokter'; ?></button> 
        </form> 
    </div> 

    <!-- Table untuk Menampilkan Data Dokter --> 
    <div class="card mt-5 p-4"> 
        <h2 class="text-center mb-4">Data Dokter</h2> 
        <table class="table table-bordered"> 
            <thead> 
                <tr> 
                    <th>ID</th> 
                    <th>Nama</th> 
                    <th>Spesialis</th> 
                    <th>Telepon</th> 
                    <th>Kategori Poli</th> 
                    <th>Hari</th> 
                    <th>Jam Mulai</th> 
                    <th>Jam Selesai</th> 
                    <th>Aksi</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php while ($row = mysqli_fetch_assoc($hasil_dokter)) { ?> 
                    <tr> 
                        <td><?= $row['id']; ?></td> 
                        <td><?= $row['nama']; ?></td> 
                        <td><?= $row['spesialis']; ?></td> 
                        <td><?= $row['telepon']; ?></td> 
                        <td><?= $row['kategori_poli']; ?></td> 
                        <td><?= $row['hari']; ?></td> 
                        <td><?= $row['jam_mulai']; ?></td> 
                        <td><?= $row['jam_selesai']; ?></td> 
                        <td> 
                            <!-- Tombol Lihat menggunakan data-bs-toggle untuk modal -->
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#lihatModal<?= $row['id']; ?>">Lihat</button>
                            
                            <!-- Modal untuk melihat detail dokter -->
                            <div class="modal fade" id="lihatModal<?= $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detail Dokter</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nama:</strong> <?= $row['nama']; ?></p>
                                            <p><strong>Spesialis:</strong> <?= $row['spesialis']; ?></p>
                                            <p><strong>Telepon:</strong> <?= $row['telepon']; ?></p>
                                            <p><strong>Kategori Poli:</strong> <?= $row['kategori_poli']; ?></p>
                                            <p><strong>Hari Praktek:</strong> <?= $row['hari']; ?></p>
                                            <p><strong>Jam Mulai:</strong> <?= $row['jam_mulai']; ?></p>
                                            <p><strong>Jam Selesai:</strong> <?= $row['jam_selesai']; ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Edit -->
                            <a href="index.php?page=dokter.php&edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a> 
                            
                            <!-- Tombol Hapus -->
                            <a href="index.php?page=dokter.php&hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a> 
                        </td> 
                    </tr> 
                <?php } ?> 
            </tbody> 
        </table> 
    </div> 
</div> 

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>
