<?php
include "../../koneksi.php";

$id = $_GET['id'];
$username = $_SESSION['username'];

/* pastikan hanya milik sendiri */
mysqli_query($conn, "
    DELETE FROM artikel 
    WHERE id_artikel='$id' AND penulis='$username'
");

header("Location: ../index.php?menu=artikel_saya");