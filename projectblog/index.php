<?php
session_start();
include "koneksi.php";

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$tag = isset($_GET['tag']) ? mysqli_real_escape_string($conn, $_GET['tag']) : '';

$query = "SELECT a.* FROM artikel a";

if($tag != ''){
    $query .= "
        JOIN artikel_tag at ON a.id_artikel = at.id_artikel
        JOIN tag t ON at.id_tag = t.id_tag
        WHERE t.nama_tag = '$tag'
    ";
}

if($search != ''){
    if($tag != ''){
        $query .= " AND (a.judul LIKE '%$search%' OR a.isi LIKE '%$search%')";
    } else {
        $query .= " WHERE a.judul LIKE '%$search%' OR a.isi LIKE '%$search%'";
    }
}

$query .= " ORDER BY a.id_artikel DESC";

$artikel = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Halo Kata</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #F4F6F9;
    font-family: 'Segoe UI', sans-serif;
    color: #333;
}

/* NAVBAR */
.navbar {
    background: #1F2A44;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.navbar-brand {
    font-weight: bold;
    color: #D4AF37 !important;
    font-size: 22px;
}
.nav-link {
    color: #ddd !important;
    margin-left: 15px;
}
.nav-link:hover {
    color: #D4AF37 !important;
}

/* HERO */
.hero {
    background: linear-gradient(135deg, #3A6EA5, #1F2A44);
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
    margin-bottom: 40px;
    color: #fff;
}
.hero h2 {
    color: #D4AF37;
}

/* SEARCH */
.search-box {
    max-width: 500px;
    margin: 20px auto;
}
.search-box input {
    border-radius: 25px 0 0 25px;
}
.search-box button {
    border-radius: 0 25px 25px 0;
}

/* CARD */
.card {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: 0.3s;
}
.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}
.card-img-top {
    height: 180px;
    object-fit: cover;
    transition: 0.3s;
}
.card:hover .card-img-top {
    transform: scale(1.05);
}
.title {
    font-weight: 600;
    font-size: 18px;
    color: #1F2A44;
}

/* BUTTON */
.btn-read {
    border-radius: 25px;
    background: #D4AF37;
    color: #1F2A44;
    font-weight: 600;
    border: none;
}
.btn-read:hover {
    background: #c19b2f;
    color: #fff;
}

.btn-logout {
    border-radius: 20px;
    border: 1px solid #D4AF37;
    color: #D4AF37;
    padding: 4px 12px;
}
.btn-logout:hover {
    background: #D4AF37;
    color: #1F2A44;
}

/* FOOTER */
footer {
    margin-top: 60px;
    background: #1F2A44;
    color: #D4AF37;
    padding: 30px 0;
}
footer a {
    color: #ccc;
    text-decoration: none;
}
footer a:hover {
    color: #D4AF37;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
<div class="container">

<a class="navbar-brand" href="index.php">Halo Kata</a>

<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="menu">
<ul class="navbar-nav ms-auto align-items-center">

<li class="nav-item">
    <a class="nav-link" href="index.php">Beranda</a>
</li>

<li class="nav-item">
    <a class="nav-link" href="artikel.php">Artikel</a>
</li>

<?php if(isset($_SESSION['login'])): ?>
    <li class="nav-item">
        <span class="nav-link">Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
    </li>
    <li class="nav-item ms-2">
        <a class="btn btn-logout" href="logout.php">Logout</a>
    </li>
<?php else: ?>
    <li class="nav-item ms-2">
        <a class="btn btn-read" href="login.php">Login</a>
    </li>
<?php endif; ?>

</ul>
</div>

</div>
</nav>

<div class="container mt-4">

<!-- HERO -->
<div class="hero">
<h2>Halo Kata</h2>
<p>Tempat berbagi cerita, inspirasi, dan pengetahuan</p>

<form method="GET" class="search-box">
<div class="input-group">
<input type="text" name="search" class="form-control" placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>">
<button class="btn btn-read">Cari</button>
</div>
</form>
</div>

<!-- ARTIKEL -->
<div class="row g-4">

<?php if(mysqli_num_rows($artikel) > 0): ?>
<?php while($row = mysqli_fetch_assoc($artikel)): ?>
<div class="col-md-4">

<div class="card h-100">

<?php if(!empty($row['gambar'])): ?>
<img src="admin/gambar/<?= $row['gambar'] ?>" class="card-img-top">
<?php else: ?>
<img src="https://via.placeholder.com/400x200" class="card-img-top">
<?php endif; ?>

<div class="card-body d-flex flex-column">

<h5 class="title">
<?= htmlspecialchars($row['judul']) ?>
</h5>

<small class="text-muted mb-2">
<?= date('d M Y', strtotime($row['tanggal'])) ?>
</small>

<p class="card-text">
<?= htmlspecialchars(substr(strip_tags($row['isi']),0,100)) ?>...
</p>

<a href="detail.php?id=<?= $row['id_artikel'] ?>" 
class="btn btn-read mt-auto">
Baca Selengkapnya
</a>

</div>

</div>

</div>
<?php endwhile; ?>
<?php else: ?>
<p class="text-center">Belum ada artikel.</p>
<?php endif; ?>

</div>

</div>

<!-- FOOTER -->
<footer>
<div class="container">
<div class="row">

<div class="col-md-4 mb-3">
<h5>Halo Kata</h5>
<p>Platform blog dengan tampilan modern dan elegan.</p>
</div>

<div class="col-md-4 mb-3">
<h6>Kontak</h6>
<p>📍 Indonesia</p>
<p>📞 +62 812-0000-0000</p>
<p>✉ info@blogsystem.com</p>
</div>

<div class="col-md-4 mb-3">
<h6>Informasi</h6>
<p><a href="index.php">Beranda</a></p>
<p><a href="artikel.php">Artikel</a></p>
</div>

</div>

<hr>

<div class="text-center">
&copy; <?= date('Y') ?> Halo Kata • Dibuat dengan ❤️
</div>

</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
