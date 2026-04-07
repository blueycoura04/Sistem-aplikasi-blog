<?php
session_start();
include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username'];

/* AMBIL ID USER */
$stmt = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if(!$user){
    die("User tidak ditemukan");
}

$id_user = $user['id_user'];

/* HAPUS USER */
$stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);

if(mysqli_stmt_execute($stmt)){
    
    // Hapus session setelah akun dihapus
    session_destroy();

    echo "<script>
        alert('Akun berhasil dihapus');
        window.location='../login.php';
    </script>";
} else {
    echo "Gagal menghapus akun";
}
?>