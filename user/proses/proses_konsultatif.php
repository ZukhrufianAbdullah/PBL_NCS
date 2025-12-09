<?php
// File: user/proses/proses_konsultatif.php
session_start();

// Define BASE_URL untuk konsistensi
define('BASE_URL', '../..');

// Koneksi database
$config_path = $_SERVER['DOCUMENT_ROOT'] . '/PBL_NCS/config/koneksi.php';
if (!file_exists($config_path)) {
    die("Database configuration file not found");
}
require_once $config_path;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pengirim = $_POST['nama_pengirim'] ?? '';
    $email = $_POST['email'] ?? '';
    $isi_pesan = $_POST['isi_pesan'] ?? '';

    if (!empty($nama_pengirim) && !empty($email) && !empty($isi_pesan)) {
        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = "Format email tidak valid.";
            header("Location: " . BASE_URL . "/user/layanan/konsultatif.php");
            exit();
        }
        
        // Simpan ke database - PostgreSQL version
        $sql = "INSERT INTO konsultatif (nama_pengirim, email, isi_pesan, status) VALUES ($1, $2, $3, 'pending')";
        $result = pg_query_params($conn, $sql, array($nama_pengirim, $email, $isi_pesan));

        if ($result) {
            // Set session untuk notifikasi sukses
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = "Pesan berhasil dikirim. Kami akan menghubungi Anda melalui email.";
            
            // Kirim notifikasi email ke admin (opsional)
            sendAdminNotification($nama_pengirim, $email, $isi_pesan);
            
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = "Terjadi kesalahan saat mengirim pesan: " . pg_last_error($conn);
        }
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = "Semua field harus diisi.";
    }

    // Redirect kembali ke halaman konsultatif
    header("Location: " . BASE_URL . "/user/layanan/konsultatif.php");
    exit();
} else {
    // Jika bukan POST, redirect ke halaman konsultatif
    header("Location: " . BASE_URL . "/user/layanan/konsultatif.php");
    exit();
}

// Fungsi untuk notifikasi admin via email (opsional)
function sendAdminNotification($nama, $email, $pesan) {
    // Ganti dengan email admin yang sebenarnya
    $admin_email = "admin@laboratory.com"; 
    $subject = "Pesan Konsultatif Baru dari $nama";
    $message = "Halo Admin,\n\n";
    $message .= "Ada pesan konsultatif baru:\n\n";
    $message .= "Nama: $nama\n";
    $message .= "Email: $email\n";
    $message .= "Pesan:\n$pesan\n\n";
    $message .= "Silakan balas melalui dashboard admin.\n";
    $message .= "Waktu: " . date('Y-m-d H:i:s') . "\n";
    
    // Uncomment jika ingin mengirim email
    // mail($admin_email, $subject, $message, "From: no-reply@laboratory.com");
    
    error_log("Notifikasi admin: Pesan baru dari $nama ($email)");
}
?>