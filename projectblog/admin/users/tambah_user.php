<?php
include "../koneksi.php";

/* CEK LOGIN & ROLE ADMIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

$error = '';

if(isset($_POST['submit'])){
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $nama     = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    // VALIDASI
    if(empty($username) || empty($nama) || empty($email) || empty($password) || empty($role)){
        $error = "Semua field harus diisi!";
    } elseif(strlen($password) < 6){
        $error = "Password minimal 6 karakter!";
    } else {
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        
        if(mysqli_num_rows($cek) > 0){
            $error = "Username atau email sudah digunakan!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_query($conn, "
                INSERT INTO users (username, nama, email, password, role) 
                VALUES ('$username','$nama','$email','$hash','$role')
            ");

            if($insert){
                header("Location: index.php?menu=users&pesan=berhasil");
                exit;
            } else {
                $error = "Gagal menambah user: " . mysqli_error($conn);
            }
        }
    }
}

$namaAdmin = $_SESSION['username'] ?? "Admin";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(160deg, #f0f0f5, #d0e0ff);
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
    padding-bottom: 70px;
}
.navbar {
    background: linear-gradient(90deg, #ff6f00, #002366);
}
.navbar .nav-link {
    color:#fff !important;
    font-weight:600;
}
.btn-logout {
    background:#bfa300;
    color:#fff;
}
.card {
    border-radius: 10px;
}
footer {
    position: fixed;
    bottom:0;
    width:100%;
    background: linear-gradient(90deg,#ff6f00,#002366);
    color:#fff;
    text-align:center;
    padding:10px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php?menu=users">
        Halo, <?= htmlspecialchars($namaAdmin); ?>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="menu">
      <ul class="navbar-nav align-items-center">

        <li class="nav-item">
            <a class="nav-link" href="../index.php?menu=dashboard">Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../index.php?menu=artikel">Artikel</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../index.php?menu=kategori">Kategori</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../index.php?menu=komentar">Komentar</a>
        </li>

        <li class="nav-item">
            <a class="nav-link active" href="../index.php?menu=users">Users</a>
        </li>

        <li class="nav-item">
            <a class="nav-link active" href="../index.php?menu=tag">Tag</a>
        </li>

        <li class="nav-item ms-3">
            <a class="btn btn-logout" href="../../logout.php">Logout</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Tambah User</h3>
        <a href="projectblog/admin/index.php?menu=users" class="btn btn-secondary">← Kembali</a>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="penulis">Penulis</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-success">
                    Simpan
                </button>

                <a href="index.php?menu=users" class="btn btn-secondary">
                    Batal
                </a>

            </form>

        </div>
    </div>

</div>

<footer>
    &copy; <?= date('Y') ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>