<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session
$id_user = $_SESSION['id_user'] ?? 1;

// Folder penyimpanan logo
$uploadDir = '../../uploads/header/';

// Pastikan folder ada
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {

    $judul = $_POST['judul'];

    // Ambil data lama (untuk menghapus logo lama)
    $queryOld = pg_query($conn, "SELECT logo_polinema FROM header WHERE id_header = 1");
    $old = pg_fetch_assoc($queryOld);
    $oldLogo = $old['logo_polinema'];

    // Cek apakah admin mengupload logo baru
    if ($_FILES['logo_polinema']['name'] != "") {

        $fileName = $_FILES['logo_polinema']['name'];
        $tmpFile  = $_FILES['logo_polinema']['tmp_name'];

        // Buat nama random untuk menghindari bentrok
        $newName = time() . "_" . $fileName;

        // Pindah file ke folder uploads
        move_uploaded_file($tmpFile, $uploadDir . $newName);

        // Hapus logo lama
        if (!empty($oldLogo) && file_exists($uploadDir . $oldLogo)) {
            unlink($uploadDir . $oldLogo);
        }

    } else {
        // Jika tidak upload file baru, gunakan nama lama
        $newName = $oldLogo;
    }

    // Update data header
    $query = "UPDATE header 
              SET judul = $1, logo_polinema = $2, id_user = $3
              WHERE id_header = 1";

    $params = array($judul, $newName, $id_user);

    $result = pg_query_params($conn, $query, $params);

    // Cek hasil
    if ($result) {
        echo "<script>
                alert('Header berhasil diperbarui!');
                window.location.href = '../header/edit_header.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui header!');
                window.location.href = '../header/edit_header.php';
              </script>";
    }

    exit();
} else {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../header/edit_header.php';
          </script>";
    exit();
}
?>
