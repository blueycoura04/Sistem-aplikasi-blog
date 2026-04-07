<?php
include "../koneksi.php";

/* CEK LOGIN & ROLE */
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
if($_SESSION['role'] != 'admin'){
    echo "Akses ditolak!";
    exit;
}

/* CEK KONEKSI */
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

/* AMBIL ID ARTIKEL */
$id = $_GET['id'] ?? 0;
$id = (int)$id;

$artikelQuery = mysqli_query($conn, "SELECT * FROM artikel WHERE id_artikel = $id");
if(!$artikelQuery || mysqli_num_rows($artikelQuery) == 0){
    die("Artikel tidak ditemukan.");
}
$row = mysqli_fetch_assoc($artikelQuery);

/* AMBIL DATA KATEGORI */
$kategoriQuery = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

/* AMBIL DATA TAG */
$tagQuery = mysqli_query($conn, "SELECT * FROM tag ORDER BY nama_tag ASC");

/* AMBIL TAG TERPILIH */
$selected_tags = [];
$tagTerpilih = mysqli_query($conn, "SELECT id_tag FROM artikel_tag WHERE id_artikel=$id");
while($t = mysqli_fetch_assoc($tagTerpilih)){
    $selected_tags[] = $t['id_tag'];
}

/* PROSES UPDATE */
if(isset($_POST['submit'])){
    $judul = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $id_kategori = (int)$_POST['id_kategori'];
    $isi = mysqli_real_escape_string($conn, trim($_POST['isi']));
    $gambar_lama = $row['gambar'];

    // handle gambar baru
    if(!empty($_FILES['gambar']['name'])){
        $namaFile = time() . "_" . basename($_FILES['gambar']['name']);
        $target = "../gambar/" . $namaFile;

        if(move_uploaded_file($_FILES['gambar']['tmp_name'], $target)){
            if(!empty($gambar_lama) && file_exists("../gambar/$gambar_lama")){
                unlink("../gambar/$gambar_lama");
            }
        }
    } else {
        $namaFile = $gambar_lama;
    }

    // update artikel
    $update = mysqli_query($conn, "
        UPDATE artikel 
        SET judul='$judul', id_kategori=$id_kategori, isi='$isi', gambar='$namaFile' 
        WHERE id_artikel=$id
    ");

    if($update){

        // hapus tag lama
        mysqli_query($conn, "DELETE FROM artikel_tag WHERE id_artikel=$id");

        // simpan tag baru
        if(isset($_POST['tags'])){
            foreach($_POST['tags'] as $id_tag){
                $id_tag = (int)$id_tag;

                mysqli_query($conn, "
                    INSERT INTO artikel_tag (id_artikel, id_tag)
                    VALUES ($id, $id_tag)
                ");
            }
        }

        header("Location: index.php?menu=artikel");
        exit;

    } else {
        $error = "Gagal update artikel: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(160deg, #f0f0f5, #d0e0ff);
    min-height: 100vh;
    padding-bottom: 70px;
}
.navbar {
    background: linear-gradient(90deg, #ff6f00, #002366);
}
.navbar .nav-link { color: #fff !important; font-weight:600; }
.btn-logout { background-color: #bfa300; color:#fff; font-weight:600; }
.btn-logout:hover { background-color: #a18600; }
footer {
    position: fixed;
    bottom: 0; width: 100%;
    background: linear-gradient(90deg,#ff6f00,#002366);
    color:#fff; padding:10px; text-align:center;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../dashboard.php">Halo Admin</a>
  </div>
</nav>

<div class="container mt-4">
    <h3>Edit Artikel</h3>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while($k = mysqli_fetch_assoc($kategoriQuery)): ?>
                    <option value="<?= $k['id_kategori'] ?>" <?= $row['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- TAG -->
        <div class="mb-3">
            <label class="form-label">Tag</label><br>
            <?php while($tag = mysqli_fetch_assoc($tagQuery)): ?>
                <div class="form-check form-check-inline">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="tags[]" 
                        value="<?= $tag['id_tag'] ?>"
                        <?= in_array($tag['id_tag'], $selected_tags) ? 'checked' : '' ?>
                    >
                    <label class="form-check-label">
                        <?= htmlspecialchars($tag['nama_tag']) ?>
                    </label>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Isi</label>
            <textarea name="isi" class="form-control" rows="5" required><?= htmlspecialchars($row['isi']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar (opsional)</label>
            <input type="file" name="gambar" class="form-control">
            <?php if(!empty($row['gambar'])): ?>
                <img src="../gambar/<?= htmlspecialchars($row['gambar']) ?>" style="max-width:150px;margin-top:10px;">
            <?php endif; ?>
        </div>

        <button type="submit" name="submit" class="btn btn-success">Update</button>
        <a href="index.php?menu=artikel" class="btn btn-secondary">Batal</a>

    </form>
</div>

<footer>
    &copy; <?= date('Y'); ?> Blog System
</footer>

</body>
</html>