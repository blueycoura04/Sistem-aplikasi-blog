<?php
session_start();
include "koneksi.php";

// Default redirect jika user biasa
$redirect = $_GET['redirect'] ?? 'index.php';

// Tangkap form login
if(isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $captcha_input = trim($_POST['captcha']);

    // 1️⃣ Cek captcha (case-insensitive)
    if(!isset($_SESSION['captcha']) || strtolower($captcha_input) !== strtolower($_SESSION['captcha'])){
        echo "<script>
        alert('Captcha salah!');
        window.location='login.php?redirect=$redirect';
        </script>";
        exit;
    }

    // 2️⃣ Ambil user berdasarkan username
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);

        // 3️⃣ Cek password hash
        if(password_verify($password, $user['password'])){
            // 4️⃣ Set session
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // 5️⃣ Redirect sesuai role
            if($user['role'] == 'admin'){
                header("Location: admin/dashboard.php");
                exit;
            } elseif($user['role'] == 'penulis'){
                header("Location: penulis/dashboard.php");
                exit;
            } else {
                // user biasa → redirect ke halaman asal
                header("Location: $redirect");
                exit;
            }

        } else {
            echo "<script>
            alert('Password salah!');
            window.location='login.php?redirect=$redirect';
            </script>";
            exit;
        }

    } else {
        echo "<script>
        alert('Username tidak ditemukan!');
        window.location='login.php?redirect=$redirect';
        </script>";
        exit;
    }
}
?>