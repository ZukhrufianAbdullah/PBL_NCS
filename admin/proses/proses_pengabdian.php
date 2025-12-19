<?php
// Memulai session untuk autentikasi user
session_start();

// Memanggil file koneksi database
include '../../config/koneksi.php';

// Memanggil helper page (untuk page & content management)
include __DIR__ . "/../../app/helpers/page_helper.php";

// Mengambil id_user dari session (fallback ke 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;

// -----------------------------------------------------------
// Helper sederhana untuk trim input POST
// -----------------------------------------------------------
function input_trim($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

/* ===========================================================
   0) UPDATE PAGE CONTENT PENGABDIAN
   =========================================================== */
if (isset($_POST['edit_page_content'])) {

    // Ambil judul dan deskripsi halaman
    $judul_page     = $_POST['judul_page'];
    $deskripsi_page = $_POST['deskripsi_page'];
    
    // Mengambil atau membuat halaman arsip pengabdian
    $id_page = ensure_page_exists($conn, 'arsip_pengabdian');
    
    // Jika gagal mendapatkan halaman
    if (!$id_page) {
        echo "<script>
                alert('Gagal membuat atau mendapatkan halaman Pengabdian!');
                window.location.href='../arsip/edit_pengabdian.php';
              </script>";
        exit();
    }

    // Update / insert judul halaman
    $resultJudul = upsert_page_content(
        $conn,
        $id_page,
        'section_title',
        $judul_page,
        $id_user
    );

    // Update / insert deskripsi halaman
    $resultDeskripsi = upsert_page_content(
        $conn,
        $id_page,
        'section_description',
        $deskripsi_page,
        $id_user
    );

    // Notifikasi hasil update konten halaman
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

    // Ambil data dari form
    $judul    = input_trim('judul_pengabdian');
    $tahun    = input_trim('tahun');
    $id_ketua = input_trim('id_ketua'); 
    $skema    = input_trim('skema');

    // Validasi input wajib
    if (empty($judul) || empty($tahun) || empty($id_ketua) || empty($skema)) {
        echo "<script>
                alert('Judul, Tahun, Skema, dan Ketua wajib diisi!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
        exit();
    }

    // Validasi format angka
    if (!ctype_digit($tahun) || !ctype_digit($id_ketua)) {
        echo "<script>
                alert('Format tahun atau ketua tidak valid!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
        exit();
    }

    // Query insert data pengabdian
    $query = "
        INSERT INTO pengabdian
        (judul_pengabdian, skema, tahun, id_ketua, id_user)
        VALUES ($1, $2, $3, $4, $5)
    ";

    // Parameter query
    $params = array(
        $judul,
        $skema,
        (int)$tahun,
        (int)$id_ketua,
        (int)$id_user
    );

    // Eksekusi query
    $res = pg_query_params($conn, $query, $params);

    // Notifikasi hasil proses
    if ($res) {
        echo "<script>
                alert('Data pengabdian berhasil ditambahkan!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>
                alert('Gagal menambahkan data: " . addslashes($err) . "');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
    }
    exit();
}

/* ===========================================================
   2) EDIT PENGABDIAN
   =========================================================== */
if (isset($_POST['edit'])) {

    // Ambil data pengabdian
    $id_pengabdian = input_trim('id_pengabdian');
    $judul         = input_trim('judul_pengabdian');
    $tahun         = input_trim('tahun');
    $id_ketua      = input_trim('id_ketua');
    $skema         = input_trim('skema');

    // Validasi ID pengabdian
    if (empty($id_pengabdian) || !ctype_digit($id_pengabdian)) {
        echo "<script>
                alert('ID pengabdian tidak valid!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
        exit();
    }

    // Validasi input wajib
    if (empty($judul) || empty($tahun) || empty($id_ketua) || empty($skema)) {
        echo "<script>
                alert('Judul, Tahun, Skema, dan Ketua wajib diisi!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
        exit();
    }

    // Validasi format angka
    if (!ctype_digit($tahun) || !ctype_digit($id_ketua)) {
        echo "<script>
                alert('Format tahun atau ketua tidak valid!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
        exit();
    }

    // Query update data pengabdian
    $query = "
        UPDATE pengabdian
        SET judul_pengabdian = $1,
            tahun            = $2,
            id_ketua         = $3,
            skema            = $4,
            id_user          = $5
        WHERE id_pengabdian = $6
    ";

    // Parameter query
    $params = array(
        $judul,
        (int)$tahun,
        (int)$id_ketua,
        $skema,
        (int)$id_user,
        (int)$id_pengabdian
    );

    // Eksekusi query
    $res = pg_query_params($conn, $query, $params);

    // Notifikasi hasil proses
    if ($res) {
        echo "<script>
                alert('Data pengabdian berhasil diperbarui!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>
                alert('Gagal memperbarui data: " . addslashes($err) . "');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
    }
    exit();
}

/* ===========================================================
   3) HAPUS PENGABDIAN
   =========================================================== */
if (isset($_POST['hapus'])) {

    // Ambil ID pengabdian
    $id_pengabdian = $_POST['id_pengabdian'];

    // Validasi ID
    if (!ctype_digit((string)$id_pengabdian)) {
        echo "<script>
                alert('ID tidak valid!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
        exit();
    }

    // Query hapus data
    $query = "DELETE FROM pengabdian WHERE id_pengabdian = $1";
    $res   = pg_query_params($conn, $query, array((int)$id_pengabdian));

    // Notifikasi hasil proses
    if ($res) {
        echo "<script>
                alert('Data pengabdian berhasil dihapus!');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>
                alert('Gagal menghapus data: " . addslashes($err) . "');
                window.location='../arsip/edit_pengabdian.php';
              </script>";
    }
    exit();
}

/* ===========================================================
   TIDAK ADA AKSI YANG VALID
   =========================================================== */
echo "<script>
        alert('Aksi tidak valid');
        window.location='../arsip/edit_pengabdian.php';
      </script>";
exit();
?>
