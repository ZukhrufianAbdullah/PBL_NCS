<?php
session_start();
include '../../config/koneksi.php';

// ID user (fallback ke 1 jika tidak login)
$id_user = $_SESSION['id_user'] ?? 1;

// Direktori upload
$uploadDir = '../../uploads/galeri/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

/* ============================================================
   HELPER: Pastikan halaman "galeri_dokumentasi" ada
   ============================================================ */
function galeri_ensure_page($conn, $pageName) {
    $q = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", [$pageName]);
    if ($q && pg_num_rows($q) > 0) {
        return pg_fetch_result($q, 0, 'id_page');
    }

    $insert = pg_query_params($conn,
        "INSERT INTO pages (nama) VALUES ($1) RETURNING id_page",
        [$pageName]
    );
    return pg_fetch_result($insert, 0, 'id_page');
}

/* ============================================================
   HELPER: Insert/update page_content
   ============================================================ */
function galeri_upsert_content($conn, $pageId, $key, $value, $userId) {
    $check = pg_query_params($conn,
        "SELECT id_page_content FROM page_content WHERE id_page=$1 AND content_key=$2",
        [$pageId, $key]
    );

    if ($check && pg_num_rows($check) > 0) {
        pg_query_params($conn,
            "UPDATE page_content SET content_value=$1, id_user=$2 WHERE id_page=$3 AND content_key=$4",
            [$value, $userId, $pageId, $key]
        );
    } else {
        pg_query_params($conn,
            "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
             VALUES ($1, $2, 'text', $3, $4)",
            [$pageId, $key, $value, $userId]
        );
    }
}

/* ============================================================
   0. EDIT KONTEN HALAMAN (section title & description)
   ============================================================ */
if (isset($_POST['edit_page'])) {

    $pageId = galeri_ensure_page($conn, "galeri_dokumentasi");

    galeri_upsert_content($conn, $pageId, 'section_title', $_POST['judul_page'], $id_user);
    galeri_upsert_content($conn, $pageId, 'section_description', $_POST['deskripsi_page'], $id_user);

    echo "<script>
            alert('Konten halaman galeri berhasil diperbarui!');
            window.location.href='../galeri/tambah_galeri.php';
          </script>";
    exit();
}

/* ============================================================
   1. TAMBAH DATA GALERI
   ============================================================ */
if (isset($_POST['tambah'])) {

    $tanggal    = $_POST['tanggal_kegiatan'];
    $judul      = $_POST['judul'];
    $deskripsi  = $_POST['deskripsi'];

    // Upload file
    $file = $_FILES['foto_path'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];

    if (!in_array($ext, $allowed)) {
        echo "<script>alert('Format gambar tidak didukung'); history.back();</script>";
        exit();
    }

    $newName = time() . "_" . preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $file['name']);
    move_uploaded_file($file['tmp_name'], $uploadDir . $newName);

    $q = pg_query_params($conn,
        "INSERT INTO galeri (tanggal_kegiatan, judul, deskripsi, media_path, id_user)
         VALUES ($1,$2,$3,$4,$5)",
        [$tanggal, $judul, $deskripsi, $newName, $id_user]
    );

    echo "<script>
            alert('Postingan galeri berhasil ditambahkan!');
            window.location.href='../galeri/tambah_galeri.php';
          </script>";
    exit();
}

/* ============================================================
   2. EDIT DATA GALERI
   ============================================================ */
if (isset($_POST['edit'])) {

    $id = $_POST['id_galeri'];
    $tanggal   = $_POST['tanggal_kegiatan'];
    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    // Ambil media lama
    $old = pg_fetch_assoc(pg_query_params($conn,
        "SELECT media_path FROM galeri WHERE id_galeri=$1",
        [$id]
    ));
    $oldMedia = $old['media_path'];

    // Upload gambar baru jika ada
    if (!empty($_FILES['foto_path']['name'])) {
        $file = $_FILES['foto_path'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            echo "<script>alert('Format gambar tidak didukung'); history.back();</script>";
            exit();
        }

        $newName = time() . "_" . preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $file['name']);
        move_uploaded_file($file['tmp_name'], $uploadDir . $newName);

        // Hapus gambar lama
        if (file_exists($uploadDir . $oldMedia)) unlink($uploadDir . $oldMedia);

    } else {
        $newName = $oldMedia;
    }

    pg_query_params($conn,
        "UPDATE galeri SET 
            tanggal_kegiatan=$1,
            judul=$2,
            deskripsi=$3,
            media_path=$4,
            id_user=$5
         WHERE id_galeri=$6",
        [$tanggal, $judul, $deskripsi, $newName, $id_user, $id]
    );

    echo "<script>
            alert('Postingan galeri berhasil diperbarui!');
            window.location.href='../galeri/tambah_galeri.php';
          </script>";
    exit();
}

/* ============================================================
   3. HAPUS DATA GALERI
   ============================================================ */
if (isset($_POST['hapus'])) {

    $id = $_POST['id_galeri'];

    // Ambil media lama
    $old = pg_fetch_assoc(pg_query_params($conn,
        "SELECT media_path FROM galeri WHERE id_galeri=$1",
        [$id]
    ));
    $oldMedia = $old['media_path'];

    // Hapus file
    if (file_exists($uploadDir . $oldMedia))
        unlink($uploadDir . $oldMedia);

    // Hapus dari DB
    pg_query_params($conn,
        "DELETE FROM galeri WHERE id_galeri=$1",
        [$id]
    );

    echo "<script>
            alert('Postingan galeri berhasil dihapus!');
            window.location.href='../galeri/tambah_galeri.php';
          </script>";
    exit();
}

?>
