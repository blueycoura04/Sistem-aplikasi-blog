<?php
include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* MENU AKTIF */
$menu = $_GET['menu'] ?? 'dashboard';

$username = $_SESSION['username'];

$stmt = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$id_user = $user['id_user'];

/* ================= STATISTIK ================= */

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM artikel WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
$totalArtikel = $data['total'];

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM artikel WHERE id_user = ? AND status = 'publish'");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
$totalPublish = $data['total'];

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM artikel WHERE id_user = ? AND status = 'draft'");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
$totalDraft = $data['total'];

/* ================= ARTIKEL TERBARU ================= */
$stmt = mysqli_prepare($conn, "
    SELECT judul, tanggal 
    FROM artikel 
    WHERE id_user = ? 
    ORDER BY tanggal DESC 
    LIMIT 5
");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$artikelTerbaru = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Penulis</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #eef1f5, #d9e2ec);
    font-family: 'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar-custom {
    background: linear-gradient(90deg, #1f3c88, #6c757d, #800020);
}

/* MENU */
.navbar-custom .nav-link {
    color: #fff !important;
    font-weight: bold;
    transition: 0.3s;
}

/* HOVER */
.navbar-custom .nav-link:hover {
    color: #ffd700 !important;
}

/* ACTIVE */
.navbar-custom .nav-link.active {
    color: #ffd700 !important;
    font-weight: bold;
    border-bottom: none !important;
}

/* CARD */
.card {
    border: none;
    border-radius: 14px;
    transition: 0.3s;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}
.card:hover {
    transform: translateY(-4px);
}

/* STAT CARD */
.stat-card {
    background: linear-gradient(135deg, #1f3c88, #6c757d);
    color: #fff;
}

/* BUTTON */
.btn-primary { background-color: #1f3c88; border: none; }
.btn-success { background-color: #800020; border: none; }
.btn-dark { background-color: #6c757d; border: none; }

/* LIST */
.list-group-item {
    border: none;
    border-bottom: 1px solid #eee;
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
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow">
  <div class="container">
    <a class="navbar-brand" href="index.php?menu=dashboard">
        Penulis - <?= htmlspecialchars($username) ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav align-items-center">

            <li class="nav-item">
                <a class="nav-link <?= ($menu == 'dashboard') ? 'active' : '' ?>" href="index.php?menu=dashboard">Dashboard</a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($menu == 'artikel_saya') ? 'active' : '' ?>" href="index.php?menu=artikel_saya">Artikel Saya</a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($menu == 'tambah_artikel') ? 'active' : '' ?>" href="index.php?menu=tambah_artikel">Tambah Artikel</a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($menu == 'kategori') ? 'active' : '' ?>" href="index.php?menu=kategori">Kategori</a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($menu == 'tag') ? 'active' : '' ?>" href="index.php?menu=tag">Tag</a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($menu == 'profil_penulis') ? 'active' : '' ?>" href="index.php?menu=profil_penulis">Profil</a>
            </li>

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

    <h3 class="fw-bold text-dark">Dashboard Penulis</h3>
    <p class="text-muted">Selamat datang, <b><?= htmlspecialchars($username) ?></b></p>

    <!-- STATISTIK -->
    <div class="row mt-4 g-4">

        <div class="col-md-4">
            <div class="card stat-card p-4 text-center">
                <h6>Total Artikel</h6>
                <h2 class="fw-bold"><?= $totalArtikel; ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card p-4 text-center">
                <h6>Published</h6>
                <h2 class="fw-bold"><?= $totalPublish; ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card p-4 text-center">
                <h6>Draft</h6>
                <h2 class="fw-bold"><?= $totalDraft; ?></h2>
            </div>
        </div>

    </div>

    <!-- MENU CEPAT -->
    <div class="row mt-4 g-4">

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h6>Artikel Saya</h6>
                <a href="index.php?menu=artikel_saya" class="btn btn-primary btn-sm mt-2">Lihat</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h6>Tambah Artikel</h6>
                <a href="index.php?menu=tambah_artikel" class="btn btn-success btn-sm mt-2">Tambah</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h6>Profil</h6>
                <a href="index.php?menu=profil_penulis" class="btn btn-dark btn-sm mt-2">Lihat</a>
            </div>
        </div>

    </div>

    <!-- ARTIKEL TERBARU -->
    <div class="card mt-4 p-4">
        <h5 class="fw-semibold">Artikel Terbaru</h5>

        <ul class="list-group mt-3">
            <?php while($row = mysqli_fetch_assoc($artikelTerbaru)) { ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= htmlspecialchars($row['judul']); ?></span>
                    <small class="text-muted"><?= $row['tanggal']; ?></small>
                </li>
            <?php } ?>
        </ul>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
