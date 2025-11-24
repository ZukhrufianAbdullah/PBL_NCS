<?php
session_start();
include "../../config/koneksi.php";

// Ambil id_user
$id_user = $_SESSION['id_user'] ?? 1;

// Folder upload
$uploadDir = "../../uploads/sarana/";

// Ekstensi file yang diperbolehkan
$allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function sarana_ensure_page($conn, string $name): int
{
    $pageRes = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", array($name));
    if ($pageRes && pg_num_rows($pageRes) > 0) {
        return (int) pg_fetch_result($pageRes, 0, 'id_page');
    }
    $insert = pg_query_params($conn, "INSERT INTO pages (nama) VALUES ($1) RETURNING id_page", array($name));
    return (int) pg_fetch_result($insert, 0, 'id_page');
}

function sarana_upsert($conn, int $pageId, string $key, string $value, int $userId): void
{
    $check = pg_query_params($conn, "SELECT id_page_content FROM page_content WHERE id_page = $1 AND content_key = $2", array($pageId, $key));
    if ($check && pg_num_rows($check) > 0) {
        pg_query_params(
            $conn,
            "UPDATE page_content SET content_type = 'text', content_value = $1, id_user = $2 WHERE id_page = $3 AND content_key = $4",
            array($value, $userId, $pageId, $key)
        );
    } else {
        pg_query_params(
            $conn,
            "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) VALUES ($1, $2, 'text', $3, $4)",
            array($pageId, $key, $value, $userId)
        );
    }
}


/* =============================================================
   0) UPDATE PAGE CONTENT SARANA & PRASARANA
   ============================================================= */
if (isset($_POST['edit_page_content'])) {

    $judul_page     = $_POST['judul_page'];
    $deskripsi_page = $_POST['deskripsi_page'];
    $page_key       = "layanan_sarana";

    $pageId = sarana_ensure_page($conn, $page_key);
    sarana_upsert($conn, $pageId, 'section_title', $judul_page, $id_user);
    sarana_upsert($conn, $pageId, 'section_description', $deskripsi_page, $id_user);

    echo "<script>
            alert('Konten halaman Sarana & Prasarana berhasil diperbarui!');
            window.location.href='../layanan/edit_sarana_prasarana.php';
          </script>";
    exit();
}



/* =============================================================
   1) TAMBAH SARANA
   ============================================================= */
if (isset($_POST['tambah_sarana'])) {

    $nama_sarana = $_POST['nama_sarana'];
    $file = $_FILES['media']['name'];
    $tmp  = $_FILES['media']['tmp_name'];

    if (!empty($file)) {

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Format file tidak valid!'); history.back();</script>";
            exit();
        }

        // Rename file
        $newFile = "sarana_" . time() . "." . $ext;

        // Upload
        move_uploaded_file($tmp, $uploadDir . $newFile);

        // Insert ke database
        $query = "INSERT INTO sarana (nama_sarana, media_path, id_user)
                  VALUES ($1, $2, $3)";

        pg_query_params($conn, $query, array($nama_sarana, $newFile, $id_user));
    }

    echo "<script>
            alert('Sarana berhasil ditambahkan!');
            window.location.href='../layanan/edit_sarana_prasarana.php';
          </script>";
    exit();
}



/* =============================================================
   2) EDIT SARANA
   ============================================================= */
if (isset($_POST['edit_sarana'])) {

    $id_sarana   = $_POST['id_sarana'];
    $nama_sarana = $_POST['nama_sarana'];

    // Ambil foto lama
    $oldQuery = pg_query_params($conn, "SELECT media_path FROM sarana WHERE id_sarana = $1", array($id_sarana));
    $oldData  = pg_fetch_assoc($oldQuery);
    $oldFoto  = $oldData['media_path'];

    $file = $_FILES['media']['name'];
    $tmp  = $_FILES['media']['tmp_name'];

    // Jika ada upload file baru
    if (!empty($file)) {

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Format file tidak valid!'); history.back();</script>";
            exit();
        }

        // Nama file unik
        $newFoto = "sarana_" . time() . "." . $ext;

        move_uploaded_file($tmp, $uploadDir . $newFoto);

        // Hapus foto lama
        if (!empty($oldFoto) && file_exists($uploadDir . $oldFoto)) {
            unlink($uploadDir . $oldFoto);
        }

    } else {
        $newFoto = $oldFoto; // tetap foto lama
    }

    // Update data sarana
    $query = "UPDATE sarana 
              SET nama_sarana = $1, media_path = $2, id_user = $3
              WHERE id_sarana = $4";

    pg_query_params($conn, $query, array($nama_sarana, $newFoto, $id_user, $id_sarana));

    echo "<script>
            alert('Sarana berhasil diperbarui!');
            window.location.href='../layanan/edit_sarana_prasarana.php';
          </script>";
    exit();
}



/* =============================================================
   3) HAPUS SARANA
   ============================================================= */
if (isset($_GET['hapus'])) {

    $id_sarana = $_GET['hapus'];

    // Ambil foto lama
    $oldQuery = pg_query_params($conn, "SELECT media_path FROM sarana WHERE id_sarana = $1", array($id_sarana));
    $oldData  = pg_fetch_assoc($oldQuery);
    $oldFoto  = $oldData['media_path'];

    // Hapus foto fisik
    if (!empty($oldFoto) && file_exists($uploadDir . $oldFoto)) {
        unlink($uploadDir . $oldFoto);
    }

    // Hapus dari database
    pg_query_params($conn, "DELETE FROM sarana WHERE id_sarana = $1", array($id_sarana));

    echo "<script>
            alert('Sarana berhasil dihapus!');
            window.location.href='../layanan/edit_sarana_prasarana.php';
          </script>";
    exit();
}



/* =============================================================
   4) JIKA AKSI TIDAK VALID
   ============================================================= */
echo "<script>
        alert('Aksi tidak valid!');
        window.location.href='../layanan/edit_sarana_prasarana.php';
      </script>";
exit();

?>
