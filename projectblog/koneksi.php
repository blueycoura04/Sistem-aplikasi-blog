<?php
// ===============================
// koneksi.php - Koneksi ke database MySQL
// ===============================

// 1. Konfigurasi database
$host = "localhost";     // biasanya localhost untuk XAMPP
$user = "root";          // user default MySQL XAMPP
$pass = "";              // kosong jika belum diubah
$db   = "projectblog";   // nama database
$port = 3307;            // ganti sesuai port MySQL (default 3306)

// 2. Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// 3. Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error() . ". 
    Pastikan MySQL sedang berjalan, username/password benar, dan port MySQL sesuai.");
}

// Jika berhasil
// echo "Koneksi berhasil!";
?>