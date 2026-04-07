<?php
include "../koneksi.php";

/* CEK LOGIN & ROLE */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

/* PROSES TAMBAH KATEGORI */
$error = "";
if(isset($_POST['submit'])){
    $id_kategori = mysqli_real_escape_string($conn, trim($_POST['id_kategori']));
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama_kategori']));

    if(!empty($id_kategori) && !empty($nama)){
        $cek = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori = '$id_kategori' OR nama_kategori = '$nama'");
        
        if(mysqli_num_rows($cek) > 0){
            $error = "ID atau Nama kategori sudah ada!";
        } else {
            $query = "INSERT INTO kategori (id_kategori, nama_kategori) VALUES ('$id_kategori', '$nama')";
            
            if(mysqli_query($conn, $query)){
                header("Location: index.php?menu=kategori");
                exit;
            } else {
                $error = "Gagal menambahkan kategori: " . mysqli_error($conn);
            }
        }
    } else {
        $error = "ID dan Nama kategori tidak boleh kosong!";
    }
}

/* AMBIL DATA KATEGORI */
$result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori ASC");
$kategoriList = mysqli_fetch_all($result, MYSQLI_ASSOC);

$namaAdmin = $_SESSION['username'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tambah Kategori</title>

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
    color: #fff !important;
    font-weight: 600;
}

.btn-logout {
    background-color: #bfa300;
    color: #fff;
    font-weight: 600;
}

.btn-logout:hover {
    background-color: #a18600;
}

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

<!-- NAVBAR (DISAMAKAN) -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">
        Halo, <?= htmlspecialchars($namaAdmin); ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel">Artikel</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php?menu=kategori">Kategori</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=komentar">Komentar</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=users">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=tag">Tag</a></li>
        <li class="nav-item ms-3">
            <a class="btn btn-logout" href="../logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

    <h3 class="mb-3">📂 Tambah Kategori</h3>

    <div class="card p-3">
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">ID Kategori</label>
                <input type="text" name="id_kategori" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php?menu=kategori" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>

</div>

<footer>
    &copy; <?= date('Y'); ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>