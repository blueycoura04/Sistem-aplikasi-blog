<?php
session_start();
include "../../koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id = $_GET['id'] ?? 0;
$id = (int)$id;

if($id > 0){
    $query = "DELETE FROM artikel WHERE id_artikel = $id";
    if(mysqli_query($conn, $query)){
        header("Location: index.php?menu=artikel");
        exit;
    } else {
        die("Gagal menghapus artikel: " . mysqli_error($conn));
    }
} else {
    header("Location: index.php?menu=artikel");
    exit;
}
?>