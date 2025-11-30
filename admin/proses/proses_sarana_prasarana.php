<?php
session_start();
include "../../config/koneksi.php";

// Ambil id_user (fallback ke 1 jika tidak login)
$id_user = $_SESSION['id_user'] ?? 1;

// Direktori upload
$uploadDir = '../../uploads/sarana_prasarana/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Helper function untuk ensure page exists
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

// Helper function untuk upsert page content
function upsert_sarana_page_content($conn, int $pageId, string $contentKey, string $value, int $userId): void
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
   0) UPDATE SECTION CONTENT SARANA
   =========================================================== */
if (isset($_POST['edit_section_content'])) {

    $section_title       = trim($_POST['section_title'] ?? '');
    $section_description = trim($_POST['section_description'] ?? '');
    $page_key            = "layanan_sarana";

    // Pastikan page exists (auto-create jika belum ada)
    $pageId = ensure_page($conn, $page_key);
    
    // Update atau insert section title
    upsert_sarana_page_content($conn, $pageId, 'section_title', $section_title, $id_user);
    
    // Update atau insert section description  
    upsert_sarana_page_content($conn, $pageId, 'section_description', $section_description, $id_user);

    echo "<script>
            alert('Konten halaman Sarana & Prasarana berhasil diperbarui!');
            window.location.href = '../layanan/edit_sarana_prasarana.php';
          </script>";
    exit();
}

/* ===========================================================
   1) TAMBAH SARANA
   =========================================================== */
if (isset($_POST['tambah_sarana'])) {

    $nama_sarana = trim($_POST['nama_sarana'] ?? '');

    if (empty($nama_sarana)) {
        echo "<script>alert('Nama sarana wajib diisi!'); window.history.back();</script>";
        exit();
    }

    // Validasi file upload
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Gambar wajib diupload!'); window.history.back();</script>";
        exit();
    }

    $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg'];
    $allowedMime = ['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'];

    $fileName = $_FILES['gambar']['name'];
    $tmpFile  = $_FILES['gambar']['tmp_name'];
    $fileType = mime_content_type($tmpFile);
    $fileSize = $_FILES['gambar']['size'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedExtensions)) {
        echo "<script>alert('Gagal: Format harus PNG/JPG/JPEG/SVG!'); window.history.back();</script>";
        exit();
    }

    if (!in_array($fileType, $allowedMime)) {
        echo "<script>alert('Gagal: File bukan gambar valid!'); window.history.back();</script>";
        exit();
    }

    if ($fileSize > 3 * 1024 * 1024) {
        echo "<script>alert('Ukuran maksimal 3MB!'); window.history.back();</script>";
        exit();
    }

    // Generate new filename and upload
    $newName = time() . "_" . $fileName;
    if (!move_uploaded_file($tmpFile, $uploadDir . $newName)) {
        echo "<script>alert('Gagal mengupload gambar!'); window.history.back();</script>";
        exit();
    }

    // Insert ke database
    $sqlInsert = "INSERT INTO sarana (nama_sarana, media_path, id_user)
                  VALUES ($1, $2, $3)";

    $resultInsert = pg_query_params($conn, $sqlInsert, array(
        $nama_sarana,
        $newName,
        $id_user
    ));

    if ($resultInsert) {
        echo "<script>
                alert('Sarana dan Prasarana berhasil ditambahkan!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
              </script>";
    } else {
        // Hapus file yang sudah diupload jika gagal insert
        unlink($uploadDir . $newName);
        echo "<script>
                alert('Gagal menambah Sarana dan Prasarana!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
              </script>";
    }
    exit();
}

/* ===========================================================
   2) EDIT SARANA
   =========================================================== */
if (isset($_POST['edit_sarana'])) {

    $id_sarana   = (int) ($_POST['id_sarana'] ?? 0);
    $nama_sarana = trim($_POST['nama_sarana'] ?? '');

    if ($id_sarana <= 0) {
        echo "<script>alert('ID sarana tidak valid!'); window.history.back();</script>";
        exit();
    }

    if (empty($nama_sarana)) {
        echo "<script>alert('Nama sarana wajib diisi!'); window.history.back();</script>";
        exit();
    }

    // Ambil data lama untuk mendapatkan nama file lama
    $sqlOld = "SELECT media_path FROM sarana WHERE id_sarana = $1 LIMIT 1";
    $resultOld = pg_query_params($conn, $sqlOld, array($id_sarana));
    
    if (!$resultOld || pg_num_rows($resultOld) === 0) {
        echo "<script>alert('Data sarana tidak ditemukan!'); window.history.back();</script>";
        exit();
    }
    
    $old = pg_fetch_assoc($resultOld);
    $oldImage = $old['media_path'];
    $newImage = $oldImage;

    // Jika ada upload file baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {

        $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg'];
        $allowedMime = ['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'];

        $fileName = $_FILES['gambar']['name'];
        $tmpFile  = $_FILES['gambar']['tmp_name'];
        $fileType = mime_content_type($tmpFile);
        $fileSize = $_FILES['gambar']['size'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExtensions)) {
            echo "<script>alert('Gagal: Format harus PNG/JPG/JPEG/SVG!'); window.history.back();</script>";
            exit();
        }

        if (!in_array($fileType, $allowedMime)) {
            echo "<script>alert('Gagal: File bukan gambar valid!'); window.history.back();</script>";
            exit();
        }

        if ($fileSize > 3 * 1024 * 1024) {
            echo "<script>alert('Ukuran maksimal 3MB!'); window.history.back();</script>";
            exit();
        }

        $newName = time() . "_" . $fileName;

        if (move_uploaded_file($tmpFile, $uploadDir . $newName)) {
            // Hapus file lama jika ada
            if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
                unlink($uploadDir . $oldImage);
            }
            $newImage = $newName;
        } else {
            echo "<script>alert('Gagal upload gambar baru!'); window.history.back();</script>";
            exit();
        }
    }

    // Update database
    $sqlUpdate = "UPDATE sarana
                  SET nama_sarana = $1, media_path = $2, id_user = $3
                  WHERE id_sarana = $4";

    $params = array($nama_sarana, $newImage, $id_user, $id_sarana);
    $resultUpdate = pg_query_params($conn, $sqlUpdate, $params);

    if ($resultUpdate) {
        echo "<script>
                alert('Sarana dan Prasarana berhasil diperbarui!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui Sarana dan Prasarana!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
              </script>";
    } 
    exit();
}

