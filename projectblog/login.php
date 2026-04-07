<?php
session_start();
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

    <!-- Form Login -->
    <form method="POST" action="proses_login.php">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control mb-3" placeholder="Masukkan username" required>

        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control mb-3" placeholder="Masukkan password" required>

        <!-- CAPTCHA -->
        <div class="captcha-box text-center">
            <img src="captcha.php" alt="Captcha" onclick="this.src='captcha.php?'+Math.random()" title="Klik untuk refresh">
        </div>
        <input type="text" name="captcha" class="form-control mb-3" placeholder="Masukkan captcha" required>

        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
