<?php
include "../../koneksi.php";

$id = $_GET['id'];
$username = $_SESSION['username'];

/* ambil data */
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM artikel 
    WHERE id_artikel='$id' AND penulis='$username'
"));

if(isset($_POST['update'])){
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];

    mysqli_query($conn, "
        UPDATE artikel SET 
        judul='$judul',
        isi='$isi',
        id_kategori='$kategori'
        WHERE id_artikel='$id' AND penulis='$username'
    ");

    header("Location: ../index.php?menu=artikel_saya");
}
?>

<h3>Edit Artikel</h3>

<form method="POST">
    <input type="text" name="judul" value="<?= $data['judul'] ?>" class="form-control mb-2">

    <textarea name="isi" class="form-control mb-2"><?= $data['isi'] ?></textarea>

    <select name="kategori" class="form-control mb-2">
        <?php
        $kat = mysqli_query($conn, "SELECT * FROM kategori");
        while($k = mysqli_fetch_assoc($kat)){
            $selected = ($k['id_kategori'] == $data['id_kategori']) ? "selected" : "";
            echo "<option value='{$k['id_kategori']}' $selected>{$k['nama_kategori']}</option>";
        }
        ?>
    </select>

    <button name="update" class="btn btn-primary">Update</button>
</form>