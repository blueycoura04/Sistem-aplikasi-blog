<?php
include "../koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* AMBIL DATA KATEGORI */
$query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kategori</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
}
.navbar {
    background: linear-gradient(90deg, #6a11cb, #2575fc);
}
.navbar .nav-link, .navbar-brand {
    color: #fff !important;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand" href="index.php?menu=dashboard">
        Penulis - <?= htmlspecialchars($_SESSION['username']) ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel_saya">Artikel Saya</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tambah_artikel">Tambah Artikel</a></li>
            <li class="nav-item"><a class="nav-link active" href="index.php?menu=kategori">Kategori</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=tag">Tag</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=profil_penulis">Profil</a></li>
            <li class="nav-item"><a class="nav-link text-warning" href="../logout.php">Logout</a></li>
        </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">
    <h3>📂 Kategori</h3>

    <div class="card mt-3 shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- Tombol kembali -->
    <div class="mt-3">
        <a href="index.php?menu=dashboard" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>

</div>

</body>
</html>