<?php
include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username'];

/* AMBIL DATA USER */
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if(!$user){
    die("User tidak ditemukan");
}

/* UPDATE PROFIL */
if(isset($_POST['update'])){
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if(!empty($password)){
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "
            UPDATE users 
            SET nama = ?, email = ?, password = ?
            WHERE username = ?
        ");
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $passwordHash, $username);
    } else {
        $stmt = mysqli_prepare($conn, "
            UPDATE users 
            SET nama = ?, email = ?
            WHERE username = ?
        ");
        mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $username);
    }

    mysqli_stmt_execute($stmt);

    echo "<script>
        alert('Profil berhasil diupdate');
        window.location='index.php?menu=profil_penulis';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Penulis</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* BODY */
body {
    background: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar-custom {
    background: linear-gradient(90deg, #1f3c88, #6c757d, #800020);
}

.navbar-custom .nav-link {
    color: #fff !important;
    font-weight: 600;
}

.navbar-custom .nav-link:hover {
    color: #ffd700 !important;
}

.navbar-custom .navbar-brand {
    font-weight: 700;
}

/* FIX LOGOUT BUTTON */
.navbar-custom .btn-danger {
    background-color: #dc3545 !important;
    color: #fff !important;
    border: none;
    font-weight: 600;
}

.navbar-custom .btn-danger:hover {
    background-color: #b02a37 !important;
}

/* CARD */
.card {
    border-radius: 12px;
}

/* BUTTON */
.btn-primary {
    background: #1f3c88;
    border: none;
}
.btn-primary:hover {
    background: #162d66;
}

/* FOOTER */
.footer-gradient {
    background: linear-gradient(135deg, #1f3c88, #6c757d, #800020);
    background-size: 300% 300%;
    animation: gradientMove 8s ease infinite;
    color: #fff;
}

.footer-title {
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
<nav class="navbar navbar-expand-lg navbar-custom shadow">
  <div class="container">
    <a class="navbar-brand text-white" href="index.php?menu=dashboard">
        Penulis - <?= htmlspecialchars($_SESSION['username']) ?>
    </a>

    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav align-items-center">

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=dashboard">Dashboard</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=artikel_saya">Artikel Saya</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=tambah_artikel">Tambah Artikel</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=kategori">Kategori</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=tag">Tag</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="index.php?menu=profil_penulis">Profil</a>
            </li>

            <!-- LOGOUT MERAH & SEJAJAR -->
            <li class="nav-item ms-2">
                <a href="../logout.php" 
                   class="btn btn-danger btn-sm"
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

    <h3>👤 Profil Saya</h3>

    <div class="card mt-3 shadow-sm">
        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" disabled>
                </div>

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-primary">
                        💾 Simpan
                    </button>

                    <a href="hapus_profil.php" 
                       class="btn btn-danger"
                       onclick="return confirm('Yakin ingin menghapus akun? Semua data akan hilang!')">
                       🗑️ Hapus Akun
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- FOOTER -->
<footer class="mt-5">
    <div class="footer-gradient pt-4 pb-3">
        <div class="container text-center">
            <small>&copy; <?= date('Y'); ?> Blog System | Profil Penulis</small>
        </div>
    </div>
</footer>

</body>
</html>
