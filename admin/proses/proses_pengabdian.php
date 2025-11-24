<?php
session_start();
include '../../config/koneksi.php';

// ambil id_user dari session (fallback 1 jika belum ada)
$id_user = $_SESSION['id_user'] ?? 1;

// helper sederhana
function input_trim($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

function ensure_page($conn, string $pageName): int
{
    $pageResult = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", array($pageName));
    if ($pageResult && pg_num_rows($pageResult) > 0) {
        $page = pg_fetch_assoc($pageResult);
        return (int) $page['id_page'];
    }

    $insertPage = pg_query_params($conn, "INSERT INTO pages (nama) VALUES ($1) RETURNING id_page", array($pageName));
    $page = pg_fetch_assoc($insertPage);
    return (int) $page['id_page'];
}

function upsert_pengabdian_page_content($conn, int $pageId, string $contentKey, string $value, int $userId): void
{
    $checkSql = "SELECT id_page_content FROM page_content WHERE id_page = $1 AND content_key = $2";
    $existing = pg_query_params($conn, $checkSql, array($pageId, $contentKey));

    if ($existing && pg_num_rows($existing) > 0) {
        $updateSql = "
            UPDATE page_content
            SET content_type = 'text', content_value = $1, id_user = $2
            WHERE id_page = $3 AND content_key = $4
        ";
        pg_query_params($conn, $updateSql, array($value, $userId, $pageId, $contentKey));
    } else {
        $insertSql = "
            INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
            VALUES ($1, $2, 'text', $3, $4)
        ";
        pg_query_params($conn, $insertSql, array($pageId, $contentKey, $value, $userId));
    }
}

/* ===========================================================
   0) UPDATE PAGE CONTENT PENGABDIAN
   =========================================================== */
if (isset($_POST['edit_page_content'])) {

    $judul_page     = $_POST['judul_page'];
    $deskripsi_page = $_POST['deskripsi_page'];
    $page_key       = "arsip_pengabdian";    // <= page untuk Pengabdian

    $pageId = ensure_page($conn, $page_key);
    upsert_pengabdian_page_content($conn, $pageId, 'section_title', $judul_page, $id_user);
    upsert_pengabdian_page_content($conn, $pageId, 'section_description', $deskripsi_page, $id_user);

    echo "<script>
            alert('Konten halaman Pengabdian berhasil diperbarui!');
            window.location.href='../arsip/tambah_pengabdian.php';
          </script>";
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
              window.location='../arsip/tambah_pengabdian.php';</script>";
        exit();
    }

    if (!ctype_digit($tahun) || !ctype_digit($id_ketua)) {
        echo "<script>alert('Format tahun atau ketua tidak valid!'); 
              window.location='../arsip/tambah_pengabdian.php';</script>";
        exit();
    }

    $query = "INSERT INTO pengabdian (judul_pengabdian, skema, tahun, id_ketua, id_user)
              VALUES ($1, $2, $3, $4, $5)";

    $params = array($judul, $skema, (int)$tahun, (int)$id_ketua, (int)$id_user);
    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil ditambahkan!'); 
              window.location='../arsip/tambah_pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal menambahkan data: " . addslashes($err) . "'); 
              window.location='../arsip/tambah_pengabdian.php';</script>";
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
              window.location='../arsip/tambah_pengabdian.php';</script>";
        exit();
    }

    if (empty($judul) || empty($tahun) || empty($id_ketua) || empty($skema)) {
        echo "<script>alert('Judul, Tahun, Skema, dan Ketua wajib diisi!'); 
              window.location='../arsip/tambah_pengabdian.php';</script>";
        exit();
    }

    if (!ctype_digit($tahun) || !ctype_digit($id_ketua)) {
        echo "<script>alert('Format tahun atau ketua tidak valid!'); 
              window.location='../arsip/tambah_pengabdian.php';</script>";
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
              window.location='../arsip/tambah_pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal memperbarui data: " . addslashes($err) . "'); 
              window.location='../arsip/tambah_pengabdian.php';</script>";
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
              window.location='../arsip/tambah_pengabdian.php';</script>";
        exit();
    }

    $query = "DELETE FROM pengabdian WHERE id_pengabdian = $1";
    $res   = pg_query_params($conn, $query, array((int)$id_pengabdian));

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil dihapus!'); 
              window.location='../arsip/tambah_pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal menghapus data: " . addslashes($err) . "'); 
              window.location='../arsip/tambah_pengabdian.php';</script>";
    }
    exit();
}


/* ===========================================================
   TIDAK ADA AKSI
   =========================================================== */
echo "<script>alert('Aksi tidak valid'); window.location='../arsip/tambah_pengabdian.php';</script>";
exit();

?>
