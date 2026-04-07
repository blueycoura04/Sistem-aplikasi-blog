<?php
session_start();
include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* CEK ROLE */
if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

/* LOAD ROUTER */
include "menu.php";
?>