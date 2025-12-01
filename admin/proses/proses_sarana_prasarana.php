<?php
session_start();
include "../../config/koneksi.php";
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// ID user (fallback ke 1)
$id_user = $_SESSION['id_user'] ?? 1;

// Direktori upload
$uploadDir = '../../uploads/sarana/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
$id_page = ensure_page_exists($conn, 'layanan_sarana');

if (!$id_page) {
    echo "<script>
            alert('Gagal membuat atau mendapatkan halaman Sarana dan Prasarana!');
            window.location.href = '../layanan/edit_sarana_prasarana.php';
          </script>";
    exit();
}

//Kelola konten halaman sarana dan prasarana
if (isset($_POST['submit_judul_deskripsi_sarana'])) {
    //Ambil input dari form
    $judul_sarana     = $_POST['judul_sarana'];
    $deskripsi_sarana = $_POST['deskripsi_sarana'];

    // Gunakan helper untuk upsert content dengan section_title dan section_description
    $resultJudulSarana = upsert_page_content($conn, $id_page, 'section_title', $judul_sarana, $id_user);
    $resultDeskripsiSarana = upsert_page_content($conn, $id_page, 'section_description', $deskripsi_sarana, $id_user);

    // Cek hasil
    if ($resultJudulSarana && $resultDeskripsiSarana) {
        echo "<script>
                alert('Konten halaman Sarana dan Prasarana berhasil diperbarui!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
                </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman Sarana dan Prasarana!');
                window.location.href = '../layanan/edit_sarana_prasarana.php';
                </script>"; 
    }
    exit();   
}

// Tambah Sarana
elseif (isset($_POST['tambah_sarana'])) {

    $nama_sarana = $_POST['nama_sarana'];

    if (empty($nama_sarana)) {
        echo "<script>alert('Nama sarana wajib diisi!'); window.history.back();</script>";
        exit();
    }

    // Proses upload Gambar
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

        //Upload file
        if (!move_uploaded_file($tmpFile, $uploadDir . $newName)) {
            echo "<script>alert('Gagal mengupload gambar!'); window.history.back();</script>";
            exit();
        }

        // Simpan data sarana ke database
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
    } else {
        echo "<script>alert('Gambar wajib diupload!'); window.history.back();</script>";
    }
    exit();
}

// Update Sarana
elseif (isset($_POST['edit_sarana'])) {

    $id_sarana   = $_POST['id_sarana'];
    $nama_sarana = $_POST['nama_sarana'];

    if (empty($nama_sarana)) {
        echo "<script>alert('Nama sarana wajib diisi!'); window.history.back();</script>";
        exit();
    }

    // Ambil data lama
    $sqlOld = "SELECT media_path FROM sarana WHERE id_sarana = $1 LIMIT 1";
    $resultOld = pg_query_params($conn, $sqlOld, array($id_sarana));
    
    if (!$resultOld || pg_num_rows($resultOld) === 0) {
        echo "<script>alert('Data sarana tidak ditemukan!'); window.history.back();</script>";
        exit();
    }
    
    $old = pg_fetch_assoc($resultOld);
    $oldImage = $old['media_path'];
    $newName = $oldImage;

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
            echo "<script>alert('Gagal: Ukuran maksimal 3MB!'); window.history.back();</script>";
            exit();
        }

        //Generate nama file baru
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
    
    // Query Update
    $sqlUpdate = "UPDATE sarana
                  SET nama_sarana=$1, media_path=$2, id_user=$3
                  WHERE id_sarana=$4";

    $params = array (
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
    exit();
}

// Hapus Sarana
elseif (isset($_POST['hapus_sarana'])) {

    $id_sarana = $_POST['id_sarana'];

    // Ambil gambar lama
    $sqlGet = "SELECT media_path FROM sarana WHERE id_sarana = $1 LIMIT 1";
    $resultGet = pg_query_params($conn, $sqlGet, array($id_sarana));
    
    if (!$resultGet || pg_num_rows($resultGet) === 0) {
        echo "<script>alert('Data sarana tidak ditemukan!'); window.history.back();</script>";
        exit();
    }
    
    $data = pg_fetch_assoc($resultGet);
    
    if ($data) {
        $fileName = $data['media_path'];
        $filePath = "../../uploads/sarana/" . $fileName;

        // Hapus file jika ada
        if (!empty($fileName) && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Hapus database
    $sqlDelete = "DELETE FROM sarana WHERE id_sarana = $1";
    $resultDelete = pg_query_params($conn, $sqlDelete, array($id_sarana));

    if ($resultDelete) {
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
    exit();
}

// Jika tidak ada action yang valid
echo "<script>
        alert('Aksi tidak dikenali!');
        window.location.href = '../layanan/edit_sarana_prasarana.php';
      </script>";
exit();
?>