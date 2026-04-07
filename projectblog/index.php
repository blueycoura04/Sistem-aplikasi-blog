<?php
include "koneksi.php";

// Search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$query = "SELECT * FROM artikel";
if($search != ''){
    $query .= " WHERE judul LIKE '%$search%' OR isi LIKE '%$search%'";
}
$query .= " ORDER BY id_artikel DESC";
$artikel = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Halo Kata - Blog</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f5f7fa; font-family: 'Segoe UI', sans-serif; }
.navbar { background: linear-gradient(90deg, #ff6f00, #002366); }
.navbar .nav-link { color: #fff !important; font-weight: 500; }
.card { border-radius: 12px; transition: 0.3s; }
.card:hover { transform: translateY(-5px); }
.title { font-weight: bold; color: #002366; }
.btn-read { background-color: #ff6f00; color: #fff; }
.btn-read:hover { background-color: #e65c00; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Halo Kata</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-3">Artikel Terbaru</h2>
    <!-- SEARCH -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Cari</button>
        </div>
    </form>

    <div class="row">
        <?php if(mysqli_num_rows($artikel) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($artikel)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="title"><?= htmlspecialchars($row['judul']) ?></h5>
                            <small class="text-muted mb-2"><?= date('d M Y', strtotime($row['tanggal'])) ?></small>
                            <p class="card-text"><?= htmlspecialchars(substr($row['isi'],0,100)) ?>...</p>
                            <a href="detail.php?id=<?= $row['id_artikel'] ?>" class="btn btn-read mt-auto">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">Belum ada artikel.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>