<?php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// Ambil id_user dari session (fallback 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;

// PROSES EDIT SECTION CONTENT KONSULTATIF
if (isset($_POST['submit_section_content'])) {
    $section_title = trim($_POST['section_title'] ?? '');
    $section_description = trim($_POST['section_description'] ?? '');
    
    // GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
    $id_page = ensure_page_exists($conn, 'layanan_konsultatif');
    
    if (!$id_page) {
        echo "<script>alert('Gagal membuat atau mendapatkan halaman Konsultatif!');
              window.location.href = '../layanan/lihat_pesan.php';</script>";
        exit();
    }

    // GUNAKAN HELPER FUNCTION untuk upsert content
    $resultJudul = upsert_page_content($conn, $id_page, 'section_title', $section_title, $id_user);
    $resultDeskripsi = upsert_page_content($conn, $id_page, 'section_description', $section_description, $id_user);

    if ($resultJudul && $resultDeskripsi) {
        echo "<script>
                alert('Konten halaman Konsultatif berhasil diperbarui!');
                window.location.href = '../layanan/lihat_pesan.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman Konsultatif!');
                window.location.href = '../layanan/lihat_pesan.php';
              </script>";
    }
    exit();
} else {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../layanan/lihat_pesan.php';
          </script>";
    exit();
}
?>