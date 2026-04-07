<?php
session_start();
include "../../koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

if(isset($_POST['id'], $_POST['judul'], $_POST['isi'])){
    $id = (int)$_POST['id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);

    // Ambil gambar lama
    $artikel = mysqli_query($conn, "SELECT gambar FROM artikel WHERE id_artikel=$id");
    $row = mysqli_fetch_assoc($artikel);
    $gambar_lama = $row['gambar'];

    // Proses upload gambar baru jika ada
    $gambar = $gambar_lama;
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0){
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../gambar/' . $gambar);

        // hapus gambar lama jika ada
        if(!empty($gambar_lama) && file_exists('../gambar/' . $gambar_lama)){
            unlink('../gambar/' . $gambar_lama);
        }
    }

    $query = "UPDATE artikel SET judul='$judul', isi='$isi', gambar='$gambar' WHERE id_artikel=$id";

    if(mysqli_query($conn, $query)){
        header("Location: artikel.php"); // redirect ke daftar artikel
        exit;
    } else {
        die("Gagal update artikel: " . mysqli_error($conn));
    }
} else {
    header("Location: artikel.php");
    exit;
}
?>