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

/* =========================
   AMBIL DETAIL ARTIKEL
========================= */
$detail_artikel = null;

if(isset($_GET['id'])){
    $id = (int) $_GET['id'];

    $query_detail = mysqli_query($conn, "
        SELECT a.*, k.nama_kategori 
        FROM artikel a
        LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
        WHERE a.id_artikel = $id
    ");

    $detail_artikel = mysqli_fetch_assoc($query_detail);
}

/* =========================
   PAGINATION
========================= */
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

/* =========================
   SEARCH
========================= */
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_sql = "";
if(!empty($search)){
    $search_sql = "WHERE a.judul LIKE '%$search%' OR a.isi LIKE '%$search%'";
}

/* =========================
   TOTAL DATA
========================= */
$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM artikel a $search_sql");
$total_data = mysqli_fetch_assoc($total_query);
$total_artikel = $total_data['total'];
$total_pages = ceil($total_artikel / $limit);

/* =========================
   AMBIL DATA ARTIKEL
========================= */
$artikel = mysqli_query($conn,"
    SELECT a.*, k.nama_kategori 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    $search_sql
    ORDER BY a.tanggal DESC
    LIMIT $limit OFFSET $offset
");

$namaAdmin = isset($_SESSION['username']) ? $_SESSION['username'] : "Admin";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Artikel - Dashboard</title>
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

.table thead {
    background: linear-gradient(90deg, #ff6f00, #002366);
    color: #fff;
}

img.thumbnail {
    width: 80px;
    height: auto;
    object-fit: cover;
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

<div class="container mt-4">

<?php if(isset($_GET['id']) && $detail_artikel): ?>

    <!-- ================= DETAIL ================= -->
    <a href="index.php?menu=artikel" class="btn btn-secondary mb-3">← Kembali</a>

    <h2><?= htmlspecialchars($detail_artikel['judul']) ?></h2>

    <p><strong>Kategori:</strong> <?= htmlspecialchars($detail_artikel['nama_kategori'] ?? '-') ?></p>
    <p><strong>Tanggal:</strong> <?= htmlspecialchars($detail_artikel['tanggal']) ?></p>

    <?php if(!empty($detail_artikel['gambar'])): ?>
        <img src="gambar/<?= $detail_artikel['gambar'] ?>" class="img-fluid mb-3">
    <?php endif; ?>

    <div>
        <?= $detail_artikel['isi'] ?>
    </div>

<?php else: ?>

    <!-- ================= LIST ================= -->

    <h1>Daftar Artikel</h1>

    <div class="mb-3 d-flex gap-2">
        <a href="index.php?menu=tambah_artikel" class="btn btn-warning">Tambah Artikel</a>
        <a href="index.php?menu=dashboard" class="btn btn-secondary">Kembali</a>
    </div>

    <!-- SEARCH -->
    <form method="get" class="mb-3">
        <input type="hidden" name="menu" value="artikel">
        <div class="input-group">
            <input type="text" name="search" class="form-control" 
                   placeholder="Cari artikel..." 
                   value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- TABLE -->
    <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Gambar</th>
            <th>Isi</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php if(mysqli_num_rows($artikel) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($artikel)): ?>
        <tr>
            <td><?= $row['id_artikel'] ?></td>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
            <td>
                <?php if(!empty($row['gambar'])): ?>
                    <img src="gambar/<?= $row['gambar'] ?>" class="thumbnail">
                <?php else: ?>
                    Tidak ada
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars(substr(strip_tags($row['isi']),0,100)) . '...' ?></td>
            <td><?= htmlspecialchars($row['tanggal']); ?></td>
            <td>
                <a href="index.php?menu=artikel&id=<?= $row['id_artikel'] ?>" 
                   class="btn btn-sm btn-success">Lihat</a>

                <a href="index.php?menu=edit_artikel&id=<?= $row['id_artikel'] ?>" 
                   class="btn btn-sm btn-primary">Edit</a>

                <a href="index.php?menu=hapus_artikel&id=<?= $row['id_artikel'] ?>" 
                   class="btn btn-danger btn-sm" 
                   onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" class="text-center">Belum ada artikel</td>
        </tr>
    <?php endif; ?>
    </tbody>
    </table>

    <!-- PAGINATION -->
    <nav>
        <ul class="pagination">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?menu=artikel&page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Prev</a>
            </li>

            <?php for($i=1; $i<=$total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?menu=artikel&page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?menu=artikel&page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next</a>
            </li>
        </ul>
    </nav>

<?php endif; ?>

</div>

<footer>
    &copy; <?= date('Y'); ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
