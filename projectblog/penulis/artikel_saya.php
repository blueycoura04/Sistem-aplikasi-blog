<?php
include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username'];

/* AMBIL ID USER */
$stmt = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$id_user = $user['id_user'];

/* AMBIL DATA ARTIKEL */
$stmt = mysqli_prepare($conn, "
    SELECT id_artikel, judul, status, tanggal 
    FROM artikel 
    WHERE id_user = ? 
    ORDER BY tanggal DESC
");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$artikel = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Artikel Saya</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* GLOBAL */
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
    color: #ffd700 !important;
}

/* CARD */
.card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* TABLE */
.table {
    border-radius: 10px;
    overflow: hidden;
}
.table thead {
    background: #1f3c88;
    color: #fff;
}
.table tbody tr:hover {
    background: #f5f7fa;
    transition: 0.2s;
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
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand" href="index.php?menu=dashboard">
        Penulis - <?= htmlspecialchars($username) ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav align-items-center">

            <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="index.php?menu=artikel_saya">Artikel Saya</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tambah_artikel">Tambah Artikel</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=kategori">Kategori</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tag">Tag</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=profil_penulis">Profil</a></li>
            <li class="nav-item ms-2">
                <a href="../logout.php" 
                   class="btn btn-danger btn-sm"
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

    <div class="card p-4">
        <h4 class="fw-bold">Artikel Saya</h4>
        <p class="text-muted">Daftar artikel yang telah kamu buat</p>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($artikel)) { 
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['judul']); ?></td>

                    <td>
                        <?php if($row['status'] == 'publish'){ ?>
                            <span class="badge bg-success">Publish</span>
                        <?php } else { ?>
                            <span class="badge bg-secondary">Draft</span>
                        <?php } ?>
                    </td>

                    <td><?= $row['tanggal']; ?></td>

                    <td>
                        <a href="../detail.php?id=<?= $row['id_artikel']; ?>" class="btn btn-sm btn-success me-1">Lihat</a>
                        <a href="edit_artikel.php?id=<?= $row['id_artikel']; ?>" class="btn btn-sm btn-primary me-1">Edit</a>
                        <a href="hapus_artikel.php?id=<?= $row['id_artikel']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus artikel?')">Hapus</a>
                    </td>
            <?php } ?>

            </tbody>
        </table>

    </div>

</div>

<!-- FOOTER -->
<footer class="mt-5">
    <div class="footer-gradient text-white pt-4 pb-3">
        <div class="container">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <h5 class="footer-title">Blog System</h5>
                    <p class="footer-text">
                        Platform pengelolaan artikel modern untuk penulis.
                    </p>
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
