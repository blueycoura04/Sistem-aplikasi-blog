<?php
include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* AMBIL DATA */
$query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kategori</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* BODY */
body {
    background: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar-custom {
    background: linear-gradient(90deg, #1f3c88, #6c757d, #800020);
}

/* BRAND */
.navbar-custom .navbar-brand {
    font-weight: bold;
}

/* MENU BOLD */
.navbar-custom .nav-link {
    color: #fff !important;
    font-weight: bold;
}

/* MENU HOVER */
.navbar-custom .nav-link:hover {
    color: #ffd700 !important;
}

/* MENU ACTIVE */
.navbar-custom .nav-link.active {
    color: #ffd700 !important;
    font-weight: 700;
}

/* 🔥 FIX SEJAJAR */
.navbar-nav {
    align-items: center;
}

.navbar-nav .nav-item {
    display: flex;
    align-items: center;
}

/* SAMAKAN TINGGI LOGOUT */
.navbar-nav .btn {
    padding: 6px 12px;
    font-size: 14px;
}

/* TABLE */
.table thead {
    background: #1f3c88;
    color: #fff;
}

/* CARD */
.card {
    border-radius: 12px;
}

/* FOOTER */
.footer-gradient {
    background: linear-gradient(135deg, #1f3c88, #6c757d, #800020);
    position: relative;
    overflow: hidden;
    background-size: 300% 300%;
    animation: gradientMove 8s ease infinite;
}

.footer-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #00c6ff, #ff6f00, #ffcc00);
}

.footer-title {
    color: #fff;
    font-weight: bold;
}

.footer-text {
    color: #e0e0e0;
}

footer {
    box-shadow: 0 -5px 20px rgba(0,0,0,0.2);
}

@keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom shadow">
  <div class="container">
    <a class="navbar-brand text-white" href="index.php?menu=dashboard">
        Penulis - <?= htmlspecialchars($_SESSION['username']) ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=dashboard">Dashboard</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=artikel_saya">Artikel Saya</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=tambah_artikel">Tambah Artikel</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="index.php?menu=kategori">Kategori</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=tag">Tag</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=profil_penulis">Profil</a>
            </li>

            <!-- 🔴 LOGOUT FIX -->
            <li class="nav-item ms-2">
                <a href="../logout.php" 
                   class="btn btn-danger"
                   onclick="return confirm('Yakin ingin logout?')">
                   🔓 Logout
                </a>
            </li>

        </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">

    <h3>📂 Daftar Kategori</h3>

    <div class="card mt-3 shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<!-- FOOTER -->
<footer class="mt-5">
    <div class="footer-gradient text-white pt-4 pb-3">
        <div class="container">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="footer-title">Blog System</h5>
                    <p class="footer-text">Platform pengelolaan artikel modern untuk penulis.</p>
                </div>

                <div class="col-md-4 mb-3">
                    <h6 class="footer-title">Kontak</h6>
                    <p class="footer-text">📍 Indonesia</p>
                    <p class="footer-text">📞 +62 812-0000-0000</p>
                    <p class="footer-text">✉ info@blogsystem.com</p>
                </div>

                <div class="col-md-4 mb-3">
                    <h6 class="footer-title">Informasi</h6>
                    <p class="footer-text">Dashboard Penulis</p>
                    <p class="footer-text">Manajemen Artikel</p>
                    <p class="footer-text">Kategori & Tag</p>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.3);">

            <div class="text-center footer-text">
                <small>&copy; <?= date('Y'); ?> Blog System</small>
            </div>

        </div>
    </div>
</footer>

</body>
</html>
