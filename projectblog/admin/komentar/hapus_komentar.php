<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$id = (int)$id;

if($id > 0){
    mysqli_query($conn, "DELETE FROM komentar WHERE id_komentar = $id");
}

header("Location: index.php?menu=komentar");
exit;
?>