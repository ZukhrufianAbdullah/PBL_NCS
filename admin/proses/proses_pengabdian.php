<?php
session_start();
include '../../config/koneksi.php';

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
    $page_key       = "pengabdian_page";    // <= page_key untuk halaman Pengabdian

    $query = "UPDATE page_content
              SET judul = $1, deskripsi = $2, id_user = $3
              WHERE page_key = $4";

    $result = pg_query_params($conn, $query, array(
        $judul_page,
        $deskripsi_page,
        $id_user,
        $page_key
    ));

    if ($result) {
        echo "<script>
                alert('Konten halaman Pengabdian berhasil diperbarui!');
                window.location.href='../arsip/pengabdian.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman!');
                window.location.href='../arsip/pengabdian.php';
              </script>";
    }
    exit();
}



/* ===========================================================
   1) TAMBAH PENGABDIAN
   =========================================================== */
if (isset($_POST['tambah'])) {

    $judul        = input_trim('judul_pengabdian');
    $deskripsi    = input_trim('deskripsi');
    $tahun        = input_trim('tahun');
    $nama_ketua   = input_trim('nama_ketua');
    $nama_anggota = input_trim('nama_anggota');
    $id_prodi     = input_trim('id_prodi');
    $skema        = input_trim('skema');

    if (empty($judul) || empty($tahun) || empty($id_prodi)) {
        echo "<script>alert('Judul, Tahun, dan Prodi wajib diisi!'); 
              window.location='../pengabdian/tambah_pengabdian.php';</script>";
        exit();
    }

    if (!ctype_digit($tahun)) {
        echo "<script>alert('Format tahun tidak valid!'); 
              window.location='../pengabdian/tambah_pengabdian.php';</script>";
        exit();
    }

    $query = "INSERT INTO pengabdian (judul_pengabdian, deskripsi, tahun, nama_ketua, nama_anggota, id_prodi, skema, id_user)
              VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";

    $params = array($judul, $deskripsi, (int)$tahun, $nama_ketua, $nama_anggota, (int)$id_prodi, $skema, (int)$id_user);
    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil ditambahkan!'); 
              window.location='../arsip/pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal menambahkan data: " . addslashes($err) . "'); 
              window.location='../pengabdian/tambah_pengabdian.php';</script>";
    }
    exit();
}

/* ===========================================================
   2) EDIT PENGABDIAN
   =========================================================== */
if (isset($_POST['edit'])) {

    $id_pengabdian = input_trim('id_pengabdian');
    $judul         = input_trim('judul_pengabdian');
    $deskripsi     = input_trim('deskripsi');
    $tahun         = input_trim('tahun');
    $nama_ketua    = input_trim('nama_ketua');
    $nama_anggota  = input_trim('nama_anggota');
    $id_prodi      = input_trim('id_prodi');
    $skema         = input_trim('skema');

    if (empty($id_pengabdian) || !ctype_digit($id_pengabdian)) {
        echo "<script>alert('ID pengabdian tidak valid!'); 
              window.location='../arsip/pengabdian.php';</script>";
        exit();
    }

    if (empty($judul) || empty($tahun) || empty($id_prodi)) {
        echo "<script>alert('Judul, Tahun, dan Prodi wajib diisi!'); 
              window.location='../pengabdian/edit_pengabdian.php?id=".$id_pengabdian."';</script>";
        exit();
    }

    if (!ctype_digit($tahun)) {
        echo "<script>alert('Format tahun tidak valid!'); 
              window.location='../pengabdian/edit_pengabdian.php?id=".$id_pengabdian."';</script>";
        exit();
    }

    $query = "UPDATE pengabdian
              SET judul_pengabdian = $1,
                  deskripsi        = $2,
                  tahun            = $3,
                  nama_ketua       = $4,
                  nama_anggota     = $5,
                  id_prodi         = $6,
                  skema            = $7,
                  id_user          = $8
              WHERE id_pengabdian = $9";

    $params = array(
        $judul, $deskripsi, (int)$tahun, $nama_ketua, $nama_anggota,
        (int)$id_prodi, $skema, (int)$id_user, (int)$id_pengabdian
    );

    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil diperbarui!'); 
              window.location='../arsip/pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal memperbarui data: " . addslashes($err) . "'); 
              window.location='../pengabdian/edit_pengabdian.php?id=".$id_pengabdian."';</script>";
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
              window.location='../arsip/pengabdian.php';</script>";
        exit();
    }

    $query = "DELETE FROM pengabdian WHERE id_pengabdian = $1";
    $res   = pg_query_params($conn, $query, array((int)$id_pengabdian));

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil dihapus!'); 
              window.location='../arsip/pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal menghapus data: " . addslashes($err) . "'); 
              window.location='../arsip/pengabdian.php';</script>";
    }
    exit();
}


/* ===========================================================
   TIDAK ADA AKSI
   =========================================================== */
echo "<script>alert('Aksi tidak valid'); window.location='../arsip/pengabdian.php';</script>";
exit();

?>
