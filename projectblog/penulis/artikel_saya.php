<?php
include "../koneksi.php";

if(!isset($_SESSION['login'])){
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

$id_user = $user['id_user'];

/* ================= AMBIL ARTIKEL ================= */
$stmt = mysqli_prepare($conn, "
    SELECT a.*, k.nama_kategori 
    FROM artikel a
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    WHERE a.id_user = ?
    ORDER BY a.tanggal DESC
");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Artikel Saya</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
html, body {
    height: 100%;
}

body {
    display: flex;
    flex-direction: column;
    background:#f0f0f5;
}

.main-content {
    flex: 1;
}

.card { border-radius:10px; }
.table-hover tbody tr:hover { background:#ffeeba; }
</style>
</head>

<body>

<?php include "navbar.php"; ?>

<div class="main-content">
    <div class="container mt-4">
        <h3>📄 Artikel Saya</h3>

        <a href="index.php?menu=tambah_artikel" class="btn btn-success mb-3">
            + Tambah Artikel
        </a>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php if(mysqli_num_rows($query) > 0): ?>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++ ?></td>

                                <td><?= htmlspecialchars($row['judul']) ?></td>

                                <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>

                                <td>
                                    <?php if($row['status'] == 'publish'): ?>
                                        <span class="badge bg-success">Publish</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= $row['tanggal'] ?></td>

                                <td>
                                    <a href="../index.php?menu=edit_artikel&id=<?= $row['id_artikel'] ?>" class="btn btn-warning btn-sm">Edit</a>

                                    <a href="../index.php?menu=hapus_artikel&id=<?= $row['id_artikel'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Hapus artikel ini?')">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada artikel</td>
                            </tr>
                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="index.php?menu=dashboard" class="btn btn-secondary">← Kembali</a>
        </div>

    </div>
</div>

<?php include "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>