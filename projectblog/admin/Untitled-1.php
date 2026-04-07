<?php 

if(isset($_GET['menu'])){
    $menu = $_GET['menu'];
} else {
    $menu = "";
}

/* ================= DASHBOARD ================= */
if($menu == "dashboard"){
    include "dashboard.php";
}

/* ================= KOMENTAR ================= */
else if($menu == "komentar"){
    include "komentar/komentar.php";
}
else if($menu == "hapus_komentar"){
    include "komentar/hapus_komentar.php";
}
else if($menu == "approve_komentar"){
    include "komentar/approve_komentar.php";
}

/* ================= ARTIKEL ================= */
else if($menu == "artikel"){
    include "artikel/artikel.php";
}
else if($menu == "tambah_artikel"){
    include "artikel/tambah_artikel.php";
}
else if($menu == "edit_artikel"){
    include "artikel/edit_artikel.php";
}
else if($menu == "hapus_artikel"){
    include "artikel/hapus_artikel.php";
}

/* ================= KATEGORI ================= */
else if($menu == "kategori"){
    include "kategori/kategori.php";
}
else if($menu == "tambah_kategori"){
    include "kategori/tambah_kategori.php";
}
else if($menu == "edit_kategori"){
    include "kategori/edit_kategori.php";
}
else if($menu == "hapus_kategori"){
    include "kategori/hapus_kategori.php";
}

/* ================= USER ================= */
else if($menu == "user"){
    include "user/user.php";
}
else if($menu == "tambah_user"){
    include "user/tambah_user.php";
}
else if($menu == "edit_user"){
    include "user/edit_user.php";
}
else if($menu == "hapus_user"){
    include "user/hapus_user.php";
}

/* ================= DEFAULT ================= */
else{
    include "dashboard.php";
}

?>