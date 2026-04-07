<?php
session_start();
if(isset($_SESSION['login'])){
    echo "Halo ".$_SESSION['username']."! Anda bisa berkomentar.";
} else {
    echo '<a href="login.php?redirect=artikel.php?id=5">Login untuk komentar</a>';
}
?>
<form method="post" action="proses_komentar.php">
    <textarea name="komentar" required></textarea>
    <button type="submit">Kirim</button>
</form>