<?php
session_start();
include "../../config/koneksi.php";

// Cek apakah tombol submit ditekan
if (isset($_POST['submit'])) {

    // Ambil id_user dari session (fallback ke 1)
    $id_user = $_SESSION['id_user'] ?? 1;

    // Ambil tipe edit (logo atau page_content)
    $edit_type = $_POST['edit_type'];

    // ============================================================
    // 1. UPDATE PAGE CONTENT (judul + deskripsi untuk profil_logo)
    // ============================================================
    if ($edit_type === "page_content") {

        $judul_page     = $_POST['judul_page'];
        $deskripsi_page = $_POST['deskripsi_page'];
        $page_key       = "profil_logo";

        $queryPage = "UPDATE page_content
                      SET judul = $1, deskripsi = $2, id_user = $3
                      WHERE page_key = $4";

        $resultPage = pg_query_params(
            $conn,
            $queryPage,
            array($judul_page, $deskripsi_page, $id_user, $page_key)
        );

        if ($resultPage) {
            echo "<script>
                    alert('Konten halaman Logo berhasil diperbarui!');
                    window.location.href = '../profil/edit_logo.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memperbarui konten halaman!');
                    window.location.href = '../profil/edit_logo.php';
                  </script>";
        }
        exit();
    }

    // ============================================================
    // 2. UPDATE LOGO 1 & LOGO 2
    // ============================================================
    elseif ($edit_type === "logo") {

        $id_logo1 = $_POST['id_logo1'];
        $id_logo2 = $_POST['id_logo2'];

        $targetDir = "../../uploads/logo/";

        // Ekstensi file yang diperbolehkan
        $allowedExt = array('jpg', 'jpeg', 'png', 'gif', 'svg');

        // ========================= LOGO 1 =========================
        if (!empty($_FILES['file_logo1']['name'])) {

            $file1 = $_FILES['file_logo1']['name'];
            $tmp1  = $_FILES['file_logo1']['tmp_name'];
            $size1 = $_FILES['file_logo1']['size'];

            // Ambil data lama
            $oldData1 = pg_fetch_assoc(pg_query($conn,
                "SELECT media_path FROM logo WHERE id_logo = $id_logo1"
            ));
            $oldFoto1 = $oldData1['media_path'];

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

            $newFile1 = "logo1_" . time() . "." . $ext1;

            if (move_uploaded_file($tmp1, $targetDir . $newFile1)) {

                // Hapus foto lama
                if (!empty($oldFoto1) && file_exists($targetDir . $oldFoto1)) {
                    unlink($targetDir . $oldFoto1);
                }

                pg_query_params(
                    $conn,
                    "UPDATE logo SET media_path = $1, id_user = $2 WHERE id_logo = $3",
                    array($newFile1, $id_user, $id_logo1)
                );

            } else {
                echo "<script>alert('Gagal upload Logo 1!');
                      window.location='../profil/edit_logo.php';</script>";
                exit();
            }
        }

        // ========================= LOGO 2 =========================
        if (!empty($_FILES['file_logo2']['name'])) {

            $file2 = $_FILES['file_logo2']['name'];
            $tmp2  = $_FILES['file_logo2']['tmp_name'];
            $size2 = $_FILES['file_logo2']['size'];

            $oldData2 = pg_fetch_assoc(pg_query($conn,
                "SELECT media_path FROM logo WHERE id_logo = $id_logo2"
            ));
            $oldFoto2 = $oldData2['media_path'];

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

            $newFile2 = "logo2_" . time() . "." . $ext2;

            if (move_uploaded_file($tmp2, $targetDir . $newFile2)) {

                if (!empty($oldFoto2) && file_exists($targetDir . $oldFoto2)) {
                    unlink($targetDir . $oldFoto2);
                }

                pg_query_params(
                    $conn,
                    "UPDATE logo SET media_path = $1, id_user = $2 WHERE id_logo = $3",
                    array($newFile2, $id_user, $id_logo2)
                );

            } else {
                echo "<script>alert('Gagal upload Logo 2!');
                      window.location='../profil/edit_logo.php';</script>";
                exit();
            }
        }

        echo "<script>
                alert('Logo berhasil diperbarui!');
                window.location.href = '../profil/edit_logo.php';
              </script>";
        exit();
    }

} else {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../profil/edit_logo.php';
          </script>";
    exit();
}
?>
