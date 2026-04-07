<?php
session_start();
include "../../koneksi.php";

// cek login admin
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

// cek koneksi
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

// cek ID kategori
if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

// hapus kategori
$hapus = mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori = $id");

if($hapus){
    header("Location: index.php?menu=kategori"); // kembali ke daftar kategori
    exit;
} else {
    die("Gagal menghapus kategori: " . mysqli_error($conn));
}