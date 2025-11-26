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

    // Update Judul dan Subjudul Banner
    $judul      = $_POST['title_banner'];
    $subjudul   = $_POST['subheadline_banner'];

    // Cek apakah title_banner sudah ada
    $checkTitleBanner = pg_query($conn, 
    "SELECT * FROM settings WHERE setting_name = 'title_banner'");

    if (pg_num_rows($checkTitleBanner) > 0) {
        // Update jika sudah ada
        pg_query_params($conn,
            "UPDATE settings SET setting_value = $1, id_user = $2
             WHERE setting_name = 'title_banner'",
            array($judul, $id_user)
        );
    } else {
        // Insert jika belum ada
        pg_query_params($conn,
            "INSERT INTO settings (setting_name, setting_type, setting_value, id_user)
             VALUES ('title_banner','text', $1, $2)",
            array($judul, $id_user)
        );
    }

    // Cek apakah subheadline_banner sudah ada
    $checkSubheadlineBanner = pg_query($conn, 
    "Select * from settings where setting_name = 'subheadline_banner'");

    if (pg_num_rows($checkSubheadlineBanner) > 0) {
        // Update jika sudah ada
        pg_query_params($conn,
            "Update settings set setting_value = $1, id_user = $2
             where setting_name = 'subheadline_banner'",
            array($subjudul, $id_user)
        );
    } else {
        // Insert jika belum ada
        pg_query_params($conn,
            "Insert into settings (setting_name, setting_type, setting_value, id_user)
             values ('subheadline_banner','text', $1, $2)",
            array($subjudul, $id_user)
        );
    }
    

    // Update Background
    if (!empty($_FILES['image_banner']['name'])) {
        // Cek banner lama
        $checkBackgroundBanner = pg_query($conn, 
        "SELECT * FROM settings WHERE setting_name = 'image_banner'");

        $fileName = $_FILES['image_banner']['name'];
        $tmpFile  = $_FILES['image_banner']['tmp_name'];
        $newName  = time() . "_" . $fileName;

        // Upload file baru
        move_uploaded_file($tmpFile, $uploadDir . $newName);

        // Jika ada data lama → hapus file lama
        if ($row = pg_fetch_assoc($checkBackgroundBanner)) {
            $oldFile = $row['setting_value'];
            if (!empty($oldFile) && file_exists($uploadDir . $oldFile)) {
                unlink($uploadDir . $oldFile);
            }
        }

        // Simpan nama file baru ke database
        if (pg_num_rows($checkBackgroundBanner) > 0) {
            // Update jika sudah ada
            pg_query_params($conn,
                "Update settings set setting_value = $1, id_user = $2
                 where setting_name = 'image_banner'",
                array($newName, $id_user)
            );
        } else {
            // Insert jika belum ada
            pg_query_params($conn,
                "Insert into settings (setting_name, setting_type, setting_value, id_user)
                 values ('image_banner','image', $1, $2)",
                array($newName, $id_user)
            );
        }
    }
    echo"<script>
            alert('Banner berhasil diperbarui!');
            window.location.href = '../beranda/edit_banner.php';
        </script>";
    exit();
} else {
    echo"<script>
            alert('Akses tidak valid!');
            window.location.href = '../beranda/edit_banner.php';
        </script>";
    exit();
}
?>
