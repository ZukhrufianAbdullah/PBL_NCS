<?php
// Memulai session untuk mengambil data login user
session_start();

// Memanggil file koneksi database
include '../../config/koneksi.php';

// Mengambil id_user dari session (fallback ke 1 jika tidak ada)
$id_user = $_SESSION['id_user'] ?? 1;

// ===========================================================================
// 1. UPDATE DATA FOOTER (tabel settings) - TERMASUK QUICK LINKS
// ===========================================================================
if (isset($_POST['update_footer'])) {

    // Ambil data footer dari form
    $site_title = $_POST['site_title'] ?? '';
    $footer_description = $_POST['footer_description'] ?? '';
    $footer_developer_title = $_POST['footer_developer_title'] ?? 'Developed by';
    $footer_copyright = $_POST['footer_copyright'] ?? 'All Rights Reserved.';
    $footer_credit_tim = $_POST['footer_credit_tim'] ?? '';
    
    // Handle checkbox quick links
    // Jika tidak dicentang, field tidak dikirim oleh form
    $footer_show_quick_links = isset($_POST['footer_show_quick_links']) ? 'true' : 'false';

    // Kumpulan setting footer yang akan diproses
    $settings = [
        'site_title' => $site_title,
        'footer_description' => $footer_description,
        'footer_developer_title' => $footer_developer_title,
        'footer_copyright' => $footer_copyright,
        'footer_credit_tim' => $footer_credit_tim,
        'footer_show_quick_links' => $footer_show_quick_links
    ];

    // Flag untuk menandai apakah semua proses berhasil
    $all_success = true;
    $error_message = '';

    // Loop setiap setting untuk update atau insert
    foreach ($settings as $setting_name => $setting_value) {

        // Cek apakah setting sudah ada di database
        $checkSetting = pg_query(
            $conn,
            "SELECT * FROM settings WHERE setting_name = '$setting_name'"
        );
        
        if (pg_num_rows($checkSetting) > 0) {
            // Jika setting sudah ada → lakukan UPDATE
            $result = pg_query_params(
                $conn,
                "UPDATE settings 
                 SET setting_value = $1, id_user = $2 
                 WHERE setting_name = '$setting_name'",
                array($setting_value, $id_user)
            );
        } else {
            // Jika setting belum ada → lakukan INSERT
            // Tipe boolean khusus untuk quick links
            $setting_type = ($setting_name === 'footer_show_quick_links') ? 'boolean' : 'text';
            $result = pg_query_params(
                $conn,
                "INSERT INTO settings 
                 (setting_name, setting_type, setting_value, id_user) 
                 VALUES ('$setting_name', '$setting_type', $1, $2)",
                array($setting_value, $id_user)
            );
        }

        // Jika salah satu query gagal, hentikan proses
        if (!$result) {
            $all_success = false;
            $error_message = "Gagal update setting: $setting_name - " . pg_last_error($conn);
            break;
        }
    }

    // Jika semua setting berhasil diperbarui
    if ($all_success) {
        echo "<script>
                alert('Footer berhasil diperbarui!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
    } else {
        // Jika terjadi error
        echo "<script>
                alert('$error_message');
                window.location.href = '../setting/edit_footer.php';
              </script>";
    }
    exit();
}

// ===========================================================================
// 2. TAMBAH SOSIAL MEDIA
// ===========================================================================
if (isset($_POST['tambah_sosmed'])) {

    // Ambil data sosial media dari form
    $nama_sosialmedia = $_POST['nama_sosialmedia'] ?? '';
    $platform = $_POST['platform'] ?? '';
    $url = $_POST['url'] ?? '';

    // Validasi input tidak boleh kosong
    if (empty($nama_sosialmedia) || empty($platform) || empty($url)) {
        echo "<script>
                alert('Nama sosial media, platform, dan URL harus diisi!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
        exit();
    }

    // Query insert sosial media
    $query = "
        INSERT INTO sosial_media 
        (nama_sosialmedia, platform, url, id_user)
        VALUES ($1, $2, $3, $4)
    ";

    $result = pg_query_params($conn, $query, array(
        $nama_sosialmedia,
        $platform,
        $url,
        $id_user
    ));

    // Notifikasi hasil proses
    if ($result) {
        echo "<script>
                alert('Sosial media berhasil ditambahkan!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan sosial media!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
    }
    exit();
}

// ===========================================================================
// 3. UPDATE SOSIAL MEDIA
// ===========================================================================
if (isset($_POST['update_sosmed'])) {

    // Ambil data sosial media yang akan diupdate
    $id_sosialmedia = $_POST['id_sosialmedia'] ?? 0;
    $nama_sosialmedia = $_POST['nama_sosialmedia'] ?? '';
    $platform = $_POST['platform'] ?? '';
    $url = $_POST['url'] ?? '';

    // Validasi ID sosial media
    if ($id_sosialmedia <= 0) {
        echo "<script>
                alert('ID sosial media tidak valid!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
        exit();
    }

    // Validasi input wajib diisi
    if (empty($nama_sosialmedia) || empty($platform) || empty($url)) {
        echo "<script>
                alert('Nama sosial media, platform, dan URL harus diisi!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
        exit();
    }

    // Query update sosial media
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

    // Notifikasi hasil proses
    if ($result) {
        echo "<script>
                alert('Sosial media berhasil diperbarui!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui sosial media!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
    }
    exit();
}

// ===========================================================================
// 4. HAPUS SOSIAL MEDIA
// ===========================================================================
if (isset($_POST['hapus_sosmed'])) {

    // Ambil ID sosial media yang akan dihapus
    $id_sosialmedia = $_POST['id_sosialmedia'] ?? 0;

    // Validasi ID sosial media
    if ($id_sosialmedia <= 0) {
        echo "<script>
                alert('ID sosial media tidak valid!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
        exit();
    }

    // Query hapus sosial media
    $query = "DELETE FROM sosial_media WHERE id_sosialmedia = $1";
    $result = pg_query_params($conn, $query, array($id_sosialmedia));

    // Notifikasi hasil proses
    if ($result) {
        echo "<script>
                alert('Sosial media berhasil dihapus!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus sosial media!');
                window.location.href = '../setting/edit_footer.php';
              </script>";
    }
    exit();
}

// ===========================================================================
// BACKUP — Jika file diakses tanpa POST yang valid
// ===========================================================================
echo "<script>
        alert('Akses tidak valid!');
        window.location.href = '../setting/edit_footer.php';
      </script>";
exit();
?>
