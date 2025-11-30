<?php
// File: user/proses/proses_konsultatif.php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pengirim = $_POST['nama_pengirim'] ?? '';
    $isi_pesan = $_POST['isi_pesan'] ?? '';

    if (!empty($nama_pengirim) && !empty($isi_pesan)) {
        // Simpan ke database
        $sql = "INSERT INTO konsultatif (nama_pengirim, isi_pesan) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nama_pengirim, $isi_pesan);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Pesan berhasil dikirim. Kami akan menghubungi Anda segera.";
        } else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat mengirim pesan.";
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Nama dan pesan harus diisi.";
    }
    $conn->close();

    // Redirect kembali ke halaman konsultatif
    header("Location: " . BASE_URL . "/user/layanan/konsultatif.php");
    exit();
} else {
    // Jika bukan POST, redirect ke halaman konsultatif
    header("Location: " . BASE_URL . "/user/layanan/konsultatif.php");
    exit();
}
?>