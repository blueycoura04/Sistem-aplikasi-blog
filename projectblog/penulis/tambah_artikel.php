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

/* ================= SIMPAN ================= */
if(isset($_POST['simpan'])){

    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $kategori = $_POST['kategori'];
    $status = $_POST['status'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if($gambar != ""){
        $folder = "../admin/gambar/";
        $namaFile = time() . "_" . $gambar;
        move_uploaded_file($tmp, $folder . $namaFile);
    } else {
        $namaFile = "";
    }

    $stmt = mysqli_prepare($conn, "
        INSERT INTO artikel (judul, isi, id_user, id_kategori, gambar, tanggal, status)
        VALUES (?, ?, ?, ?, ?, NOW(), ?)
    ");
    mysqli_stmt_bind_param($stmt, "ssiiss", $judul, $isi, $id_user, $kategori, $namaFile, $status);
    mysqli_stmt_execute($stmt);

    $id_artikel = mysqli_insert_id($conn);

    if(isset($_POST['tag'])){
        foreach($_POST['tag'] as $id_tag){
            mysqli_query($conn, "
                INSERT INTO artikel_tag (id_artikel, id_tag)
                VALUES ('$id_artikel', '$id_tag')
            ");
        }
    }

    header("Location: index.php?menu=artikel_saya");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Artikel</title>

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

/* CARD FORM */
.card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* FORM */
.form-control, .form-select {
    border-radius: 10px;
}
textarea {
    resize: none;
}

/* BUTTON */
.btn-primary {
    background: #1f3c88;
    border: none;
}
.btn-primary:hover {
    background: #162c66;
}

.btn-secondary {
    background: #6c757d;
    border: none;
}

/* TAG CHECKBOX */
.tag-box label {
    display: inline-block;
    margin-right: 12px;
    margin-bottom: 6px;
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
            <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel_saya">Artikel Saya</a></li>
            <li class="nav-item"><a class="nav-link active" href="index.php?menu=tambah_artikel">Tambah Artikel</a></li>
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
        <h4 class="fw-bold mb-3">Tambah Artikel</h4>

        <?php
        $kategori = mysqli_query($conn, "SELECT * FROM kategori");
        $tag = mysqli_query($conn, "SELECT * FROM tag");
        ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="fw-semibold">Judul</label>
                <input type="text" name="judul" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Isi</label>
                <textarea name="isi" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <?php while($k = mysqli_fetch_assoc($kategori)): ?>
                        <option value="<?= $k['id_kategori'] ?>">
                            <?= $k['nama_kategori'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="publish">Publish</option>
                </select>
            </div>

            <div class="mb-3 tag-box">
                <label class="fw-semibold d-block mb-1">Tag</label>
                <?php while($t = mysqli_fetch_assoc($tag)): ?>
                    <label>
                        <input type="checkbox" name="tag[]" value="<?= $t['id_tag'] ?>">
                        <?= $t['nama_tag'] ?>
                    </label>
                <?php endwhile; ?>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Gambar</label>
                <input type="file" name="gambar" class="form-control">
            </div>

            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php?menu=artikel_saya" class="btn btn-secondary">Kembali</a>

        </form>
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
