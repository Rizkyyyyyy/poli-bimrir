<?php 
include 'koneksi.php'; 
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $username = $_POST['username']; 
    $password = $_POST['password']; 
    $role = $_POST['role'];  // Ambil role dari form login

    // Cek berdasarkan role
    if ($role == 'admin' || $role == 'dokter') {
        // Login untuk admin dan dokter menggunakan username dan password biasa
        $query = "SELECT * FROM user WHERE username='$username' AND role='$role'"; 
        $result = mysqli_query($koneksi, $query); 
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) { 
            // Login berhasil, simpan session
            $_SESSION['username'] = $username; 
            $_SESSION['role'] = $role; 

            // Redirect berdasarkan role
            if ($role == 'admin') { 
                header("Location: index.php"); 
            } elseif ($role == 'dokter') { 
                header("Location: dokter_dashboard.php");  // Redirect ke halaman dokter
            }
            exit; 
        } else { 
            $error = "Username atau Password salah!"; 
        }
    } elseif ($role == 'pasien') {
        // Login untuk pasien menggunakan nama sebagai username dan 3 digit terakhir RM sebagai password
        $query = "SELECT * FROM pasien WHERE nama='$username'"; 
        $result = mysqli_query($koneksi, $query); 
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            // Ambil nomor RM dan cek 3 digit terakhirnya
            $no_rm = $user['no_rm']; 
            $last_3_digits = substr($no_rm, -3); // 3 digit terakhir dari nomor RM

            // Verifikasi password dengan 3 digit terakhir nomor RM
            if ($password == $last_3_digits) {
                // Login berhasil, simpan session
                $_SESSION['username'] = $username; 
                $_SESSION['role'] = $role; 
                $_SESSION['id_pasien'] = $user['id']; // Simpan ID pasien untuk keperluan lain
                
                // Redirect ke halaman pasien
                header("Location: daftar_pasien.php");
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Nama pasien tidak ditemukan!";
        }
    } else {
        $error = "Peran tidak valid!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #d3d3d3;
            background-size: cover;
        }
        .card {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
        }
        .input-group-text {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width: 400px;">
        <h2 class="text-center mb-4">Login</h2>

        <!-- Menampilkan error jika ada -->
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

        <!-- Form Login -->
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Peran</label>
                <select name="role" class="form-select" required>
                    <option value="" disabled selected>Pilih peran</option>
                    <option value="admin">Admin</option>
                    <option value="dokter">Dokter</option>
                    <option value="pasien">Pasien</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <p>Belum punya akun? <a href="index.php?page=RegistrasiUser.php">Register</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
