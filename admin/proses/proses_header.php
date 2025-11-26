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

    $judul = $_POST['title_header'];

    // Update Judul Header

    // Cek apakah title_header sudah ada
    $checkTitleHeader = pg_query(
        $conn,
        "Select * from settings where setting_name = 'title_header'"
    );

    if (pg_num_rows($checkTitleHeader) > 0) {
        // Update jika sudah ada
        pg_query_params(
            $conn,
            "Update settings set setting_value = $1, id_user = $2
            where setting_name = 'title_header'",
            array($judul, $id_user)
        );
    } else {
        // Insert jika belum ada
        pg_query_params(
            $conn,
            "Insert into settings (setting_name, setting_type, setting_value, id_user)
            values ('title_header','text', $1, $2)",
            array($judul, $id_user)
        );
    }

    //Update Logo Header

    if (!empty($_FILES['logo_header']['name'])) {
        // Cek logo lama
        $checkLogo = pg_query($conn, "SELECT * FROM settings WHERE setting_name = 'logo_header'");

        $fileName = $_FILES['logo_header']['name'];
        $tmpFile = $_FILES['logo_header']['tmp_name'];
        $newName = time() . "_" . $fileName;

        // Upload file baru
        move_uploaded_file($tmpFile, $uploadDir . $newName);

        // Jika ada data lama → hapus file lama
        if ($row = pg_fetch_assoc($checkLogo)) {
            $oldFile = $row['setting_value'];
            if (!empty($oldFile) && file_exists($uploadDir . $oldFile)) {
                unlink($uploadDir . $oldFile);
            }

            // Update jika sudah ada
            pg_query_params(
                $conn,
                "UPDATE settings SET setting_value = $1, id_user = $2 WHERE setting_name = 'logo_header'",
                array($newName, $id_user)
            );
        } else {
            // Insert jika belum ada
            pg_query_params(
                $conn,
                "INSERT INTO settings (setting_name, setting_type, setting_value, id_user) VALUES ('logo_header', 'image', $1, $2)",
                array($newName, $id_user)
            );
        }
    }
    /* ============================================================ */

    echo "<script> alert('Header berhasil diperbarui!'); window.location.href = '../setting/edit_header.php'; </script>";
    exit();
} else {
    echo "<script> alert('Akses tidak valid!'); window.location.href = '../setting/edit_header.php'; </script>";
    exit();
}
