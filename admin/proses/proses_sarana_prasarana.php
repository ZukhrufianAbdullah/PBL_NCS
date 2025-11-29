<?php
session_start();
include "../../config/koneksi.php";

// Ambil id_user (fallback ke 1 jika tidak login)
$id_user = $_SESSION['id_user'] ?? 1;

// Direktori upload
$uploadDir = '../../uploads/sarana_prasarana/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// ambil id_page untuk halaman 'layanan_sarana'
$sqlPage = "SELECT id_page FROM pages WHERE nama = 'layanan_sarana' LIMIT 1";
$resultPage = pg_query($conn, $sqlPage);

if (!$resultPage || pg_num_rows($resultPage) === 0) {
    echo "<script>
                alert('Halaman Sarana dan Prasarana tidak ditemukan di tabel pages!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
                </script>";
    exit();
}
$page = pg_fetch_assoc($resultPage);
$id_page = $page['id_page'];

//Kelola konten halaman sarana prasarana
if (isset($_POST['submit_judul_deskripsi_sarana'])) {
    //Ambil input dari form
    $judul_sarana   = ($_POST['judul_sarana']);
    $deskripsi_sarana = ($_POST['deskripsi_sarana']);

    //Update atau Insert judul sarana
    $checkJudulSarana = "SELECT id_page_content FROM page_content
                WHERE id_page = $1 AND content_key = 'judul_sarana' LIMIT 1";
    $checkResultJudulSarana = pg_query_params($conn, $checkJudulSarana, array($id_page));

    if (pg_num_rows($checkResultJudulSarana) > 0) {
        // UPDATE
        $updateJudulSarana = "UPDATE page_content
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = 'judul_sarana'";
        $resultJudulSarana = pg_query_params($conn, $updateJudulSarana, array($judul_sarana, $id_user, $id_page));
    } else {
        // INSERT
        $insertJudulSarana = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, 'judul_sarana', 'text', $2, $3)";
        $resultJudulSarana = pg_query_params($conn, $insertJudulSarana, array($id_page, $judul_sarana, $id_user));
    }

    // Update atau Insert deskripsi sarana
    $checkDeskripsiSarana = "SELECT id_page_content FROM page_content
                WHERE id_page = $1 AND content_key = 'deskripsi_sarana' LIMIT 1";
    $checkResultDeskripsiSarana = pg_query_params($conn, $checkDeskripsiSarana, array($id_page));

    if (pg_num_rows($checkResultDeskripsiSarana) > 0) {
        // UPDATE
        $updateDeskripsiSarana = "UPDATE page_content
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = 'deskripsi_sarana'";
        $resultDeskripsiSarana = pg_query_params($conn, $updateDeskripsiSarana, array($deskripsi_sarana, $id_user, $id_page));
    } else {
        // INSERT
        $insertDeskripsiSarana = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, 'deskripsi_sarana', 'text', $2, $3)";
        $resultDeskripsiSarana = pg_query_params($conn, $insertDeskripsiSarana, array($id_page, $deskripsi_sarana, $id_user));
    }

    // Cek hasil
    if ($resultJudulSarana && $resultDeskripsiSarana) {
        echo "<script>
                alert('Konten Sarana dan Prasarana berhasil diperbarui!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
                </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman Sarana dan Prasarana!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
                </script>";
    }
}
// Tambah sarana
elseif (isset($_POST['tambah_sarana'])) {

    $nama_sarana = $_POST['nama_sarana'];

    // Jika file gambar diupload
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
        move_uploaded_file($tmpFile, $uploadDir . $newName);

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
            echo "<script>
                    alert('Gagal menambah Sarana dan Prasarana!');
                    window.location.href = '../layanan/edit_sarana_prasarana.php';
                  </script>";
        }
    }
    exit();
}
//edit sarana
elseif (isset($_POST['edit_sarana'])) {

    $id_sarana   = $_POST['id_sarana'];
    $nama_sarana = $_POST['nama_sarana'];

    // Ambil data lama
    $sqlOld = "SELECT media_path FROM sarana WHERE id_sarana = $1 LIMIT 1";
    $resultOld = pg_query_params($conn, $sqlOld, array($id_sarana));
    $old = pg_fetch_assoc($resultOld);
    $oldImage = $old['media_path'];

    $newImage = $oldImage;

    // Jika ada upload baru
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
            if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
                unlink($uploadDir . $oldImage);
            }
        } else {
            echo "<script>alert('Gagal upload gambar baru!'); window.history.back();</script>";
            exit();
        }
    }

    $sqlUpdate = "UPDATE sarana
                  SET nama_sarana=$1, media_path=$2, id_user=$3
                  WHERE id_sarana=$4";

    $params = array(
        $nama_sarana,
        $newName,
        $id_user,
        $id_sarana
    );
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
    exit;
}

// hapus sarana
elseif (isset($_POST['hapus_sarana'])) {

    $id_sarana = $_POST['id_sarana'];

    // Ambil gambar lama
    $sqlGet = "SELECT media_path FROM sarana WHERE id_sarana = $1 LIMIT 1";
    $resultGet = pg_query_params($conn, $sqlGet, array($id_sarana));
    $data = pg_fetch_assoc($resultGet);

    if ($data) {
        $fileName = $data['media_path'];
        $filePath = "../../uploads/sarana_prasarana/" . $fileName;

        // 2. Hapus file jika ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Hapus database
    $sqlDelete = "DELETE FROM sarana WHERE id_sarana = $1";
    $resultDelete = pg_query_params($conn, $sqlDelete, array($id_sarana));

    if ($resultUpdate) {
        echo "<script>
                    alert('Sarana dan Prasarana berhasil dihapus beserta gambarnya!');
                    window.location.href = '../layanan/edit_sarana_prasarana.php';
                  </script>";
    } else {
        echo "<script>
                    alert('Gagal menghapus Sarana dan Prasarana!');
                    window.location.href = '../layanan/edit_sarana_prasarana.php';
                  </script>";
    } 
    exit;
}
