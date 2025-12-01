<?php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// ID user (fallback ke 1 jika tidak login)
$id_user = $_SESSION['id_user'] ?? 1;

// Direktori upload
$uploadDir = '../../uploads/galeri/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
$id_page = ensure_page_exists($conn, 'galeri_galeri');

if (!$id_page) {
    echo "<script>
            alert('Gagal membuat atau mendapatkan halaman Galeri!');
            window.location.href = '../galeri/edit_galeri.php';
          </script>";
    exit();
}

//Kelola konten halaman Galeri
if (isset($_POST['submit_judul_deskripsi_galeri'])) {
    //Ambil input dari form
    $judul_galeri   = ($_POST['judul_galeri']);
    $deskripsi_galeri = ($_POST['deskripsi_galeri']);

    // Gunakan helper untuk upsert content dengan section_title dan section_description
    $resultJudulGaleri = upsert_page_content($conn, $id_page, 'section_title', $judul_galeri, $id_user);
    $resultDeskripsiGaleri = upsert_page_content($conn, $id_page, 'section_description', $deskripsi_galeri, $id_user);

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
    exit();   
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
        if (!move_uploaded_file($tmpFile, $uploadDir . $newName)) {
            echo "<script>alert('Gagal mengupload gambar!'); window.history.back();</script>";
            exit();
        }

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
            // Hapus file yang sudah diupload jika gagal insert
            unlink($uploadDir . $newName);
            echo "<script>
                    alert('Gagal menambah galeri!');
                    window.location.href = '../galeri/edit_galeri.php';
                  </script>";
        }
    } else {
        echo "<script>alert('Gambar wajib diupload!'); window.history.back();</script>";
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

// Hapus Galeri
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

    // Hapus dari database
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
    exit();
}

// Jika tidak ada action yang valid
echo "<script>
        alert('Aksi tidak dikenali!');
        window.location.href = '../galeri/edit_galeri.php';
      </script>";
exit();
   
?>