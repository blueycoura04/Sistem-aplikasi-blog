<?php
session_start();
include "koneksi.php";

if(isset($_POST['register'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if($password !== $confirm){
        echo "<script>alert('Password tidak sama');</script>";
    } else {

        // cek username
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        
        if(mysqli_num_rows($cek) > 0){
            echo "<script>alert('Username sudah dipakai');</script>";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query($conn, "
                INSERT INTO users (username, password, role)
                VALUES ('$username', '$hash', 'user')
            ");

            echo "<script>
                alert('Registrasi berhasil, silakan login');
                window.location='login.php';
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5" style="max-width:400px;">
    <h3>Register User</h3>

    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm" class="form-control" required>
        </div>

        <button name="register" class="btn btn-primary w-100">Daftar</button>
    </form>

    <p class="mt-3 text-center">
        Sudah punya akun? <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>