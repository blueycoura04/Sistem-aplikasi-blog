<?php
session_start();
include "../koneksi.php";

/* =========================
   CEK LOGIN
========================= */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* =========================
   CEK ROLE PENULIS
========================= */
if($_SESSION['role'] != 'penulis'){
    echo "Akses ditolak!";
    exit;
}

/* =========================
   LOAD ROUTER MENU
========================= */
include "menu.php";
?>