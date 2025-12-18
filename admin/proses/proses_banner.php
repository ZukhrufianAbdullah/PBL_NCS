<?php
session_start();
include '../../config/koneksi.php'; // dihubungkan dengan database

// Ambil id_user admin yang login
$id_user = $_SESSION['id_user'] ?? 1;

// Folder penyimpanan banner
$uploadDir = '../../uploads/banner/';

// Pastikan folder upload ada, jika tidak ada membuat folder baru
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

        // VALIDASI FORMAT FILE
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg'];
        $allowedMime = [
            'image/png',
            'image/jpeg',
            'image/svg+xml'
        ];

        $fileName = $_FILES['image_banner']['name']; // Mengambil nama file asli
        $tmpFile  = $_FILES['image_banner']['tmp_name']; //mengambil lokasi sementara file yang di-upload oleh user di server
        $fileType = mime_content_type($tmpFile); // Mengetahui jenis asli file berdasarkan isinya
        $fileSize = $_FILES['image_banner']['size']; // untuk mengetahui ukuran file

        //mengambil ekstensi file dari nama file dan mengubahnya menjadi huruf kecil agar memudahkan proses validasi jenis file yang di-upload
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validasi ekstensi
        if (!in_array($fileExt, $allowedExtensions)) {
            echo "<script>alert('Gagal: Format file tidak diizinkan. Gunakan PNG, JPG, atau SVG!'); 
                window.history.back();</script>";
            exit();
        }

        // Validasi MIME type
        if (!in_array($fileType, $allowedMime)) {
            echo "<script>alert('Gagal: File bukan gambar valid!'); 
                window.history.back();</script>";
            exit();
        }

        // Validasi ukuran (opsional 3MB)
        if ($fileSize > 3 * 1024 * 1024) {
            echo "<script>alert('Gagal: Ukuran file maksimal 3MB!'); 
                window.history.back();</script>";
            exit();
        }

        // Cek banner lama
        $checkBackgroundBanner = pg_query($conn, 
        "SELECT * FROM settings WHERE setting_name = 'image_banner'");

        //membuat nama file baru yang unik sebelum file disimpan ke server.
        $newName  = time() . "_" . $fileName;

       // memindahkan file yang di-upload dari folder sementara tmp ke folder tujuan yang sebenarnya dengan nama file baru.
        move_uploaded_file($tmpFile, $uploadDir . $newName);

        // mengecek apakah data lama sudah ada, jika sudah ada maka data lama dihapus
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
