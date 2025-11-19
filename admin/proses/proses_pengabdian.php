<?php
session_start();
include '../../config/koneksi.php';

// ambil id_user dari session (fallback 1 jika belum ada)
$id_user = $_SESSION['id_user'] ?? 1;

/*
Assumsi kolom tabel `pengabdian`:
id_pengabdian (SERIAL)
judul_pengabdian (VARCHAR)
deskripsi (TEXT)           -- optional, gunakan jika ada
tahun (INT)
nama_ketua (VARCHAR)      -- optional, gunakan jika ada
nama_anggota (TEXT)       -- optional, gunakan jika ada (comma separated)
id_prodi (INT)            -- referensi ke tabel prodi
skema (VARCHAR)
id_user (INT)
*/

// helper sederhana: safe trim
function input_trim($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

/* ===========================================================
   1) TAMBAH PENGABDIAN
   Form harus mengirim name="tambah"
   Fields expected: judul_pengabdian, deskripsi (opsional), tahun, nama_ketua, nama_anggota, id_prodi, skema
   =========================================================== */
if (isset($_POST['tambah'])) {

    $judul        = input_trim('judul_pengabdian');
    $deskripsi    = input_trim('deskripsi');        // optional
    $tahun        = input_trim('tahun');
    $nama_ketua   = input_trim('nama_ketua');
    $nama_anggota = input_trim('nama_anggota');     // comma separated
    $id_prodi     = input_trim('id_prodi');         // expects numeric id
    $skema        = input_trim('skema');

    // Validasi sederhana
    if (empty($judul) || empty($tahun) || empty($id_prodi)) {
        echo "<script>alert('Judul, Tahun, dan Prodi wajib diisi!'); window.location='../pengabdian/tambah_pengabdian.php';</script>";
        exit();
    }

    if (!ctype_digit($tahun)) {
        echo "<script>alert('Format tahun tidak valid!'); window.location='../pengabdian/tambah_pengabdian.php';</script>";
        exit();
    }

    // Bangun query INSERT. Jika kolom deskripsi/nama_ketua/nama_anggota tidak ada di DB, ubah query sesuai DB kalian.
    $query = "INSERT INTO pengabdian (judul_pengabdian, deskripsi, tahun, nama_ketua, nama_anggota, id_prodi, skema, id_user)
              VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";

    $params = array($judul, $deskripsi, (int)$tahun, $nama_ketua, $nama_anggota, (int)$id_prodi, $skema, (int)$id_user);
    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil ditambahkan!'); window.location='../arsip/pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal menambahkan data: " . addslashes($err) . "'); window.location='../pengabdian/tambah_pengabdian.php';</script>";
    }
    exit();
}

/* ===========================================================
   2) EDIT PENGABDIAN
   Form must send name="edit" and hidden id_pengabdian
   =========================================================== */
if (isset($_POST['edit'])) {

    $id_pengabdian = input_trim('id_pengabdian');
    $judul         = input_trim('judul_pengabdian');
    $deskripsi     = input_trim('deskripsi');        // optional
    $tahun         = input_trim('tahun');
    $nama_ketua    = input_trim('nama_ketua');
    $nama_anggota  = input_trim('nama_anggota');
    $id_prodi      = input_trim('id_prodi');
    $skema         = input_trim('skema');

    if (empty($id_pengabdian) || !ctype_digit($id_pengabdian)) {
        echo "<script>alert('ID pengabdian tidak valid!'); window.location='../arsip/pengabdian.php';</script>";
        exit();
    }

    if (empty($judul) || empty($tahun) || empty($id_prodi)) {
        echo "<script>alert('Judul, Tahun, dan Prodi wajib diisi!'); window.location='../pengabdian/edit_pengabdian.php?id=".$id_pengabdian."';</script>";
        exit();
    }

    if (!ctype_digit($tahun)) {
        echo "<script>alert('Format tahun tidak valid!'); window.location='../pengabdian/edit_pengabdian.php?id=".$id_pengabdian."';</script>";
        exit();
    }

    // UPDATE query
    $query = "UPDATE pengabdian
              SET judul_pengabdian = $1,
                  deskripsi = $2,
                  tahun = $3,
                  nama_ketua = $4,
                  nama_anggota = $5,
                  id_prodi = $6,
                  skema = $7,
                  id_user = $8
              WHERE id_pengabdian = $9";

    $params = array($judul, $deskripsi, (int)$tahun, $nama_ketua, $nama_anggota, (int)$id_prodi, $skema, (int)$id_user, (int)$id_pengabdian);
    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil diperbarui!'); window.location='../arsip/pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal memperbarui data: " . addslashes($err) . "'); window.location='../pengabdian/edit_pengabdian.php?id=".$id_pengabdian."';</script>";
    }
    exit();
}

/* ===========================================================
   3) HAPUS PENGABDIAN
   Access via GET: proses_pengabdian.php?hapus=<id>
   =========================================================== */
if (isset($_GET['hapus'])) {

    $id_pengabdian = $_GET['hapus'];

    if (!ctype_digit((string)$id_pengabdian)) {
        echo "<script>alert('ID tidak valid!'); window.location='../arsip/pengabdian.php';</script>";
        exit();
    }

    // Jika ada tabel relasi (pengabdian_dosen) hapus relasi dulu (opsional)
    // contoh: DELETE FROM pengabdian_dosen WHERE id_pengabdian = $1;

    // Hapus data pengabdian
    $query = "DELETE FROM pengabdian WHERE id_pengabdian = $1";
    $res = pg_query_params($conn, $query, array((int)$id_pengabdian));

    if ($res) {
        echo "<script>alert('Data pengabdian berhasil dihapus!'); window.location='../arsip/pengabdian.php';</script>";
    } else {
        $err = pg_last_error($conn);
        echo "<script>alert('Gagal menghapus data: " . addslashes($err) . "'); window.location='../arsip/pengabdian.php';</script>";
    }
    exit();
}

/* Jika tidak ada aksi yang cocok */
echo "<script>alert('Aksi tidak valid'); window.location='../arsip/pengabdian.php';</script>";
exit();

?>
