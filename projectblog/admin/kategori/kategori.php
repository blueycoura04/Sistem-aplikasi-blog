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
   CEK KONEKSI
========================= */
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

/* =========================
   PAGINATION
========================= */
$limit = 5;
$page = isset($_GET['page_kategori']) ? (int)$_GET['page_kategori'] : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

/* =========================
   SEARCH
========================= */
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = "";

if(!empty($search)){
    $search_esc = mysqli_real_escape_string($conn, $search);
    $search_sql = "WHERE nama_kategori LIKE '%$search_esc%'";
}

/* =========================
   TOTAL DATA
========================= */
$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kategori $search_sql");
$total_data = mysqli_fetch_assoc($total_query);
$total_kategori = $total_data['total'];
$total_pages = ceil($total_kategori / $limit);

/* =========================
   AMBIL DATA KATEGORI
========================= */
$result = mysqli_query($conn, "
    SELECT * FROM kategori 
    $search_sql 
    ORDER BY id_kategori ASC 
    LIMIT $limit OFFSET $offset
");

$namaAdmin = isset($_SESSION['username']) ? $_SESSION['username'] : "Admin";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Kategori - Dashboard</title>
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

.card {
    border-radius: 10px;
}

.table-hover tbody tr:hover {
    background-color: #ffeeba;
}

.btn-custom {
    background: linear-gradient(90deg, #ff6f00, #ff9900);
    color: #fff;
}
.btn-custom:hover {
    background: linear-gradient(90deg, #ff9900, #ffcc66);
}

.pagination .page-item.active .page-link {
    background-color: #ff6f00;
    border-color: #ff6f00;
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

<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../dashboard.php">
        Halo, <?= htmlspecialchars($namaAdmin); ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel">Artikel</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php?menu=kategori">Kategori</a></li>
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
    <h2 class="mb-4">📂 Daftar Kategori</h2>

    <div class="d-flex justify-content-between flex-wrap mb-3">
        <form method="get" class="d-flex mb-2 gap-2">
    <input type="hidden" name="menu" value="kategori">
    
    <input type="text" name="search" class="form-control" 
           placeholder="Cari kategori..." 
           value="<?= htmlspecialchars($search) ?>">
           
    <button class="btn btn-primary">Search</button>
</form>
        <div class="mb-2">
            <a href="index.php?menu=tambah_kategori" class="btn btn-success btn-custom">Tambah Kategori</a>
            <a href="index.php?menu=dashboard" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['id_kategori'] ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td>
                                <a href="index.php?menu=edit_kategori&id=<?= $row['id_kategori'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="index.php?menu=hapus_kategori&id=<?= $row['id_kategori'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada kategori</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <nav class="mt-3">
        <ul class="pagination justify-content-center flex-wrap">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page_kategori=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Prev</a>
            </li>

            <?php for($i=1; $i<=$total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?page_kategori=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page_kategori=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>

<footer>
    &copy; <?= date('Y'); ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>