<?php
session_start();
include '../../config/koneksi.php';

// ID user (fallback ke 1 jika tidak login)
$id_user = $_SESSION['id_user'] ?? 1;

// Direktori upload
$uploadDir = '../../uploads/galeri/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// ambil id_page untuk halaman 'galeri_galeri'
    $sqlPage = "SELECT id_page FROM pages WHERE nama = 'galeri_galeri' LIMIT 1";
    $resultPage = pg_query($conn, $sqlPage);

    if (!$resultPage || pg_num_rows($resultPage) === 0) {
        echo "<script>
                alert('Halaman Galeri tidak ditemukan di tabel pages!');
                window.location.href = '../galeri/edit_galeri.php';
                </script>";
        exit();
    }
    $page = pg_fetch_assoc($resultPage);
    $id_page = $page['id_page'];

//Kelola konten halaman Galeri
if (isset($_POST['submit_judul_deskripsi_galeri'])) {
    //Ambil input dari form
    $judul_galeri   = ($_POST['judul_galeri']);
    $deskripsi_galeri = ($_POST['deskripsi_galeri']);

    //Update atau Insert judul galeri
    $checkJudulGaleri = "SELECT id_page_content FROM page_content
                WHERE id_page = $1 AND content_key = 'judul_galeri' LIMIT 1";
    $checkResultJudulGaleri = pg_query_params($conn, $checkJudulGaleri, array($id_page));

    if (pg_num_rows($checkResultJudulGaleri) > 0) {
        // UPDATE
        $updateJudulGaleri = "UPDATE page_content
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = 'judul_galeri'";
        $resultJudulGaleri = pg_query_params($conn, $updateJudulGaleri, array($judul_galeri, $id_user, $id_page));
    } else {
        // INSERT
        $insertJudulGaleri = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, 'judul_galeri', 'text', $2, $3)";
        $resultJudulGaleri = pg_query_params($conn, $insertJudulGaleri, array($id_page, $judul_galeri, $id_user));
    }

    // Update atau Insert deskripsi galeri
    $checkDeskripsiGaleri = "SELECT id_page_content FROM page_content
                WHERE id_page = $1 AND content_key = 'deskripsi_galeri' LIMIT 1";
    $checkResultDeskripsiGaleri = pg_query_params($conn, $checkDeskripsiGaleri, array($id_page));

    if (pg_num_rows($checkResultDeskripsiGaleri) > 0) {
        // UPDATE
        $updateDeskripsiGaleri = "UPDATE page_content
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = 'deskripsi_galeri'";
        $resultDeskripsiGaleri = pg_query_params($conn, $updateDeskripsiGaleri, array($deskripsi_galeri, $id_user, $id_page));
    } else {
        // INSERT
        $insertDeskripsiGaleri = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, 'deskripsi_galeri', 'text', $2, $3)";
        $resultDeskripsiGaleri = pg_query_params($conn, $insertDeskripsiGaleri, array($id_page, $deskripsi_galeri, $id_user));
    }

    // Cek hasil
    if ($resultJudulGaleri && $resultDeskripsiGaleri) {
        echo "<script>
                alert('Konten halaman Galeri berhasil diperbarui!');
                window.location.href = '../galeri/edit_galeri.php';
                </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman Galeri!');
                window.location.href = '../galeri/edit_galeri.php';
                </script>"; 
    }    
}
// Tambah Galeri
elseif (isset($_POST['tambah_galeri'])) {
    $judul       = ($_POST['judul']);
    $deskripsi   = ($_POST['deskripsi']);
    $tanggal     = ($_POST['tanggal']);

    // Proses upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg'];
        $allowedMime = ['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'];
        
        $fileName = $_FILES['gambar']['name'];
        $tmpFile  = $_FILES['gambar']['tmp_name'];
        $fileType = mime_content_type($tmpFile);
        $fileSize = $_FILES['gambar']['size'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validasi ekstensi file
        if (!in_array($fileExt, $allowedExtensions)) {
            echo "<script>alert('Gagal: Format harus PNG/JPG/JPEG/SVG!'); window.history.back();</script>";
            exit();
        }

        // Validasi MIME file
        if (!in_array($fileType, $allowedMime)) {
            echo "<script>alert('Gagal: File yang diupload bukan gambar valid!'); window.history.back();</script>";
            exit();
        }

        // Validasi ukuran maksimal 3MB (opsional)
        if ($fileSize > 3 * 1024 * 1024) {
            echo "<script>alert('Gagal: Ukuran file maksimal 3MB!'); window.history.back();</script>";
            exit();
        }

        $newName = time() . "_" . $fileName;

        // Upload file
        move_uploaded_file($tmpFile, $uploadDir . $newName);

        // Simpan data galeri ke database
       $sqlInsert = "
            INSERT INTO galeri (judul, deskripsi, tanggal, media_path, id_user)
            VALUES ($1, $2, $3, $4, $5)
        ";

        $resultInsert = pg_query_params($conn, $sqlInsert, array(
            $judul,
            $deskripsi,
            $tanggal,
            $newName,
            $id_user
        ));

        if ($resultInsert) {
            echo "<script>
                    alert('Galeri berhasil ditambahkan!');
                    window.location.href = '../galeri/edit_galeri.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menambah galeri!');
                    window.location.href = '../galeri/edit_galeri.php';
                  </script>";
        }
    }
    exit();
}

