<?php
if(!isset($_SESSION['login'])){
    header("Location: ../../login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.navbar {
    background: linear-gradient(90deg, #6a11cb, #2575fc);
}
.navbar .nav-link, .navbar-brand {
    color: #fff !important;
    font-weight: 500;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark shadow">
  <div class="container">
    <a class="navbar-brand" href="index.php?menu=dashboard">
        Penulis - <?= htmlspecialchars($username) ?>
    </a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=dashboard">Dashboard</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=artikel_saya">Artikel Saya</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=tambah_artikel">Tambah Artikel</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?menu=profil_penulis">Profil</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-warning" href="../../logout.php">Logout</a>
            </li>

        </ul>
    </div>
  </div>
</nav>