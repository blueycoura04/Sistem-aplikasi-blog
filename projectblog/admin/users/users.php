<?php

include "../koneksi.php";

/* CEK LOGIN & ROLE */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

/* PAGINATION */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

/* SEARCH */
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_sql = !empty($search) ? "WHERE username LIKE '%$search%' OR nama LIKE '%$search%'" : "";

/* TOTAL DATA */
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users $search_sql");
$total_data = mysqli_fetch_assoc($total_query);
$total_users = $total_data['total'];
$total_pages = ceil($total_users / $limit);

/* AMBIL DATA */
$result = mysqli_query($conn, "
    SELECT * FROM users 
    $search_sql 
    ORDER BY id_user DESC 
    LIMIT $limit OFFSET $offset
");

$namaAdmin = $_SESSION['username'] ?? "Admin";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f0f0f5; padding-bottom:70px; }
.navbar { background: linear-gradient(90deg, #ff6f00, #002366); }
.navbar .nav-link { color:#fff !important; font-weight:600; }
.btn-logout { background:#bfa300;color:#fff; }
footer {
    position: fixed;
    bottom:0;
    width:100%;
    background: linear-gradient(90deg,#ff6f00,#002366);
    color:#fff;
    text-align:center;
    padding:10px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../dashboard.php">
        Halo, <?= htmlspecialchars($namaAdmin); ?>
    </a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="menu">
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
            <a class="nav-link active" href="index.php?menu=users">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="index.php?menu=tag">Tag</a>
        </li>

        <li class="nav-item ms-3">
            <a class="btn btn-logout" href="../../logout.php">Logout</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">

    <h3>👤 Daftar Users</h3>

    <div class="d-flex justify-content-between flex-wrap mb-3">
        <!-- SEARCH -->
        <form method="get" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" 
                   placeholder="Cari user..." 
                   value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Search</button>
        </form>

        <div>
            <a href="index.php?menu=tambah_user" class="btn btn-success">Tambah</a>
            <a href="index.php?menu=dashboard" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id_user'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= $row['role'] ?></td>
                        <td>
                            <a href="index.php?menu=edit_user&id=<?= $row['id_user'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="index.php?menu=hapus_user&id=<?= $row['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada user</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Prev</a>
            </li>

            <?php for($i=1; $i<=$total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next</a>
            </li>
        </ul>
    </nav>

</div>

<footer>
    &copy; <?= date('Y') ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>