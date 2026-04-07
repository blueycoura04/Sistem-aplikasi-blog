<?php
session_start();
include "koneksi.php";

// Notif error
$error = '';

// Redirect default (untuk user biasa)
$redirect = $_GET['redirect'] ?? 'index.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $captcha_input = trim($_POST['captcha']);

    // Cek captcha (case-insensitive)
    if(!isset($_SESSION['captcha']) || strtolower($captcha_input) !== strtolower($_SESSION['captcha'])){
        $error = "Captcha salah!";
    } else {
        // Prepared statement untuk keamanan
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);

            // Cek password hash
            if(password_verify($password, $row['password'])){
                $_SESSION['login'] = true;
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role']; // admin / penulis / user

                // Redirect sesuai role
                if($row['role'] == 'admin'){
                    header("Location: admin/dashboard.php");
                    exit;
                } elseif($row['role'] == 'penulis'){
                    header("Location: penulis/dashboard.php");
                    exit;
                } else {
                    // user biasa → redirect ke halaman asal
                    header("Location: $redirect");
                    exit;
                }
            } else {
                $error = "Username atau password salah!";
            }
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - Halo Kata</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #6b5b95, #ff6f91, #4facfe);
    font-family: 'Segoe UI', sans-serif;
}
.card {
    border-radius: 16px;
    padding: 30px;
    width: 100%;
    max-width: 360px;
    background: rgba(255,255,255,0.95);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.captcha-box img {
    display:block;
    margin:auto;
    margin-bottom:8px;
    border:1px solid #ddd;
    border-radius:8px;
    cursor:pointer;
}
</style>
</head>
<body>
<div class="card shadow">
    <h4 class="text-center mb-4">🔒 Login Halo Kata</h4>

    <!-- NOTIF ERROR -->
    <?php if($error): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control mb-3" placeholder="Masukkan username" required>

        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control mb-3" placeholder="Masukkan password" required>

        <!-- CAPTCHA IMAGE -->
        <div class="captcha-box text-center">
            <img src="captcha.php" alt="Captcha" onclick="this.src='captcha.php?'+Math.random()" title="Klik untuk refresh">
        </div>
        <input type="text" name="captcha" class="form-control mb-3" placeholder="Masukkan captcha" required>

        <button class="btn btn-primary w-100">Login</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>