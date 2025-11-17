<?php
include '../../config/koneksi.php';
session_start();

$id_user = $_SESSION['id_user'] ?? 1;

// ===========================================================================
// 1. UPDATE DATA FOOTER (judul_footer & copyright_text)
// ===========================================================================
if (isset($_POST['update_footer'])) {

    $tittle_footer   = $_POST['tittle_footer'];
    $copyright_text  = $_POST['copyright_text'];
    $id_footer       = $_POST['id_footer'];  // id footer sudah ada dari awal

    $query = "
        UPDATE footer 
        SET tittle_footer = $1, 
            copyright_text = $2,
            id_user = $3
        WHERE id_footer = $4
    ";

    $result = pg_query_params($conn, $query, array(
        $tittle_footer, 
        $copyright_text, 
        $id_user,
        $id_footer
    ));

    if ($result) {
        echo "<script>alert('Footer berhasil diperbarui!'); window.location.href='../footer/edit_footer.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui footer.'); window.location.href='../footer/edit_footer.php';</script>";
    }
    exit();
}



// ===========================================================================
// 2. TAMBAH SOSIAL MEDIA
// ===========================================================================
if (isset($_POST['tambah_sosmed'])) {

    $id_footer    = $_POST['id_footer'];
    $logo_sosmed  = $_POST['logo_sosmed'];  // string nama icon (misal: linkedin.svg)
    $url          = $_POST['url'];

    $query = "
        INSERT INTO sosmed_footer (id_footer, logo_sosmed, url)
        VALUES ($1, $2, $3)
    ";

    $result = pg_query_params($conn, $query, array(
        $id_footer,
        $logo_sosmed,
        $url
    ));

    echo "<script>alert('Sosial media berhasil ditambahkan!'); window.location.href='../footer/edit_footer.php';</script>";
    exit();
}



// ===========================================================================
// 3. EDIT SOSIAL MEDIA
// ===========================================================================
if (isset($_POST['edit_sosmed'])) {

    $id_sosmed    = $_POST['id_sosmed'];
    $logo_sosmed  = $_POST['logo_sosmed'];
    $url          = $_POST['url'];

    $query = "
        UPDATE sosmed_footer
        SET logo_sosmed = $1, url = $2
        WHERE id_sosmed = $3
    ";

    pg_query_params($conn, $query, array($logo_sosmed, $url, $id_sosmed));

    echo "<script>alert('Sosial media berhasil diperbarui!'); window.location.href='../footer/edit_footer.php';</script>";
    exit();
}



// ===========================================================================
// 4. HAPUS SOSIAL MEDIA
// ===========================================================================
if (isset($_POST['hapus_sosmed'])) {

    $id_sosmed = $_POST['id_sosmed'];

    $query = "DELETE FROM sosmed_footer WHERE id_sosmed = $1";
    pg_query_params($conn, $query, array($id_sosmed));

    echo "<script>alert('Sosial media berhasil dihapus!'); window.location.href='../footer/edit_footer.php';</script>";
    exit();
}



// ===========================================================================
// 5. TAMBAH CREDIT TIM
// ===========================================================================
if (isset($_POST['tambah_credit'])) {

    $id_footer = $_POST['id_footer'];
    $nama_tim  = $_POST['nama_tim'];

    $query = "
        INSERT INTO credit_tim (id_footer, nama_tim)
        VALUES ($1, $2)
    ";

    pg_query_params($conn, $query, array($id_footer, $nama_tim));

    echo "<script>alert('Credit tim berhasil ditambahkan!'); window.location.href='../footer/edit_footer.php';</script>";
    exit();
}



// ===========================================================================
// 6. EDIT CREDIT TIM
// ===========================================================================
if (isset($_POST['edit_credit'])) {

    $id_credit = $_POST['id_credit'];
    $nama_tim  = $_POST['nama_tim'];

    $query = "
        UPDATE credit_tim
        SET nama_tim = $1
        WHERE id_credit = $2
    ";

    pg_query_params($conn, $query, array($nama_tim, $id_credit));

    echo "<script>alert('Credit tim berhasil diperbarui!'); window.location.href='../footer/edit_footer.php';</script>";
    exit();
}



// ===========================================================================
// 7. HAPUS CREDIT TIM
// ===========================================================================
if (isset($_POST['hapus_credit'])) {

    $id_credit = $_POST['id_credit'];

    $query = "DELETE FROM credit_tim WHERE id_credit = $1";
    pg_query_params($conn, $query, array($id_credit));

    echo "<script>alert('Credit tim berhasil dihapus!'); window.location.href='../footer/edit_footer.php';</script>";
    exit();
}



// ===========================================================================
// BACKUP — Jika akses tanpa POST
// ===========================================================================
echo "<script>alert('Akses tidak valid!'); window.location.href='../footer/edit_footer.php';</script>";
exit();

?>
