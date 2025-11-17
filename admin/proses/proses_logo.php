<?php
session_start();
include "../../config/koneksi.php";

// Cek apakah tombol submit ditekan
if (isset($_POST["submit"])) {

    // Ambil ID logo dari form
    $id_logo1 = $_POST['id_logo1'];
    $id_logo2 = $_POST['id_logo2'];

    // Ambil id_user dari session (fallback ke 1)
    $id_user = $_SESSION['id_user'] ?? 1;

    // Tentukan folder upload
    $targetDir = "../../uploads/logo/";

    // Ekstensi file yang diperbolehkan
    $allowedExt = array('jpg', 'jpeg', 'png', 'gif', 'svg');

    // =========================================================
    // 1. PROSES LOGO 1
    // =========================================================

    $file1 = $_FILES['file_logo1']['name'];
    $tmp1  = $_FILES['file_logo1']['tmp_name'];
    $size1 = $_FILES['file_logo1']['size'];

    if (!empty($file1)) {

        // Ambil media_path lama (untuk dihapus)
        $oldQuery1 = pg_query($conn, "SELECT media_path FROM logo WHERE id_logo = $id_logo1");
        $oldData1  = pg_fetch_assoc($oldQuery1);
        $oldFoto1  = $oldData1['media_path'];

        $ext1 = strtolower(pathinfo($file1, PATHINFO_EXTENSION));

        if (!in_array($ext1, $allowedExt)) {
            echo "<script>alert('Logo 1 gagal diubah! Format tidak valid.');
                  window.location='../profil/edit_logo.php';</script>";
            exit();
        }

        if ($size1 > 2 * 1024 * 1024) {
            echo "<script>alert('Logo 1 gagal diubah! File lebih dari 2MB.');
                  window.location='../profil/edit_logo.php';</script>";
            exit();
        }

        // Nama file baru
        $newFile1 = "logo1_" . time() . "." . $ext1;
        $dest1 = $targetDir . $newFile1;

        if (move_uploaded_file($tmp1, $dest1)) {

            // Hapus foto lama jika ada
            if (!empty($oldFoto1) && file_exists($targetDir . $oldFoto1)) {
                unlink($targetDir . $oldFoto1);
            }

            // Update media_path logo 1
            $query1 = "UPDATE logo SET media_path = $1, id_user = $2 WHERE id_logo = $3";
            pg_query_params($conn, $query1, array($newFile1, $id_user, $id_logo1));

        } else {
            echo "<script>alert('Gagal upload Logo 1!');
                  window.location='../profil/edit_logo.php';</script>";
            exit();
        }
    }

    // =========================================================
    // 2. PROSES LOGO 2
    // =========================================================

    $file2 = $_FILES['file_logo2']['name'];
    $tmp2  = $_FILES['file_logo2']['tmp_name'];
    $size2 = $_FILES['file_logo2']['size'];

    if (!empty($file2)) {

        // Ambil media_path lama (untuk dihapus)
        $oldQuery2 = pg_query($conn, "SELECT media_path FROM logo WHERE id_logo = $id_logo2");
        $oldData2  = pg_fetch_assoc($oldQuery2);
        $oldFoto2  = $oldData2['media_path'];

        $ext2 = strtolower(pathinfo($file2, PATHINFO_EXTENSION));

        if (!in_array($ext2, $allowedExt)) {
            echo "<script>alert('Logo 2 gagal diubah! Format tidak valid.');
                  window.location='../profil/edit_logo.php';</script>";
            exit();
        }

        if ($size2 > 2 * 1024 * 1024) {
            echo "<script>alert('Logo 2 gagal diubah! File lebih dari 2MB.');
                  window.location='../profil/edit_logo.php';</script>";
            exit();
        }

        // Nama file baru
        $newFile2 = "logo2_" . time() . "." . $ext2;
        $dest2 = $targetDir . $newFile2;

        if (move_uploaded_file($tmp2, $dest2)) {

            // Hapus foto lama
            if (!empty($oldFoto2) && file_exists($targetDir . $oldFoto2)) {
                unlink($targetDir . $oldFoto2);
            }

            // Update media_path logo 2
            $query2 = "UPDATE logo SET media_path = $1, id_user = $2 WHERE id_logo = $3";
            pg_query_params($conn, $query2, array($newFile2, $id_user, $id_logo2));

        } else {
            echo "<script>alert('Gagal upload Logo 2!');
                  window.location='../profil/edit_logo.php';</script>";
            exit();
        }
    }

    // =========================================================
    // SEMUA PROSES BERHASIL
    // =========================================================
    echo "<script>
            alert('Logo berhasil diperbarui!');
            window.location='../profil/edit_logo.php';
          </script>";
    exit();

} else {
    echo "<script>alert('Akses tidak valid!');
          window.location='../profil/edit_logo.php';</script>";
    exit();
}
?>
