<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

$id = (int) $_GET['id'];
$username = $_SESSION['username'];

/* ambil id_user */
$stmt = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username=?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$id_user = $user['id_user'];

/* hapus berdasarkan id_user */
$stmt = mysqli_prepare($conn, "
    DELETE FROM artikel 
    WHERE id_artikel=? AND id_user=?
");
mysqli_stmt_bind_param($stmt, "ii", $id, $id_user);
mysqli_stmt_execute($stmt);

header("Location: index.php?menu=artikel_saya");
exit;
?>
