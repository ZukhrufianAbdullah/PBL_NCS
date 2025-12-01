<?php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// Ambil id_user dari session (fallback 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;

// GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
$id_page = ensure_page_exists($conn, 'profil_visi_misi');

if (!$id_page) {
    echo "<script>
            alert('Gagal membuat atau mendapatkan halaman Visi & Misi!');
            window.location.href = '../profil/edit_visi_misi.php';
          </script>";
    exit();
}

if (isset($_POST['submit_judul_deskripsi_visi_misi'])) {
    //Ambil input dari form
    $judul = ($_POST['judul']);
    $deskripsi = ($_POST['deskripsi']);

    // Gunakan helper untuk upsert content
    $resultJudul = upsert_page_content($conn, $id_page, 'section_title', $judul, $id_user);
    $resultDeskripsi = upsert_page_content($conn, $id_page, 'section_description', $deskripsi, $id_user);

    // Cek hasil
    if ($resultJudul && $resultDeskripsi) {
        echo "<script>
                alert('Judul & Deskripsi Visi & Misi berhasil diperbarui!');
                window.location.href = '../profil/edit_visi_misi.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui Judul & Deskripsi Visi & Misi!');
                window.location.href = '../profil/edit_visi_misi.php';
              </script>";
    }
} elseif (isset($_POST['submit_visi_misi'])) {
    // Ambil input dari form
    $visi  = ($_POST['visi']);
    $misi  = ($_POST['misi']);

    // UPDATE atau INSERT visi
    $checkVisi = "SELECT id_page_content FROM page_content 
            WHERE id_page = $1 AND content_key = 'visi' LIMIT 1";
    $checkResultVisi = pg_query_params($conn, $checkVisi, array($id_page));

    if (pg_num_rows($checkResultVisi) > 0) {
        // UPDATE
        $updateVisi = "UPDATE page_content 
                 SET content_value = $1, id_user = $2
                 WHERE id_page = $3 AND content_key = 'visi'";
        $resultVisi = pg_query_params($conn, $updateVisi, array($visi, $id_user, $id_page));
    } else {
        // INSERT
        $insertVisi = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                 VALUES ($1, 'visi', 'text', $2, $3)";
        $resultVisi = pg_query_params($conn, $insertVisi, array($id_page, $visi, $id_user));
    }

    // UPDATE atau INSERT misi
    $checkMisi = "SELECT id_page_content FROM page_content 
            WHERE id_page = $1 AND content_key = 'misi' LIMIT 1";
    $checkResultMisi = pg_query_params($conn, $checkMisi, array($id_page));

    if (pg_num_rows($checkResultMisi) > 0) {
        // UPDATE
        $updateMisi = "UPDATE page_content 
                 SET content_value = $1, id_user = $2
                 WHERE id_page = $3 AND content_key = 'misi'";
        $resultMisi = pg_query_params($conn, $updateMisi, array($misi, $id_user, $id_page));
    } else {
        // INSERT
        $insertMisi = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                 VALUES ($1, 'misi', 'text', $2, $3)";
        $resultMisi = pg_query_params($conn, $insertMisi, array($id_page, $misi, $id_user));
    }

    // Cek hasil
    if ($resultVisi && $resultMisi) {
        echo "<script>
                alert('Visi & Misi berhasil diperbarui!');
                window.location.href = '../profil/edit_visi_misi.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui Visi & Misi!');
                window.location.href = '../profil/edit_visi_misi.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../profil/edit_visi_misi.php';
          </script>";
    exit();
}
?>  