/* ===========================================================
   3) HAPUS SARANA
   =========================================================== */
if (isset($_POST['hapus_sarana'])) {

    $id_sarana = (int) ($_POST['id_sarana'] ?? 0);

    if ($id_sarana <= 0) {
        echo "<script>alert('ID sarana tidak valid!'); window.history.back();</script>";
        exit();
    }

    // Ambil data untuk mendapatkan nama file
    $sqlGet = "SELECT media_path FROM sarana WHERE id_sarana = $1 LIMIT 1";
    $resultGet = pg_query_params($conn, $sqlGet, array($id_sarana));
    
    if (!$resultGet || pg_num_rows($resultGet) === 0) {
        echo "<script>alert('Data sarana tidak ditemukan!'); window.history.back();</script>";
        exit();
    }
    
    $data = pg_fetch_assoc($resultGet);
    $fileName = $data['media_path'];

    // Hapus file gambar jika ada
    if (!empty($fileName)) {
        $filePath = $uploadDir . $fileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Hapus dari database
    $sqlDelete = "DELETE FROM sarana WHERE id_sarana = $1";
    $resultDelete = pg_query_params($conn, $sqlDelete, array($id_sarana));

    if ($resultDelete) {
        echo "<script>
                alert('Sarana dan Prasarana berhasil dihapus!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus Sarana dan Prasarana!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
              </script>";
    } 
    exit();
}

/* ===========================================================
   TIDAK ADA AKSI YANG VALID
   =========================================================== */
echo "<script>
        alert('Aksi tidak valid!');
        window.location.href = '../layanan/edit_sarana_prasarana.php';
      </script>";
exit();
?>