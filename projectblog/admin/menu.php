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
else if($menu == "approved_komentar"){
    include "komentar/approved_komentar.php";
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
else if($menu == "users"){
    include "users/users.php";
}
else if($menu == "tambah_user"){
    include "users/tambah_user.php";
}
else if($menu == "edit_user"){
    include "users/edit_user.php";
}
else if($menu == "hapus_user"){
    include "users/hapus_user.php";
}

else if($menu == "tag"){
    include "tag.php";
}


/* ================= DEFAULT ================= */
else{
    include "dashboard.php";
}

?>