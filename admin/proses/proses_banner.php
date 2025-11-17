<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user admin yang login
$id_user = $_SESSION['id_user'] ?? 1;

// Folder penyimpanan banner
$uploadDir = '../../uploads/banner/';

// Pastikan folder upload ada
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {

    $judul      = $_POST['judul'];
    $subjudul   = $_POST['subjudul'];

    // Ambil data lama dari database untuk menghapus gambar lama
    $queryOld = pg_query($conn, "SELECT gambar FROM banner WHERE id_banner = 1");
    $oldData = pg_fetch_assoc($queryOld);
    $oldBanner = $oldData['gambar'];

    // Cek apakah admin upload gambar baru
    if ($_FILES['gambar']['name'] != "") {

        $fileName = $_FILES['gambar']['name'];
        $tmpFile  = $_FILES['gambar']['tmp_name'];

        // Nama file unik
        $newName = time() . "_" . $fileName;

        // Upload file banner baru
        move_uploaded_file($tmpFile, $uploadDir . $newName);

        // Hapus banner lama jika ada
        if (!empty($oldBanner) && file_exists($uploadDir . $oldBanner)) {
            unlink($uploadDir . $oldBanner);
        }

    } else {
        // Jika admin tidak mengganti gambar
        $newName = $oldBanner;
    }

    // Update table banner
    $query = "UPDATE banner 
              SET judul = $1, subjudul = $2, gambar = $3, id_user = $4
              WHERE id_banner = 1";

    $params = array($judul, $subjudul, $newName, $id_user);

    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>
                alert('Banner berhasil diperbarui!');
                window.location.href = '../banner/edit_banner.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal memperbarui banner!');
                window.location.href = '../banner/edit_banner.php';
              </script>";
        exit();
    }

} else {
    // Jika file ini diakses tanpa submit
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../banner/edit_banner.php';
          </script>";
    exit();
}
?>
