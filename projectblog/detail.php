<?php
session_start();
include "koneksi.php";

/* ================= CEK ID ================= */
if(!isset($_GET['id'])){
    die("Artikel tidak ditemukan!");
}

$id = (int) $_GET['id'];

/* ================= AMBIL ARTIKEL ================= */
$stmt = mysqli_prepare($conn, "
    SELECT a.*, k.nama_kategori, u.nama 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    LEFT JOIN users u ON a.id_user = u.id_user
    WHERE a.id_artikel = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Artikel tidak ditemukan!");
}

/* ================= SIMPAN KOMENTAR ================= */
if(isset($_POST['kirim_komentar'])){

    if(!isset($_SESSION['login'])){
        $_SESSION['redirect'] = "detail.php?id=".$id;
        header("Location: login.php");
        exit;
    }

    $nama = $_SESSION['username'];
    $isi_komentar = trim($_POST['komentar']);

    if(!empty($isi_komentar)){
        $stmt = mysqli_prepare($conn, "
           INSERT INTO komentar (id_artikel, nama, komentar, tanggal, status)
           VALUES (?, ?, ?, NOW(), 0)
        ");
        mysqli_stmt_bind_param($stmt, "iss", $id, $nama, $isi_komentar);
        mysqli_stmt_execute($stmt);
    }

    header("Location: detail.php?id=$id&status=pending");
    exit;
}

/* ================= AMBIL KOMENTAR ================= */
$komentar = mysqli_query($conn, "
    SELECT * 
    FROM komentar
    WHERE id_artikel = $id 
    AND status = 1
    ORDER BY tanggal DESC
");

/* ================= AMBIL TAG ================= */
$tags = mysqli_query($conn, "
    SELECT t.nama_tag
    FROM tag t
    JOIN artikel_tag at ON t.id_tag = at.id_tag
    WHERE at.id_artikel = $id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($data['judul']) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar {
    background: linear-gradient(90deg, #4A6C8C, #6F8FB3);
}

/* CONTAINER */
.artikel-container {
    max-width: 800px;
    margin: 40px auto;
}

/* BOX */
.artikel-box {
    background: #fff;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.05);
}

/* TITLE */
.artikel-title {
    font-size: 32px;
    font-weight: bold;
}

/* META */
.artikel-meta {
    font-size: 14px;
    color: #777;
    margin-bottom: 20px;
}

/* IMAGE */
.artikel-img {
    width: 100%;
    border-radius: 15px;
    margin-bottom: 25px;
}

/* CONTENT */
.artikel-content p {
    margin-bottom: 18px;
    line-height: 1.8;
    text-align: justify;
}

/* BUTTON */
.btn-back {
    background: #C8A96A;
    color: #fff;
    padding: 12px 25px;
    border-radius: 30px;
    border: none;
}
.btn-back:hover {
    background: #b89555;
}

/* KOMENTAR */
.komentar-box {
    margin-top: 40px;
}
.komentar-item {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 12px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar p-3">
<div class="container text-white">
    <b>Halo Kata</b>

    <div>
    <?php if(isset($_SESSION['login'])): ?>
        Halo, <?= htmlspecialchars($_SESSION['username']) ?>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
    <?php else: ?>
        <a href="login.php" class="btn btn-sm btn-light">Login</a>
    <?php endif; ?>
    </div>
</div>
</nav>

<div class="artikel-container">

<div class="artikel-box">

<h1 class="artikel-title"><?= htmlspecialchars($data['judul']) ?></h1>
<div class="artikel-meta">
✍️ <?= htmlspecialchars($data['nama'] ?? 'Admin') ?> • 
<?= date('d M Y', strtotime($data['tanggal'])) ?> <br>

📂 Kategori: <?= htmlspecialchars($data['nama_kategori'] ?? '-') ?>
</div>

<p><strong>Tag:</strong> 
<?php
if(mysqli_num_rows($tags) > 0){
    while($t = mysqli_fetch_assoc($tags)){
        echo '<a href="index.php?tag=' . urlencode($t['nama_tag']) . '" 
                class="badge bg-primary me-1 text-decoration-none">'
             . htmlspecialchars($t['nama_tag']) .
             '</a>';
    }
}else{
    echo '-';
}
?>
</p>

<?php if(!empty($data['gambar'])): ?>
<img src="admin/gambar/<?= htmlspecialchars($data['gambar']) ?>" class="artikel-img">
<?php endif; ?>

<!-- 🔥 FIX PARAGRAF RAPI -->
<div class="artikel-content">
<?php
$isi = htmlspecialchars($data['isi']);

// FIX database yang simpan "\r\n"
$isi = str_replace(["\\r\\n", "\\n"], "\n", $isi);

// pisah paragraf
$paragraf = preg_split("/\n\s*\n/", $isi);

foreach($paragraf as $p){
    if(trim($p)){
        echo "<p>" . nl2br(trim($p)) . "</p>";
    }
}
?>
</div>

</div>

<!-- KOMENTAR -->
<div class="komentar-box">
<h5>Komentar</h5>

<?php if(isset($_SESSION['login'])): ?>
<form method="POST">
<textarea name="komentar" class="form-control mb-2" placeholder="Tulis komentar..." required></textarea>
<button name="kirim_komentar" class="btn btn-primary">Kirim</button>
</form>
<?php else: ?>
<div class="alert alert-warning">
Silakan <a href="login.php">Login</a> atau 
<a href="register.php">Daftar</a> untuk komentar.
</div>
<?php endif; ?>

<?php if(isset($_GET['status'])): ?>
<div class="alert alert-info mt-2">
Komentar kamu sedang menunggu persetujuan admin.
</div>
<?php endif; ?>

<?php if(mysqli_num_rows($komentar) > 0): ?>
<?php while($k = mysqli_fetch_assoc($komentar)): ?>
<div class="komentar-item">
<b><?= htmlspecialchars($k['nama']) ?></b><br>
<small><?= date('d M Y H:i', strtotime($k['tanggal'])) ?></small>
<p><?= htmlspecialchars($k['komentar']) ?></p>
</div>
<?php endwhile; ?>
<?php else: ?>
<p class="text-muted">Belum ada komentar</p>
<?php endif; ?>

</div>

<!-- BUTTON BAWAH -->
<div class="text-center mt-5">
<a href="index.php" class="btn btn-back">
Kembali ke Beranda
</a>
</div>

</div>

</body>
</html>
