<?php
include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

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
.navbar {
    background: linear-gradient(90deg, #1f3c88, #6c757d, #800020);
}
.navbar-brand, .nav-link {
    color: #fff !important;
    font-weight: 600;
}
.nav-link:hover {
    opacity: 0.8;
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
.btn-primary {
    background-color: #1f3c88;
    border: none;
}
.btn-primary:hover {
    background-color: #162e66;
}

.btn-success {
    background-color: #800020;
    border: none;
}
.btn-success:hover {
    background-color: #5a0016;
}

.btn-dark {
    background-color: #6c757d;
    border: none;
}
.btn-dark:hover {
    background-color: #565e64;
}

/* LIST */
.list-group-item {
    border: none;
    border-bottom: 1px solid #eee;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand" href="index.php?menu=dashboard">
        Penulis - <?= htmlspecialchars($username) ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav align-items-center">

            <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel_saya">Artikel Saya</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tambah_artikel">Tambah Artikel</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=kategori">Kategori</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tag">Tag</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=profil_penulis">Profil</a></li>
            <li class="nav-item ms-2">
                <a class="btn btn-sm btn-warning" href="../logout.php">Logout</a>
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
<footer class="mt-5">
    <div class="footer-gradient text-white pt-4 pb-3">
        <div class="container">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold footer-title">Blog System</h5>
                    <p class="small footer-text">
                        Platform pengelolaan artikel modern untuk penulis.
                    </p>
                </div>

                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold footer-title">Kontak</h6>
                    <p class="small footer-text">📍 Indonesia</p>
                    <p class="small footer-text">📞 +62 812-0000-0000</p>
                    <p class="small footer-text">✉ info@blogsystem.com</p>
                </div>

                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold footer-title">Informasi</h6>
                    <p class="small footer-text">Dashboard Penulis</p>
                    <p class="small footer-text">Manajemen Artikel</p>
                    <p class="small footer-text">Kategori & Tag</p>
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