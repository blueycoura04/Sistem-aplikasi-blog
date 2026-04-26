<?php
session_start();
include "../koneksi.php";

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username'];

/* ================= AMBIL ID USER ================= */
$stmt = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User tidak ditemukan!");
}

$id_user = $user['id_user'];

/* ================= AMBIL ID ARTIKEL ================= */
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

/* ================= AMBIL DATA ARTIKEL ================= */
$stmt = mysqli_prepare($conn, "
    SELECT * FROM artikel 
    WHERE id_artikel = ? AND id_user = ?
");
mysqli_stmt_bind_param($stmt, "ii", $id, $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Artikel tidak ditemukan!");
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {

    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $kategori = (int) $_POST['kategori'];
    $status = $_POST['status'];

    $isi = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $isi);

    /* ================= HANDLE GAMBAR ================= */
    $namaFile = $data['gambar']; // default gambar lama

    if (!empty($_FILES['gambar']['name'])) {
        $gambarBaru = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $folder = "../admin/gambar/";
        $namaFile = time() . "_" . basename($gambarBaru);

        if (move_uploaded_file($tmp, $folder . $namaFile)) {
            // hapus gambar lama
            if (!empty($data['gambar']) && file_exists($folder . $data['gambar'])) {
                unlink($folder . $data['gambar']);
            }
        } else {
            $namaFile = $data['gambar'];
        }
    }

    /* ================= UPDATE ARTIKEL ================= */
    $stmt = mysqli_prepare($conn, "
        UPDATE artikel 
        SET judul=?, isi=?, id_kategori=?, status=?, gambar=?
        WHERE id_artikel=? AND id_user=?
    ");
    mysqli_stmt_bind_param(
        $stmt,
        "ssissii",
        $judul,
        $isi,
        $kategori,
        $status,
        $namaFile,
        $id,
        $id_user
    );
    mysqli_stmt_execute($stmt);

    /* ================= UPDATE TAG ================= */
    mysqli_query($conn, "DELETE FROM artikel_tag WHERE id_artikel = $id");

    if (isset($_POST['tag'])) {
        foreach ($_POST['tag'] as $id_tag) {
            $id_tag = (int) $id_tag;
            mysqli_query($conn, "
                INSERT INTO artikel_tag (id_artikel, id_tag)
                VALUES ('$id', '$id_tag')
            ");
        }
    }

    header("Location: index.php?menu=artikel_saya");
    exit;
}

/* ================= AMBIL KATEGORI ================= */
$kategori = mysqli_query($conn, "SELECT * FROM kategori");

/* ================= AMBIL TAG ================= */
$tag = mysqli_query($conn, "SELECT * FROM tag");

/* ================= TAG TERPILIH ================= */
$tagDipilih = [];
$q = mysqli_query($conn, "SELECT id_tag FROM artikel_tag WHERE id_artikel = $id");

while ($d = mysqli_fetch_assoc($q)) {
    $tagDipilih[] = $d['id_tag'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Artikel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #eef1f5, #d9e2ec);
    font-family: 'Segoe UI', sans-serif;
}

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

.card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.tag-box label {
    display: inline-block;
    margin-right: 12px;
    margin-bottom: 6px;
}

.footer-gradient {
    background: linear-gradient(135deg, #1f3c88, #6c757d, #800020);
    background-size: 300% 300%;
    animation: gradientMove 8s ease infinite;
}

.footer-title {
    color: #fff;
    font-weight: bold;
}

.footer-text {
    color: #e0e0e0;
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

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">
            <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="index.php?menu=artikel_saya">Artikel Saya</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tambah_artikel">Tambah Artikel</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=kategori">Kategori</a></li>
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
    <div class="card p-4">
        <h4 class="fw-bold mb-3">Edit Artikel</h4>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="fw-semibold">Judul</label>
                <input type="text" name="judul"
                       value="<?= htmlspecialchars($data['judul']) ?>"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Isi</label>
                <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars(str_replace(["\\r\\n","\\n","\\r"], "\n", $data['isi'])) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Kategori</label>
                <select name="kategori" class="form-select">
                    <?php while($k = mysqli_fetch_assoc($kategori)): ?>
                        <option value="<?= $k['id_kategori'] ?>"
                            <?= $k['id_kategori'] == $data['id_kategori'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" <?= $data['status']=='draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="publish" <?= $data['status']=='publish' ? 'selected' : '' ?>>Publish</option>
                </select>
            </div>

            <div class="mb-3 tag-box">
                <label class="fw-semibold d-block mb-1">Tag</label>
                <?php while($t = mysqli_fetch_assoc($tag)): ?>
                    <label>
                        <input type="checkbox" name="tag[]" value="<?= $t['id_tag'] ?>"
                            <?= in_array($t['id_tag'], $tagDipilih) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($t['nama_tag']) ?>
                    </label>
                <?php endwhile; ?>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Gambar</label>
                <input type="file" name="gambar" class="form-control">
                <?php if (!empty($data['gambar'])): ?>
                    <div class="mt-2">
                        <img src="../admin/gambar/<?= htmlspecialchars($data['gambar']) ?>" width="140" class="rounded">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update</button>
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
