<?php
session_start();
include "../../config/koneksi.php";
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// Ambil id_user dari session (fallback ke 1)
$id_user = $_SESSION['id_user'] ?? 1;

// GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
$id_page = ensure_page_exists($conn, 'profil_logo');

if (!$id_page) {
    echo "<script>
            alert('Gagal membuat atau mendapatkan halaman Logo!');
            window.location.href = '../profil/edit_logo.php';
          </script>";
    exit();
}

if (isset($_POST['submit_judul_deskripsi_logo'])) {
    //Ambil input dari form
    $judul_logo   = ($_POST['judul_logo']);
    $deskripsi_logo = ($_POST['deskripsi_logo']);

    // Gunakan helper untuk upsert content dengan section_title dan section_description
    $resultJudulLogo = upsert_page_content($conn, $id_page, 'section_title', $judul_logo, $id_user);
    $resultDeskripsiLogo = upsert_page_content($conn, $id_page, 'section_description', $deskripsi_logo, $id_user);

    // Cek hasil
    if ($resultJudulLogo && $resultDeskripsiLogo) {
        echo "<script>
                alert('Judul dan Deskripsi Logo berhasil diperbarui!');
                window.location.href = '../profil/edit_logo.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal memperbarui Judul dan Deskripsi Logo!');
                window.location.href = '../profil/edit_logo.php';
              </script>";
        exit();
    }
       
// UPDATE LOGO 1 & LOGO 2
    
 } elseif (isset($_POST['submit_logo'])) {

        $id_logo1 = $_POST['id_logo1'];
        $id_logo2 = $_POST['id_logo2'];

        // folder peyimpanan logo
        $uploadDir = '../../uploads/logo/';

        // Validasi format file
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg'];
        $allowedMime = [
            'image/png',
            'image/jpeg',
            'image/svg+xml'
        ];

        // LOGO 1
        if (!empty($_FILES['file_logo1']['name'])) {

            $file1 = $_FILES['file_logo1']['name'];
            $tmp1  = $_FILES['file_logo1']['tmp_name'];
            $fileType1 = mime_content_type($tmp1);
            $size1 = $_FILES['file_logo1']['size'];

            // Ambil Ekstensi
            $fileExt1 = strtolower(pathinfo($file1, PATHINFO_EXTENSION));

            // Validasi ekstensi dan MIME
            if (!in_array($fileExt1, $allowedExtensions) || !in_array($fileType1, $allowedMime)) {
                echo "<script>alert('Logo 1 gagal diubah! Format tidak valid. Gunakan PNG, JPG, JPEG, atau SVG.');
                      window.history.back();</script>";
                exit();
            }

            // Validasi ukuran file (maks 3MB)
            if ($size1 > 3 * 1024 * 1024) {
                echo "<script>alert('Logo 1 gagal diubah! File lebih dari 3MB.');
                      window.history.back();</script>";
                exit();
            }
            
            // Ambil data lama
            $checkLogo1 = pg_query($conn,
                "SELECT id_logo, media_path FROM logo WHERE nama_logo = 'logo_utama' LIMIT 1"
            );

            $rowLogo1 = pg_fetch_assoc($checkLogo1);
            $newFile1 = time() . "_" . $file1;

            // Upload file baru
            move_uploaded_file($tmp1, $uploadDir . $newFile1);

            //jika ada data lama → hapus file lama
            if ($rowLogo1) {
                $oldFile1 = $rowLogo1['media_path'];
                if (!empty($oldFile1) && file_exists($uploadDir . $oldFile1)) {
                    unlink($uploadDir . $oldFile1);
                }
            }

            // Simpan nama file baru ke database
            if (pg_num_rows($checkLogo1) > 0) {
                // Update jika sudah ada
                pg_query_params(
                    $conn,
                    "UPDATE logo SET media_path = $1, id_user = $2 WHERE id_logo = $3",
                    array($newFile1, $id_user, $rowLogo1['id_logo'])
                );
                // Insert jika belum ada
            } else {
                pg_query_params(
                    $conn,
                    "INSERT INTO logo (nama_logo, media_path, id_user)
                    VALUES ('logo_utama', $1, $2)",
                    array($newFile1, $id_user)
                );
            }
        }

        // LOGO 2
        if (!empty($_FILES['file_logo2']['name'])) {

            $file2 = $_FILES['file_logo2']['name'];
            $tmp2  = $_FILES['file_logo2']['tmp_name'];
            $fileType2 = mime_content_type($tmp2);
            $size2 = $_FILES['file_logo2']['size'];

            // Ambil Ekstensi
            $fileExt2 = strtolower(pathinfo($file2, PATHINFO_EXTENSION));

            // Validasi ekstensi dan MIME
            if (!in_array($fileExt2, $allowedExtensions) || !in_array($fileType2, $allowedMime)) {
                echo "<script>alert('Logo 2 gagal diubah! Format tidak valid. Gunakan PNG, JPG, JPEG, atau SVG.');
                      window.history.back();</script>";
                exit();
            }
            // Validasi ukuran maksimal 3MB
            if ($size2 > 3 * 1024 * 1024) {
                echo "<script>alert('Logo 2 gagal diubah! File lebih dari 3MB.');
                      window.history.back();</script>";
                exit();
            }

            // Ambil data lama
            $checkLogo2 = pg_query($conn,
                "SELECT id_logo, media_path FROM logo WHERE nama_logo = 'logo_deskripsi' LIMIT 1"
            );
            $rowLogo2 = pg_fetch_assoc($checkLogo2);
            $newFile2 = time() . "_" . $file2;

            // Upload file baru
            move_uploaded_file($tmp2, $uploadDir . $newFile2);

            //jika ada data lama → hapus file lama
            if ($rowLogo2) {
                $oldFile2 = $rowLogo2['media_path'];
                if (!empty($oldFile2) && file_exists($uploadDir . $oldFile2)) {
                    unlink($uploadDir . $oldFile2);
                }
            }
            // Simpan nama file baru ke database
            if (pg_num_rows($checkLogo2) > 0) {
                // Update jika sudah ada
                pg_query_params(
                    $conn,
                    "UPDATE logo SET media_path = $1, id_user = $2 WHERE id_logo = $3",
                    array($newFile2, $id_user, $rowLogo2['id_logo'])
                );
            } else {
                // Insert jika belum ada
                pg_query_params(
                    $conn,
                    "INSERT INTO logo (nama_logo, media_path, id_user)
                    VALUES ('logo_deskripsi', $1, $2)",
                    array($newFile2, $id_user)
                );
            }
        }  
        
        // Tampilkan alert setelah kedua logo diproses
        echo "<script>
                alert('Logo berhasil diperbarui!');
                window.location.href = '../profil/edit_logo.php';
              </script>";
        exit();

} else {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../profil/edit_logo.php';
          </script>";
    exit();
}
?>