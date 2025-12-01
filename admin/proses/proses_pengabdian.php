<?php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// ambil id_user dari session (fallback 1 jika belum ada)
$id_user = $_SESSION['id_user'] ?? 1;

// helper sederhana
function input_trim($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

/* ===========================================================
   0) UPDATE PAGE CONTENT PENGABDIAN
   =========================================================== */
if (isset($_POST['edit_page_content'])) {

    $judul_page     = $_POST['judul_page'];
    $deskripsi_page = $_POST['deskripsi_page'];
    
    // GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
    $id_page = ensure_page_exists($conn, 'arsip_pengabdian');
    
    if (!$id_page) {
        echo "<script>alert('Gagal membuat atau mendapatkan halaman Pengabdian!');
              window.location.href='../arsip/edit_pengabdian.php';</script>";
        exit();
    }

    // GUNAKAN HELPER FUNCTION untuk upsert content
    // Menggunakan 'section_title' dan 'section_description' untuk konsistensi
    $resultJudul = upsert_page_content($conn, $id_page, 'section_title', $judul_page, $id_user);
    $resultDeskripsi = upsert_page_content($conn, $id_page, 'section_description', $deskripsi_page, $id_user);

    if ($resultJudul && $resultDeskripsi) {
        echo "<script>
                alert('Konten halaman Pengabdian berhasil diperbarui!');
                window.location.href='../arsip/edit_pengabdian.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman Pengabdian!');
                window.location.href='../arsip/edit_pengabdian.php';
              </script>";
    }
    exit();
}

/* ===========================================================
   1) TAMBAH PENGABDIAN
   =========================================================== */
if (isset($_POST['tambah'])) {

    $judul        = input_trim('judul_pengabdian');
    $tahun        = input_trim('tahun');
    $id_ketua     = input_trim('id_ketua'); 
    $skema        = input_trim('skema');

    if (empty($judul) || empty($tahun) || empty($id_ketua) || empty($skema)) {
        echo "<script>alert('Judul, Tahun, Skema, dan Ketua wajib diisi!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
        exit();
    }

    if (!ctype_digit($tahun) || !ctype_digit($id_ketua)) {
        echo "<script>alert('Format tahun atau ketua tidak valid!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
        exit();
    }

    $query = "INSERT INTO pengabdian (judul_pengabdian, skema, tahun, id_ketua, id_user)
              VALUES ($1, $2, $3, $4, $5)";

    $params = array($judul, $skema, (int)$tahun, (int)$id_ketua, (int)$id_user);
    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil ditambahkan!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal menambahkan data: " . addslashes($err) . "'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
    }
    exit();
}

/* ===========================================================
   2) EDIT PENGABDIAN
   =========================================================== */
if (isset($_POST['edit'])) {

    $id_pengabdian = input_trim('id_pengabdian');
    $judul         = input_trim('judul_pengabdian');
    $tahun         = input_trim('tahun');
    $id_ketua      = input_trim('id_ketua');
    $skema         = input_trim('skema');

    if (empty($id_pengabdian) || !ctype_digit($id_pengabdian)) {
        echo "<script>alert('ID pengabdian tidak valid!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
        exit();
    }

    if (empty($judul) || empty($tahun) || empty($id_ketua) || empty($skema)) {
        echo "<script>alert('Judul, Tahun, Skema, dan Ketua wajib diisi!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
        exit();
    }

    if (!ctype_digit($tahun) || !ctype_digit($id_ketua)) {
        echo "<script>alert('Format tahun atau ketua tidak valid!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
        exit();
    }

    $query = "UPDATE pengabdian
              SET judul_pengabdian = $1,
                  tahun            = $2,
                  id_ketua         = $3,
                  skema            = $4,
                  id_user          = $5
              WHERE id_pengabdian = $6";

    $params = array(
        $judul, (int)$tahun, (int)$id_ketua, $skema, (int)$id_user, (int)$id_pengabdian
    );

    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil diperbarui!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal memperbarui data: " . addslashes($err) . "'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
    }
    exit();
}

/* ===========================================================
   3) HAPUS PENGABDIAN
   =========================================================== */
if (isset($_GET['hapus'])) {

    $id_pengabdian = $_GET['hapus'];

    if (!ctype_digit((string)$id_pengabdian)) {
        echo "<script>alert('ID tidak valid!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
        exit();
    }

    $query = "DELETE FROM pengabdian WHERE id_pengabdian = $1";
    $res   = pg_query_params($conn, $query, array((int)$id_pengabdian));

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil dihapus!'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal menghapus data: " . addslashes($err) . "'); 
              window.location='../arsip/edit_pengabdian.php';</script>";
    }
    exit();
}


/* ===========================================================
   TIDAK ADA AKSI
   =========================================================== */
echo "<script>alert('Aksi tidak valid'); window.location='../arsip/edit_pengabdian.php';</script>";
exit();

?>