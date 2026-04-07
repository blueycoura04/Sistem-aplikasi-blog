<?php
session_start();
include "koneksi.php";

// Ambil id artikel
$id_artikel = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data artikel
$artikel_query = mysqli_query($conn, "SELECT * FROM artikel WHERE id_artikel='$id_artikel'");
$artikel = mysqli_fetch_assoc($artikel_query);

// Ambil komentar yang sudah di-approve
$komentar_query = mysqli_query($conn, "
    SELECT * FROM komentar 
    WHERE id_artikel='$id_artikel' AND status=1
    ORDER BY tanggal DESC
");

// Proses komentar
if(isset($_POST['submit_komentar'])){
    // Cek login
    if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
        header("Location: login.php?redirect=detail.php?id=$id_artikel");
        exit;
    }

    $nama = $_SESSION['username'];
    $isi = mysqli_real_escape_string($conn, $_POST['komentar']);
    $tanggal = date('Y-m-d H:i:s');

    mysqli_query($conn, "
        INSERT INTO komentar (id_artikel, nama, komentar, tanggal, status) 
        VALUES ('$id_artikel','$nama','$isi','$tanggal',0)
    ");

    echo "<script>
        alert('Komentar berhasil dikirim, menunggu approval admin');
        window.location='detail.php?id=$id_artikel';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($artikel['judul']) ?> - Halo Kata</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f5f5f5; font-family: 'Segoe UI', sans-serif; }
.container { max-width: 800px; margin-top: 40px; }
textarea { resize: none; }
.comment-box { border:1px solid #ddd; border-radius:8px; padding:10px; background:#fff; }
.comment-meta { font-size:0.85rem; color:#555; }
</style>
</head>
<body>
<div class="container">

    <a href="index.php" class="btn btn-secondary mb-3">← Kembali</a>

    <!-- ARTIKEL -->
    <h2><?= htmlspecialchars($artikel['judul']) ?></h2>
    <small class="text-muted">
        <?= date('d M Y', strtotime($artikel['tanggal'])) ?>
    </small>
    <p><?= nl2br(htmlspecialchars($artikel['isi'])) ?></p>
    <hr>

    <?php if(isset($_SESSION['login']) && $_SESSION['login']===true): ?>
<form method="POST">
    <textarea name="komentar" required></textarea>
    <button name="submit_komentar">Kirim</button>
</form>
<?php else: ?>
<div>
    Anda harus <a href="login.php?redirect=detail.php?id=<?= $id_artikel ?>">login</a> untuk mengirim komentar
</div>
<?php endif; ?>

    <!-- LIST KOMENTAR -->
    <?php if(mysqli_num_rows($komentar_query) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($komentar_query)): ?>
            <div class="comment-box mb-3">
                <strong><?= htmlspecialchars($row['nama']) ?></strong> 
                <span class="comment-meta"><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></span>
                <p><?= nl2br(htmlspecialchars($row['komentar'])) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada komentar.</p>
    <?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>