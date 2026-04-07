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
    $nama = $_POST['nama'];
    $email = $_POST['email'];
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
body {
    background: #f0f0f5;
    padding-bottom: 80px;
}
.navbar {
    background: linear-gradient(90deg, #ff6f00, #002366);
}
.navbar .nav-link {
    color: #fff !important;
}
footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background: linear-gradient(90deg, #ff6f00, #002366);
    color: white;
    text-align: center;
    padding: 10px;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Dashboard Penulis</a>

    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php?menu=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?menu=artikel_saya">Artikel</a></li>
            <li class="nav-item"><a class="nav-link active" href="index.php?menu=profil_penulis">Profil</a></li>
            <li class="nav-item">
                <a class="nav-link btn btn-warning text-dark ms-2" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">
    <h3>👤 Profil Saya</h3>

    <div class="card shadow-sm mt-3">
        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" class="form-control" value="<?= $user['username']; ?>" disabled>
                </div>

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= $user['nama']; ?>" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $user['email']; ?>" required>
                </div>

                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                </div>

                <!-- BUTTON SEJAJAR -->
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-primary">
                        Simpan
                    </button>

                    <a href="hapus_profil.php" 
                       class="btn btn-danger"
                       onclick="return confirm('Yakin ingin menghapus akun? Semua data akan hilang!')">
                       Hapus Akun
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    &copy; <?= date('Y') ?> Blog System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>