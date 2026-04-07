<?php
session_start();
include "../koneksi.php";

/* Cek login & role admin */
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin'){
    die("Akses ditolak!");
}

/* Ambil ID komentar dari URL */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id > 0){
    // Update status komentar menjadi approved (1)
    mysqli_query($conn, "UPDATE komentar SET status=1 WHERE id_komentar='$id'");
}

// Kembali ke halaman komentar
header("Location: index.php?menu=komentar");
exit;