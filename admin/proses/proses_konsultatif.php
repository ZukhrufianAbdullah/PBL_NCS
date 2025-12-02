<?php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// Ambil id_user dari session (fallback 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;

// PROSES PENGIRIMAN PESAN KONSULTATIF DARI USER
if (isset($_POST['nama_pengirim']) && isset($_POST['isi_pesan'])) {
    $nama_pengirim = trim($_POST['nama_pengirim']);
    $isi_pesan = trim($_POST['isi_pesan']);
    
    // Validasi input
    if (empty($nama_pengirim) || empty($isi_pesan)) {
        $_SESSION['alert_type'] = 'warning';
        $_SESSION['alert_message'] = 'Nama dan pesan tidak boleh kosong!';
        header("Location: ../layanan/konsultatif.php");
        exit();
    }
    
    // Insert pesan ke database
    $query = "INSERT INTO pesan_konsultatif (nama_pengirim, isi_pesan, id_user, tanggal_kirim, status) 
              VALUES ($1, $2, $3, NOW(), 'pending')";
    $result = pg_query_params($conn, $query, array($nama_pengirim, $isi_pesan, $id_user));
    
    if ($result) {
        $_SESSION['alert_type'] = 'success';
        $_SESSION['alert_message'] = 'Pesan berhasil dikirim!';
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Gagal mengirim pesan. Silakan coba lagi.';
    }
    
    header("Location: ../layanan/konsultatif.php");
    exit();
}

// PROSES EDIT SECTION CONTENT KONSULTATIF (untuk admin)
if (isset($_POST['submit_section_content'])) {
    $section_title = trim($_POST['section_title'] ?? '');
    $section_description = trim($_POST['section_description'] ?? '');
    
    // GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
    $id_page = ensure_page_exists($conn, 'layanan_konsultatif');
    
    if (!$id_page) {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Gagal membuat atau mendapatkan halaman Konsultatif!';
        header("Location: ../layanan/lihat_pesan.php");
        exit();
    }

    // GUNAKAN HELPER FUNCTION untuk upsert content
    $resultJudul = upsert_page_content($conn, $id_page, 'section_title', $section_title, $id_user);
    $resultDeskripsi = upsert_page_content($conn, $id_page, 'section_description', $section_description, $id_user);

    if ($resultJudul && $resultDeskripsi) {
        $_SESSION['alert_type'] = 'success';
        $_SESSION['alert_message'] = 'Konten halaman Konsultatif berhasil diperbarui!';
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Gagal memperbarui konten halaman Konsultatif!';
    }
    
    header("Location: ../layanan/lihat_pesan.php");
    exit();
}

// Jika tidak ada aksi yang valid
$_SESSION['alert_type'] = 'error';
$_SESSION['alert_message'] = 'Akses tidak valid!';
header("Location: ../layanan/konsultatif.php");
exit();
?>