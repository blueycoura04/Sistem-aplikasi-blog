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

/* ================= ARTIKEL (MILIK PENULIS) ================= */
else if($menu == "artikel_saya"){
    include "artikel_saya.php";
}
else if($menu == "tambah_artikel"){
    include "tambah_artikel.php";
}
else if($menu == "edit_artikel"){
    include "edit_artikel.php";
}
else if($menu == "hapus_artikel"){
    include "hapus_artikel.php";
}

/* ================= KATEGORI (VIEW ONLY) ================= */
else if($menu == "kategori"){
    include "kategori.php";
}

/* ================= TAG (VIEW ONLY) ================= */
else if($menu == "tag"){
    include "tag.php";
}

/* ================= PROFIL ================= */
else if($menu == "profil_penulis"){
    include "profil_penulis.php";
}

/* ================= DEFAULT ================= */
else{
    include "dashboard.php";
}

?>