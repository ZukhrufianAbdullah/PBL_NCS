<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session
$id_user = $_SESSION['id_user'] ?? 1;

// ===========================================================================
// 1. UPDATE DATA FOOTER (settings table) - DIPERBAIKI
// ===========================================================================
if (isset($_POST['update_footer'])) {
    $site_title = $_POST['site_title'] ?? '';
    $footer_description = $_POST['footer_description'] ?? '';
    $footer_developer_title = $_POST['footer_developer_title'] ?? 'Developed by';
    $footer_copyright = $_POST['footer_copyright'] ?? 'All Rights Reserved.';
    $footer_credit_tim = $_POST['footer_credit_tim'] ?? '';

    // Update masing-masing setting secara terpisah
    $settings = [
        'site_title' => $site_title,
        'footer_description' => $footer_description,
        'footer_developer_title' => $footer_developer_title,
        'footer_copyright' => $footer_copyright,
        'footer_credit_tim' => $footer_credit_tim
    ];

    $all_success = true;
    $error_message = '';

    foreach ($settings as $setting_name => $setting_value) {
        // Cek apakah setting sudah ada
        $checkSetting = pg_query($conn, "SELECT * FROM settings WHERE setting_name = '$setting_name'");
        
        if (pg_num_rows($checkSetting) > 0) {
            // Update jika sudah ada
            $result = pg_query_params(
                $conn,
                "UPDATE settings SET setting_value = $1, id_user = $2 WHERE setting_name = '$setting_name'",
                array($setting_value, $id_user)
            );
        } else {
            // Insert jika belum ada
            $result = pg_query_params(
                $conn,
                "INSERT INTO settings (setting_name, setting_type, setting_value, id_user) VALUES ('$setting_name', 'text', $1, $2)",
                array($setting_value, $id_user)
            );
        }

        if (!$result) {
            $all_success = false;
            $error_message = "Gagal update setting: $setting_name - " . pg_last_error($conn);
            break;
        }
    }

    if ($all_success) {
        echo "<script>alert('Footer berhasil diperbarui!'); window.location.href = '../setting/edit_footer.php';</script>";
    } else {
        echo "<script>alert('$error_message'); window.location.href = '../setting/edit_footer.php';</script>";
    }
    exit();
}

// ===========================================================================
// 2. TAMBAH SOSIAL MEDIA
// ===========================================================================
if (isset($_POST['tambah_sosmed'])) {
    $nama_sosialmedia = $_POST['nama_sosialmedia'] ?? '';
    $platform = $_POST['platform'] ?? '';
    $url = $_POST['url'] ?? '';

    if (empty($nama_sosialmedia) || empty($platform) || empty($url)) {
        echo "<script>alert('Nama sosial media, platform, dan URL harus diisi!'); window.location.href = '../setting/edit_footer.php';</script>";
        exit();
    }

    $query = "
        INSERT INTO sosial_media (nama_sosialmedia, platform, url, id_user)
        VALUES ($1, $2, $3, $4)
    ";

    $result = pg_query_params($conn, $query, array(
        $nama_sosialmedia,
        $platform,
        $url,
        $id_user
    ));

    if ($result) {
        echo "<script>alert('Sosial media berhasil ditambahkan!'); window.location.href = '../setting/edit_footer.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan sosial media!'); window.location.href = '../setting/edit_footer.php';</script>";
    }
    exit();
}

// ===========================================================================
// 3. UPDATE SOSIAL MEDIA (BARU)
// ===========================================================================
if (isset($_POST['update_sosmed'])) {
    $id_sosialmedia = $_POST['id_sosialmedia'] ?? 0;
    $nama_sosialmedia = $_POST['nama_sosialmedia'] ?? '';
    $platform = $_POST['platform'] ?? '';
    $url = $_POST['url'] ?? '';

    if ($id_sosialmedia <= 0) {
        echo "<script>alert('ID sosial media tidak valid!'); window.location.href = '../setting/edit_footer.php';</script>";
        exit();
    }

    if (empty($nama_sosialmedia) || empty($platform) || empty($url)) {
        echo "<script>alert('Nama sosial media, platform, dan URL harus diisi!'); window.location.href = '../setting/edit_footer.php';</script>";
        exit();
    }

    $query = "
        UPDATE sosial_media 
        SET nama_sosialmedia = $1, 
            platform = $2, 
            url = $3, 
            id_user = $4 
        WHERE id_sosialmedia = $5
    ";

    $result = pg_query_params($conn, $query, array(
        $nama_sosialmedia,
        $platform,
        $url,
        $id_user,
        $id_sosialmedia
    ));

    if ($result) {
        echo "<script>alert('Sosial media berhasil diperbarui!'); window.location.href = '../setting/edit_footer.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui sosial media!'); window.location.href = '../setting/edit_footer.php';</script>";
    }
    exit();
}

// ===========================================================================
// 4. HAPUS SOSIAL MEDIA
// ===========================================================================
if (isset($_POST['hapus_sosmed'])) {
    $id_sosialmedia = $_POST['id_sosialmedia'] ?? 0;

    if ($id_sosialmedia <= 0) {
        echo "<script>alert('ID sosial media tidak valid!'); window.location.href = '../setting/edit_footer.php';</script>";
        exit();
    }

    $query = "DELETE FROM sosial_media WHERE id_sosialmedia = $1";
    $result = pg_query_params($conn, $query, array($id_sosialmedia));

    if ($result) {
        echo "<script>alert('Sosial media berhasil dihapus!'); window.location.href = '../setting/edit_footer.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus sosial media!'); window.location.href = '../setting/edit_footer.php';</script>";
    }
    exit();
}

// ===========================================================================
// BACKUP — Jika akses tanpa POST valid
// ===========================================================================
echo "<script>alert('Akses tidak valid!'); window.location.href = '../setting/edit_footer.php';</script>";
exit();
?>