// Update Galeri
elseif (isset($_POST['edit_galeri'])) {
    $id_galeri   = $_POST['id_galeri'];
    $judul       = ($_POST['judul']);
    $deskripsi   = ($_POST['deskripsi']);
    $tanggal     = ($_POST['tanggal']);

    // Ambil data lama galeri (termasuk nama file gambar)
    $sqlOld = "SELECT media_path FROM galeri WHERE id_galeri = $1 LIMIT 1";
    $resultOld = pg_query_params($conn, $sqlOld, array($id_galeri));
    $oldData = pg_fetch_assoc($resultOld);
    $oldImage = $oldData['media_path'];
    
    $newName = $oldImage; 

    // Jika upload gambar baru
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
            echo "<script>alert('Gagal: Ukuran maksimal 3MB!'); window.history.back();</script>";
            exit();
        }

        // Generate nama file baru
        $newName = time() . "_" . $fileName;

        // Upload file
        if (move_uploaded_file($tmpFile, $uploadDir . $newName)) {

            // Hapus gambar lama jika ada
            if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
                unlink($uploadDir . $oldImage);
            }

        } else {
            echo "<script>alert('Gagal upload gambar baru!'); window.history.back();</script>";
            exit();
        }
    }

    // Query UPDATE
    $sqlUpdate = "
        UPDATE galeri
        SET judul = $1,
            deskripsi = $2,
            tanggal = $3,
            media_path = $4,
            id_user = $5
        WHERE id_galeri = $6
    ";

    $params = array(
        $judul,
        $deskripsi,
        $tanggal,
        $newName,   
        $id_user,
        $id_galeri
    );

    $resultUpdate = pg_query_params($conn, $sqlUpdate, $params);

    if ($resultUpdate) {
        echo "<script>
                alert('Galeri berhasil diperbarui!');
                window.location.href = '../galeri/edit_galeri.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui galeri!');
                window.location.href = '../galeri/edit_galeri.php';
              </script>";
    }
    exit();
}


// Hapus Agenda
elseif (isset($_POST['hapus'])) {

    $id_galeri = $_POST['id_galeri'];

    // Ambil nama file dari database
    $sqlGet = "SELECT media_path FROM galeri WHERE id_galeri = $1 LIMIT 1";
    $resultGet = pg_query_params($conn, $sqlGet, array($id_galeri));
    $data = pg_fetch_assoc($resultGet);

    if ($data) {
        $fileName = $data['media_path'];
        $filePath = "../../uploads/galeri/" . $fileName;

        // Hapus file jika ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // 3. Hapus database
    $sqlDelete = "DELETE FROM galeri WHERE id_galeri = $1";
    $resultDelete = pg_query_params($conn, $sqlDelete, array($id_galeri));

    if ($resultDelete) {
        echo "<script>
                alert('Galeri berhasil dihapus beserta gambarnya!');
                window.location.href = '../galeri/edit_galeri.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus galeri!');
                window.location.href = '../galeri/edit_galeri.php';
              </script>";
    }
}
   
?>
