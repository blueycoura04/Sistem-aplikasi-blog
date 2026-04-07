<?php
include "../koneksi.php";

/* =========================
   CEK LOGIN
========================= */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* =========================
   CEK ROLE ADMIN
========================= */
if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

/* =========================
   CEK KONEKSI
========================= */
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

/* =========================
   HITUNG DATA
========================= */
$q1 = mysqli_query($conn,"SELECT COUNT(*) as total FROM artikel");
$jml_artikel = mysqli_fetch_assoc($q1)['total'];

$q2 = mysqli_query($conn,"SELECT COUNT(*) as total FROM kategori");
$jml_kategori = mysqli_fetch_assoc($q2)['total'];

$q3 = mysqli_query($conn,"SELECT COUNT(*) as total FROM users");
$jml_users = mysqli_fetch_assoc($q3)['total'];

$q4 = mysqli_query($conn,"SELECT COUNT(*) as total FROM komentar");
$jml_komentar = mysqli_fetch_assoc($q4)['total'];

/* =========================
   AMBIL DATA ARTIKEL TERBARU
========================= */
$artikel = mysqli_query($conn,"SELECT * FROM artikel ORDER BY tanggal DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard - Blog</title>
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

/* Cards */
.card-primary {
    background: linear-gradient(135deg, #ff9900, #ffd700);
    font-weight: 600;
}
.card-success {
    background: linear-gradient(135deg, #4da6ff, #1a3d7c);
    color: #fff;
    font-weight: 600;
}
.card-warning {
    background: linear-gradient(135deg, #ffcc66, #bfa300);
    font-weight: 600;
}
.card-info {
    background: linear-gradient(135deg, #6c757d, #343a40);
    color: #fff;
    font-weight: 600;
}

/* Table */
.table thead {
    background: linear-gradient(90deg, #ff6f00, #002366);
    color: #fff;
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
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">Halo Kata</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a class="nav-link active" href="index.php?menu=dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel">Artikel</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=kategori">Kategori</a></li>
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
    <!-- WELCOME -->
    <h3>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></h3>

    <!-- CARD STATISTIK -->
    <div class="row mt-4 g-3">
        <div class="col-md-3">
            <div class="card card-primary text-center shadow">
                <div class="card-body">
                    <h5>Artikel</h5>
                    <h2><?= $jml_artikel ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-success text-center shadow">
                <div class="card-body">
                    <h5>Kategori</h5>
                    <h2><?= $jml_kategori ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-warning text-center shadow">
                <div class="card-body">
                    <h5>Users</h5>
                    <h2><?= $jml_users ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-info text-center shadow">
                <div class="card-body">
                    <h5>Komentar</h5>
                    <h2><?= $jml_komentar ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL ARTIKEL TERBARU -->
    <div class="mt-5">
        <h4>Artikel Terbaru</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Isi</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($artikel) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($artikel)): ?>
                <tr>
                    <td><?= $row['id_artikel']; ?></td>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= htmlspecialchars(substr($row['isi'],0,80)); ?>...</td>
                    <td><?= $row['tanggal']; ?></td>
                    <td>
                        <a href="edit_artikel.php?id=<?= $row['id_artikel']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus_artikel.php?id=<?= $row['id_artikel']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada artikel</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    &copy; <?= date('Y'); ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>