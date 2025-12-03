<?php
// File: admin/proses/proses_beranda.php
session_start();
include '../../config/koneksi.php';

// Include helper functions
require_once __DIR__ . '/../../app/helpers/page_helper.php';

// Inisialisasi halaman home jika belum ada
$homePageId = init_home_page_and_sections($conn, $_SESSION['id_user'] ?? 1);

if (!$homePageId) {
    echo "<script>alert('Gagal menginisialisasi halaman home!'); window.location.href = '../beranda/edit_beranda.php';</script>";
    exit();
}

// Ambil id_user dari session
$id_user = $_SESSION['id_user'] ?? 1;

// ===========================================================================
// 1. UPDATE DESKRIPSI BERANDA
// ===========================================================================
if (isset($_POST['update_deskripsi'])) {
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    
    if (empty($deskripsi)) {
        echo "<script>alert('Deskripsi tidak boleh kosong!'); window.location.href = '../beranda/edit_beranda.php';</script>";
        exit();
    }
    
    // Gunakan helper function untuk upsert
    $result = upsert_page_content($conn, $homePageId, 'deskripsi', $deskripsi, $id_user);
    
    if ($result) {
        echo "<script>alert('Deskripsi beranda berhasil diperbarui!'); window.location.href = '../beranda/edit_beranda.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui deskripsi: " . pg_last_error($conn) . "'); window.location.href = '../beranda/edit_beranda.php';</script>";
    }
    exit();
}

// ===========================================================================
// 2. UPDATE VISIBILITY SETTINGS
// ===========================================================================
if (isset($_POST['update_visibility'])) {
    $sections = [
        'show_visi_misi',
        'show_logo',
        'show_struktur',
        'show_agenda',
        'show_galeri',
        'show_penelitian',
        'show_pengabdian',
        'show_sarana'
    ];
    
    $allSuccess = true;
    $errors = [];
    
    foreach ($sections as $section) {
        // Jika checkbox dicentang, value = 'true', jika tidak = 'false'
        $value = isset($_POST[$section]) ? 'true' : 'false';
        
        // Gunakan helper function untuk upsert
        $result = upsert_page_content($conn, $homePageId, $section, $value, $id_user);
        
        if (!$result) {
            $allSuccess = false;
            $errors[] = "Gagal update setting $section";
        }
    }
    
    if ($allSuccess) {
        $_SESSION['success_message'] = 'Pengaturan visibilitas berhasil diperbarui!';
        echo "<script>alert('Pengaturan visibilitas berhasil diperbarui!'); window.location.href = '../beranda/edit_beranda.php';</script>";
        exit();
    } else {
        $errorMsg = implode(', ', $errors);
        echo "<script>alert('Terjadi kesalahan: $errorMsg'); window.location.href = '../beranda/edit_beranda.php';</script>";
    }
    exit();
}

// ===========================================================================
// 3. RESET KE DEFAULT SETTINGS (opsional tambahan)
// ===========================================================================
if (isset($_POST['reset_to_default'])) {
    // Reset semua section ke true
    $sections = [
        'show_visi_misi' => 'true',
        'show_logo' => 'true',
        'show_struktur' => 'true',
        'show_agenda' => 'true',
        'show_galeri' => 'true',
        'show_penelitian' => 'true',
        'show_pengabdian' => 'true',
        'show_sarana' => 'true'
    ];
    
    $allSuccess = true;
    
    foreach ($sections as $key => $value) {
        $result = upsert_page_content($conn, $homePageId, $key, $value, $id_user);
        if (!$result) {
            $allSuccess = false;
            break;
        }
    }
    
    if ($allSuccess) {
        $_SESSION['success_message'] = 'Semua section telah direset ke default (ditampilkan)';
        header('Location: ../beranda/edit_beranda.php');
        exit();
    } else {
        echo "<script>alert('Gagal mereset ke default settings'); window.location.href = '../beranda/edit_beranda.php';</script>";
    }
    exit();
}

// ===========================================================================
// BACKUP — Jika akses tanpa POST valid
// ===========================================================================
echo "<script>alert('Akses tidak valid!'); window.location.href = '../beranda/edit_beranda.php';</script>";
exit();
?>