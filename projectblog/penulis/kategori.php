<?php

include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* QUERY + HITUNG ARTIKEL */
$query = mysqli_query($conn, "
    SELECT 
        k.id_kategori,
        k.nama_kategori,
        COUNT(a.id_artikel) AS total
    FROM kategori k
    LEFT JOIN artikel a 
        ON k.id_kategori = a.id_kategori
        AND a.status = 'publish'
    GROUP BY k.id_kategori
    ORDER BY k.nama_kategori ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kategori</title>

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
.navbar-custom .nav-link {
    color: #fff !important;
    font-weight: 600;
}
.navbar-custom .nav-link:hover {
    color: #ffd700 !important;
}
.navbar-custom .nav-link.active {
    color: #ffd700 !important;
}

/* CARD GRID */
.kategori-card {
    display: block;
    background: linear-gradient(135deg, #1f3c88, #6c757d);
    color: #fff;
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    transition: 0.3s;
    text-decoration: none;
}

.kategori-card:hover {
    transform: translateY(-6px);
    background: linear-gradient(135deg, #800020, #1f3c88);
}

/* TEXT */
.kategori-name {
    font-size: 18px;
    font-weight: bold;
}

.kategori-count {
    font-size: 13px;
    opacity: 0.9;
    margin-top: 5px;
}

/* FOOTER */
.footer-gradient {
    background: linear-gradient(135deg, #1f3c88, #6c757d, #800020);
    background-size: 300% 300%;
    animation: gradientMove 8s ease infinite;
}

.footer-title { color: #fff; font-weight: bold; }
.footer-text { color: #e0e0e0; }

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

    <a class="navbar-brand text-white fw-bold" href="index.php?menu=dashboard">
        Penulis - <?= htmlspecialchars($_SESSION['username']) ?>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">

            <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel_saya">Artikel Saya</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tambah_artikel">Tambah Artikel</a></li>
            <li class="nav-item"><a class="nav-link active" href="index.php?menu=kategori">Kategori</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tag">Tag</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=profil_penulis">Profil</a></li>

            <li class="nav-item ms-2">
                <a href="../logout.php" class="btn btn-danger btn-sm"
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

    <h3 class="fw-bold">📂 Daftar Kategori</h3>
    <p class="text-muted">Kategori + jumlah artikel (publish)</p>

    <div class="row mt-3">

        <?php if(mysqli_num_rows($query) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($query)): ?>

            <div class="col-md-3 col-sm-6 mb-3">
                <a href="../index.php?kategori=<?= urlencode($row['id_kategori']) ?>" class="kategori-card">

                    <div class="kategori-name">
                        <?= htmlspecialchars($row['nama_kategori']) ?>
                    </div>

                    <div class="kategori-count">
                        <?= $row['total'] ?> artikel
                    </div>

                </a>
            </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">Belum ada kategori</p>
        <?php endif; ?>

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
