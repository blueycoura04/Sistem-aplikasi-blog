<?php
include "../koneksi.php";

/* =========================
   CEK LOGIN
========================= */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* =========================
   CEK ROLE ADMIN
========================= */
if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

/* CEK KONEKSI */
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

$namaAdmin = $_SESSION['username'] ?? "Admin";

/* =========================
   AMBIL KATEGORI & TAG
========================= */
$kategori_query = mysqli_query($conn,"SELECT * FROM kategori ORDER BY nama_kategori ASC");
$tag_query = mysqli_query($conn,"SELECT * FROM tag ORDER BY nama_tag ASC");

/* =========================
   PROSES SIMPAN
========================= */
if(isset($_POST['submit'])){
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori']);
    $tanggal = date('Y-m-d H:i:s');

    $gambar = null;
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0){
        $gambar = time() . '_' . preg_replace('/\s+/', '_', $_FILES['gambar']['name']);
        $upload = move_uploaded_file($_FILES['gambar']['tmp_name'], 'gambar/' . $gambar);

        if(!$upload){
            die("Upload gagal! Pastikan folder admin/gambar writable");
        }
    }

    $query = "INSERT INTO artikel (judul, isi, tanggal, gambar, id_kategori) 
              VALUES ('$judul', '$isi', '$tanggal', '$gambar', '$id_kategori')";

    if(mysqli_query($conn, $query)){

        // ambil id artikel
        $id_artikel = mysqli_insert_id($conn);

        // simpan tag
        if(isset($_POST['tags'])){
            foreach($_POST['tags'] as $id_tag){
                $id_tag = (int)$id_tag;

                mysqli_query($conn, "
                    INSERT INTO artikel_tag (id_artikel, id_tag)
                    VALUES ($id_artikel, $id_tag)
                ");
            }
        }

        header("Location: index.php?menu=artikel");
        exit;

    } else {
        $error = "Gagal menambahkan artikel: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(160deg, #f0f0f5, #d0e0ff);
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
    padding-bottom: 70px;
}

.navbar {
    background: linear-gradient(90deg, #ff6f00, #002366);
}

.navbar .nav-link {
    color: #fff !important;
    font-weight: 600;
}

.btn-logout {
    background-color: #bfa300;
    color: #fff;
    font-weight: 600;
}

.btn-logout:hover {
    background-color: #a18600;
}

footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background: linear-gradient(90deg, #ff6f00, #002366);
    color: #fff;
    padding: 10px;
    text-align: center;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">
        Halo, <?= htmlspecialchars($namaAdmin); ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php?menu=artikel">Artikel</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=kategori">Kategori</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=komentar">Komentar</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=users">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=tag">Tag</a></li>
        <li class="nav-item ms-3">
            <a class="btn btn-logout" href="../logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- FORM -->
<div class="container mt-4">
    <h1>Tambah Artikel</h1>

    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="id_kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while($k = mysqli_fetch_assoc($kategori_query)): ?>
                    <option value="<?= $k['id_kategori'] ?>">
                        <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- TAG -->
        <div class="mb-3">
    <label>Tag</label><br>

    <?php if(mysqli_num_rows($tag_query) > 0): ?>
        <?php while($tag = mysqli_fetch_assoc($tag_query)): ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag['id_tag'] ?>">
                <label class="form-check-label">
                    <?= htmlspecialchars($tag['nama_tag']) ?>
                </label>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-danger">Belum ada tag. Silakan tambah di menu tag.</div>
    <?php endif; ?>
</div>

        <div class="mb-3">
            <label>Gambar Artikel</label>
            <input type="file" name="gambar" class="form-control">
        </div>

        <div class="mb-3">
            <label>Isi Artikel</label>
            <textarea name="isi" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-success">Simpan</button>
        <a href="index.php?menu=artikel" class="btn btn-secondary">Batal</a>

    </form>
</div>

<!-- FOOTER -->
<footer>
    &copy; <?= date('Y'); ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>