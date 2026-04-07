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
    die("Akses ditolak!");
}

/* =========================
   PAGINATION
========================= */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

/* =========================
   SEARCH
========================= */
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_sql = !empty($search) ? "WHERE nama LIKE '%$search%' OR komentar LIKE '%$search%'" : "";

/* =========================
   TOTAL DATA
========================= */
$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM komentar $search_sql");
$total_data = mysqli_fetch_assoc($total_query);
$total_komentar = $total_data['total'];
$total_pages = ceil($total_komentar / $limit);

/* =========================
   AMBIL DATA KOMENTAR
========================= */
$result = mysqli_query($conn, "SELECT * FROM komentar $search_sql ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");

$namaAdmin = $_SESSION['username'] ?? "Admin";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Komentar - Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f0f5; font-family: 'Segoe UI', sans-serif; padding-bottom:70px; }
.navbar { background: linear-gradient(90deg, #ff6f00, #002366); }
.navbar .nav-link { color:#fff !important; font-weight:600; }
.btn-logout { background:#bfa300;color:#fff;font-weight:600; }
.btn-logout:hover { background:#a18600; }
.card { border-radius:10px; }
.table-hover tbody tr:hover { background-color: #ffeeba; }
.pagination .page-item.active .page-link { background-color:#ff6f00; border-color:#ff6f00; }
footer { position: fixed; bottom:0;width:100%;background:linear-gradient(90deg,#ff6f00,#002366);color:#fff;padding:10px;text-align:center; }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../dashboard.php">Halo, <?= htmlspecialchars($namaAdmin); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
            aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel">Artikel</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=kategori">Kategori</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php?menu=komentar">Komentar</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=users">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?menu=tag">Tag</a></li>
        <li class="nav-item ms-3"><a class="btn btn-logout" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-3">💬 Daftar Komentar</h2>
    <!-- Tombol Kembali -->
    <div class="mb-3">

    <!-- Tombol Kembali (atas) -->
    <div class="mb-2">
        <a href="index.php?menu=dashboard" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <!-- Search (bawah) -->
    <form method="get" class="d-flex gap-2">
        <input type="hidden" name="menu" value="komentar">
        <input type="text" name="search" class="form-control" 
               placeholder="Cari komentar..." 
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

</div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Isi Komentar</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['id_komentar'] ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['komentar']) ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= $row['status'] ? 'Approved' : 'Pending' ?></td>
                            <td>
                                <?php if(!$row['status']): ?>
                                    <a href="approved_komentar.php?id=<?= $row['id_komentar'] ?>" 
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('Approve komentar ini?')">Approve</a>
                                <?php endif; ?>
                                <a href="hapus_komentar.php?id=<?= $row['id_komentar'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin hapus komentar ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Belum ada komentar</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

    <!-- Pagination -->
    <nav class="mt-3">
        <ul class="pagination justify-content-center flex-wrap">
            <li class="page-item <?= ($page<=1)?'disabled':'' ?>">
                <a class="page-link" href="?menu=komentar&page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Prev</a>
            </li>
            <?php for($i=1;$i<=$total_pages;$i++): ?>
            <li class="page-item <?= ($i==$page)?'active':'' ?>">
                <a class="page-link" href="?menu=komentar&page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= ($page>=$total_pages)?'disabled':'' ?>">
                <a class="page-link" href="?menu=komentar&page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>

<footer>&copy; <?= date('Y') ?> Blog System</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>