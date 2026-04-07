<?php
session_start();
include "../koneksi.php";

/* =========================
   CEK LOGIN & ROLE ADMIN
========================= */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

/* =========================
   CEK ID USER
========================= */
if(!isset($_GET['id'])){
    die("ID user tidak ditemukan!");
}

$id_user = (int)$_GET['id'];

/* =========================
   HAPUS USER
========================= */
$query = "DELETE FROM users WHERE id_user = $id_user";
if(mysqli_query($conn, $query)){
    // berhasil
    header("Location: index.php?menu=users&pesan=berhasil");
    exit;
} else {
    // gagal
    die("Gagal menghapus user: " . mysqli_error($conn));
}
?>