<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user
$id_user = $_SESSION['id_user'] ?? 1;

// Folder upload
$uploadDir = '../../uploads/galeri/';

// Pastikan folder ada
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}


/* ============================================================
   0. UPDATE PAGE CONTENT GALERI
   ============================================================ */
if (isset($_POST['edit_page_content'])) {

    $judul_page     = $_POST['judul_page'];
    $deskripsi_page = $_POST['deskripsi_page'];
    $page_key       = "galeri_galeri";

    $query = "UPDATE page_content
              SET judul = $1, deskripsi = $2, id_user = $3
              WHERE page_key = $4";

    $params = array($judul_page, $deskripsi_page, $id_user, $page_key);

    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>
                alert('Konten halaman Galeri berhasil diperbarui!');
                window.location.href='../galeri/galeri.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman!');
                window.location.href='../galeri/galeri.php';
              </script>";
    }

    exit();
}



/* ============================================================
   1. TAMBAH DATA GALERI
   ============================================================ */
if (isset($_POST['tambah'])) {

    $tanggal    = $_POST['tanggal'];
    $judul      = $_POST['judul'];
    $deskripsi  = $_POST['deskripsi'];

    // Upload foto
    $fileName = $_FILES['foto']['name'];
    $tmpName  = $_FILES['foto']['tmp_name'];

    // Buat nama file unik
    $newName = time() . "_" . $fileName;

    move_uploaded_file($tmpName, $uploadDir . $newName);

    $query = "INSERT INTO galeri (tanggal, judul, deskripsi, foto, id_user)
              VALUES ($1, $2, $3, $4, $5)";

    $params = array($tanggal, $judul, $deskripsi, $newName, $id_user);

    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>alert('Data galeri berhasil ditambahkan!'); window.location.href='../galeri/galeri.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan galeri!'); window.location.href='../galeri/tambah_galeri.php';</script>";
    }

    exit();
}



/* ============================================================
   2. EDIT DATA GALERI
   ============================================================ */
if (isset($_POST['edit'])) {

    $id_galeri = $_POST['id_galeri'];
    $tanggal   = $_POST['tanggal'];
    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    // Ambil foto lama
    $q = pg_query($conn, "SELECT foto FROM galeri WHERE id_galeri = $id_galeri");
    $old = pg_fetch_assoc($q);
    $oldFoto = $old['foto'];

    // Jika admin upload foto baru
    if ($_FILES['foto']['name'] != "") {

        $fileName = $_FILES['foto']['name'];
        $tmpName  = $_FILES['foto']['tmp_name'];
        $newName  = time() . "_" . $fileName;

        move_uploaded_file($tmpName, $uploadDir . $newName);

        // Hapus foto lama
        if (file_exists($uploadDir . $oldFoto)) {
            unlink($uploadDir . $oldFoto);
        }

    } else {
        $newName = $oldFoto;
    }

    $query = "UPDATE galeri 
              SET tanggal = $1, judul = $2, deskripsi = $3, foto = $4, id_user = $5
              WHERE id_galeri = $6";

    $params = array($tanggal, $judul, $deskripsi, $newName, $id_user, $id_galeri);

    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>alert('Data galeri berhasil diperbarui!'); window.location.href='../galeri/galeri.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui galeri!'); window.location.href='../galeri/edit_galeri.php?id=$id_galeri';</script>";
    }

    exit();
}



/* ============================================================
   3. HAPUS DATA GALERI
   ============================================================ */
if (isset($_GET['hapus'])) {

    $id_galeri = $_GET['hapus'];

    // Ambil foto lama
    $q = pg_query($conn, "SELECT foto FROM galeri WHERE id_galeri = $id_galeri");
    $old = pg_fetch_assoc($q);
    $oldFoto = $old['foto'];

    // Hapus file foto
    if (file_exists($uploadDir . $oldFoto)) {
        unlink($uploadDir . $oldFoto);
    }

    // Hapus data
    $query = "DELETE FROM galeri WHERE id_galeri = $1";
    $params = array($id_galeri);

    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>alert('Data galeri berhasil dihapus!'); window.location.href='../galeri/galeri.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location.href='../galeri/galeri.php';</script>";
    }

    exit();
}

?>
