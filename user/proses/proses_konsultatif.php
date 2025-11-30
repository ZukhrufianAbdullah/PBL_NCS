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
    $isi_pesan = $_POST['isi_pesan'] ?? '';

    if (!empty($nama_pengirim) && !empty($isi_pesan)) {
        // Simpan ke database - PostgreSQL version
        $sql = "INSERT INTO konsultatif (nama_pengirim, isi_pesan) VALUES ($1, $2)";
        $result = pg_query_params($conn, $sql, array($nama_pengirim, $isi_pesan));

        if ($result) {
            // Set session untuk notifikasi sukses
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = "Pesan berhasil dikirim. Kami akan menghubungi Anda segera.";
            
            // OPSIONAL: Kirim notifikasi ke admin (bisa dikembangkan dengan email/websocket)
            // notifyAdminNewMessage($nama_pengirim, $isi_pesan);
            
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = "Terjadi kesalahan saat mengirim pesan: " . pg_last_error($conn);
        }
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = "Nama dan pesan harus diisi.";
    }

    // Redirect kembali ke halaman konsultatif
    header("Location: " . BASE_URL . "/user/layanan/konsultatif.php");
    exit();
} else {
    // Jika bukan POST, redirect ke halaman konsultatif
    header("Location: " . BASE_URL . "/user/layanan/konsultatif.php");
    exit();
}

// Fungsi untuk notifikasi admin (opsional - bisa dikembangkan)
function notifyAdminNewMessage($nama_pengirim, $isi_pesan) {
    // Contoh: Simpan notifikasi di database atau kirim email
    // Ini adalah placeholder untuk pengembangan lebih lanjut
    error_log("Pesan baru dari: $nama_pengirim - " . substr($isi_pesan, 0, 50) . "...");
}
?>