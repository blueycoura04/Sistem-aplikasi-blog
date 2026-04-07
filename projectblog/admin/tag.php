<?php
include "../koneksi.php";

/* =========================
   CEK LOGIN & ROLE
========================= */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

/* =========================
   TAMBAH TAG
========================= */
if(isset($_POST['simpan'])){
    $nama_tag = mysqli_real_escape_string($conn, $_POST['nama_tag']);

    if(!empty($nama_tag)){
        mysqli_query($conn, "INSERT INTO tag (nama_tag) VALUES ('$nama_tag')");
    }

    header("Location: index.php?menu=tag");
    exit;
}

/* =========================
   HAPUS TAG
========================= */
if(isset($_GET['hapus'])){
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tag WHERE id_tag=$id");

    header("Location: index.php?menu=tag");
    exit;
}

/* =========================
   AMBIL DATA
========================= */
$result = mysqli_query($conn, "SELECT * FROM tag ORDER BY id_tag DESC");

$namaAdmin = $_SESSION['username'] ?? "Admin";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Tag</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(160deg, #f0f0f5, #d0e0ff);
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
    padding-bottom: 70px;
}

/* NAVBAR (SAMA DENGAN DASHBOARD) */
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

/* TABLE HEADER */
.table thead {
    background: linear-gradient(90deg, #ff6f00, #002366);
    color: #fff;
}

/* FOOTER (SAMA DENGAN DASHBOARD) */
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
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php?menu=dashboard">Halo Kata</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">

        <li class="nav-item">
            <a class="nav-link" href="index.php?menu=dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?menu=artikel">Artikel</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?menu=kategori">Kategori</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?menu=komentar">Komentar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?menu=users">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="index.php?menu=tag">Tag</a>
        </li>

        <li class="nav-item ms-3">
            <a class="btn btn-logout" href="../logout.php">Logout</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

    <h3>Manajemen Tag</h3>

    <!-- FORM TAMBAH -->
    <form method="post" class="mb-3">
        <div class="input-group">
            <input type="text" name="nama_tag" class="form-control" placeholder="Nama tag..." required>
            <button type="submit" name="simpan" class="btn btn-primary">Tambah</button>
        </div>
    </form>

    <!-- TABEL -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Tag</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id_tag'] ?></td>
                <td><?= htmlspecialchars($row['nama_tag']) ?></td>
                <td>
                    <a href="index.php?menu=tag&hapus=<?= $row['id_tag'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus tag ini?')">
                       Hapus
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">Belum ada tag</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

<!-- FOOTER -->
<footer>
    &copy; <?= date('Y'); ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>