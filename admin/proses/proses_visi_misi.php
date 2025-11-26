<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session (fallback 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;


// ambil id_page untuk halaman 'profil_visi_misi'
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

if (isset($_POST['submit_judul_deskripsi_visi_misi'])) {
    //Ambil input dari form
    $judul = ($_POST['judul']);
    $deskripsi = ($_POST['deskripsi']);

    // UPDATE atau INSERT judul
    $checkJudul = "SELECT id_page_content FROM page_content 
              WHERE id_page = $1 AND content_key = 'judul_visi_misi' LIMIT 1";
    $checkResultJudulVisiMisi = pg_query_params($conn, $checkJudul, array($id_page));

    if (pg_num_rows($checkResultJudulVisiMisi) > 0) {
        // UPDATE
        $updateJudul = "UPDATE page_content 
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = 'judul_visi_misi'";
        $resultJudul = pg_query_params($conn, $updateJudul, array($judul, $id_user, $id_page));
    } else {
        // INSERT
        $insertJudul = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, 'judul_visi_misi', 'text', $2, $3)";
        $resultJudul = pg_query_params($conn, $insertJudul, array($id_page, $judul, $id_user));
    }

    // UPDATE atau INSERT deskripsi
    $checkDeskripsi = "SELECT id_page_content FROM page_content 
              WHERE id_page = $1 AND content_key = 'deskripsi_visi_misi' LIMIT 1";
    $checkResultDeskripsiVisiMisi = pg_query_params($conn, $checkDeskripsi, array($id_page));

    if (pg_num_rows($checkResultDeskripsiVisiMisi) > 0) {
        // UPDATE
        $updateDeskripsi = "UPDATE page_content 
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = 'deskripsi_visi_misi'";
        $resultDeskripsi = pg_query_params($conn, $updateDeskripsi, array($deskripsi, $id_user, $id_page));
    } else {
        // INSERT
        $insertDeskripsi = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, 'deskripsi_visi_misi', 'text', $2, $3)";
        $resultDeskripsi = pg_query_params($conn, $insertDeskripsi, array($id_page, $deskripsi, $id_user));
    }

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
