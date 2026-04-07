<?php
session_start();
include "../../koneksi.php";

/* =========================
   CEK LOGIN & ROLE
========================= */
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

/* =========================
   CEK KONEKSI
========================= */
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

/* =========================
   AMBIL ID KATEGORI
========================= */
if(!isset($_GET['id'])){
    header("Location: kategori.php");
    exit;
}
$id = (int)$_GET['id'];

/* =========================
   AMBIL DATA KATEGORI
========================= */
$result = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori = $id");
$kategori = mysqli_fetch_assoc($result);
if(!$kategori){
    die("Kategori tidak ditemukan!");
}

/* =========================
   FORM SUBMIT
========================= */
$error = '';
if(isset($_POST['submit'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    
    if(empty($nama)){
        $error = "Nama kategori tidak boleh kosong!";
    } else {
        $update = mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama' WHERE id_kategori=$id");
        if($update){
            header("Location: index.php?menu=kategori");
            exit;
        } else {
            $error = "Gagal mengubah kategori: " . mysqli_error($conn);
        }
    }
}

$namaAdmin = isset($_SESSION['username']) ? $_SESSION['username'] : "Admin";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Kategori - Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(160deg, #f0f0f5, #d0e0ff);
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
    padding-bottom: 70px;
}

/* Navbar */
.navbar {
    background: linear-gradient(90deg, #ff6f00, #002366);
}
.navbar .nav-link {
    color: #fff !important;
    font-weight: 600;
}

/* Button logout */
.btn-logout {
    background-color: #bfa300;
    color: #fff;
    font-weight: 600;
}
.btn-logout:hover {
    background-color: #a18600;
}

/* Form Card */
.card {
    border-radius: 10px;
}

/* Footer */
footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background: linear-gradient(90deg, #ff6f00, #002366);
    color: #fff;
    padding: 10px;
    text-align: center;
}
</style>
</head>

<body>

<!-- NAVBAR ADMIN -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../dashboard.php">Halo, <?= htmlspecialchars($namaAdmin); ?></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a class="nav-link" href="../index.php?menu=dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="../index.php?menu=artikel">Artikel</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php?menu=kategori">Kategori</a></li>
        <li class="nav-item"><a class="nav-link" href="../index.php?menu=komentar">Komentar</a></li>
        <li class="nav-item"><a class="nav-link" href="../index.php?menu=users">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=tag">Tag</a></li>
        <li class="nav-item ms-3">
            <a class="btn btn-logout" href="../logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT EDIT KATEGORI -->
<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <h3>Edit Kategori</h3>

        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">id_kategori</label>
                <input type="text" name="id_kategori" class="form-control" 
                       value="<?= htmlspecialchars($kategori['nama_kategori']); ?>" required>
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" 
                       value="<?= htmlspecialchars($kategori['nama_kategori']); ?>" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="index.php?menu=kategori" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<footer>
    &copy; <?= date('Y'); ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>