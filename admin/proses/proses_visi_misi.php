<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session (fallback 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;

// Pastikan tombol submit ditekan
if (!isset($_POST['submit'])) {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../profil/edit_visi_misi.php';
          </script>";
    exit();
}

// Ambil input
$visi  = trim($_POST['visi']);
$misi  = trim($_POST['misi']);

// ======================================================
// 1. Ambil id_page untuk halaman 'profil_visi_misi'
// ======================================================
$sqlPage = "SELECT id_page FROM pages WHERE nama = 'profil_visi_misi' LIMIT 1";
$resultPage = pg_query($conn, $sqlPage);

if (!$resultPage || pg_num_rows($resultPage) === 0) {
    echo "<script>
            alert('Halaman Visi & Misi tidak ditemukan di tabel pages!');
            window.location.href = '../profil/edit_visi_misi.php';
          </script>";
    exit();
}

$page = pg_fetch_assoc($resultPage);
$id_page = $page['id_page'];

// ======================================================
// 2. Fungsi reusable untuk UPDATE atau INSERT CMS Content
// ======================================================
function save_content($conn, $id_page, $key, $value, $id_user)
{
    // Cek apakah konten sudah ada
    $check = "SELECT id_page_content FROM page_content 
              WHERE id_page = $1 AND content_key = $2 LIMIT 1";
    $checkResult = pg_query_params($conn, $check, array($id_page, $key));

    if (pg_num_rows($checkResult) > 0) {
        // UPDATE
        $update = "UPDATE page_content 
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = $4";
        return pg_query_params($conn, $update, array($value, $id_user, $id_page, $key));
    } else {
        // INSERT
        $insert = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, $2, 'text', $3, $4)";
        return pg_query_params($conn, $insert, array($id_page, $key, $value, $id_user));
    }
}

// ======================================================
// 3. Simpan VISI & MISI
// ======================================================
$saveVisi = save_content($conn, $id_page, 'visi', $visi, $id_user);
$saveMisi = save_content($conn, $id_page, 'misi', $misi, $id_user);

// ======================================================
// 4. Cek hasil
// ======================================================
if ($saveVisi && $saveMisi) {
    echo "<script>
            alert('Visi & Misi berhasil diperbarui!');
            window.location.href = '../profil/edit_visi_misi.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menyimpan Visi & Misi!');
            window.location.href = '../profil/edit_visi_misi.php';
          </script>";
}

exit();
?>
