<?php
include "../koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: ../../login.php");
    exit;
}

$username = $_SESSION['username'];

/* AMBIL ID USER */
$stmt = mysqli_prepare($conn, "SELECT id_user FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$id_user = $user['id_user'];

/* ================= SIMPAN ================= */
if(isset($_POST['simpan'])){

    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $kategori = $_POST['kategori'];
    $status = $_POST['status'];

    // upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if($gambar != ""){
        $folder = "../../uploads/";
        $namaFile = time() . "_" . $gambar;
        move_uploaded_file($tmp, $folder . $namaFile);
    } else {
        $namaFile = "";
    }

    /* INSERT ARTIKEL */
    $stmt = mysqli_prepare($conn, "
        INSERT INTO artikel (judul, isi, id_user, id_kategori, gambar, tanggal, status)
        VALUES (?, ?, ?, ?, ?, NOW(), ?)
    ");
    mysqli_stmt_bind_param($stmt, "ssiiss", $judul, $isi, $id_user, $kategori, $namaFile, $status);
    mysqli_stmt_execute($stmt);

    $id_artikel = mysqli_insert_id($conn);

    /* INSERT TAG (many-to-many) */
    if(isset($_POST['tag'])){
        foreach($_POST['tag'] as $id_tag){
            mysqli_query($conn, "
                INSERT INTO artikel_tag (id_artikel, id_tag)
                VALUES ('$id_artikel', '$id_tag')
            ");
        }
    }

    header("Location: index.php?menu=artikel_saya");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Artikel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f9;">

<?php include "navbar.php"; ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="mb-3">➕ Tambah Artikel</h3>

            <?php
            $kategori = mysqli_query($conn, "SELECT * FROM kategori");
            $tag = mysqli_query($conn, "SELECT * FROM tag");
            ?>

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label>Judul</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Isi</label>
                    <textarea name="isi" class="form-control" rows="5" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php while($k = mysqli_fetch_assoc($kategori)): ?>
                            <option value="<?= $k['id_kategori'] ?>">
                                <?= $k['nama_kategori'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="draft">Draft</option>
                        <option value="publish">Publish</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tag</label><br>
                    <?php while($t = mysqli_fetch_assoc($tag)): ?>
                        <label>
                            <input type="checkbox" name="tag[]" value="<?= $t['id_tag'] ?>">
                            <?= $t['nama_tag'] ?>
                        </label><br>
                    <?php endwhile; ?>
                </div>

                <div class="mb-3">
                    <label>Gambar</label>
                    <input type="file" name="gambar" class="form-control">
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                <a href="index.php?menu=artikel_saya" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
</div>

<?php include "footer.php"; ?>

</body>
</html>