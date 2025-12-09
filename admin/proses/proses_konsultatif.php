<?php
// File: admin/proses/proses_konsultatif.php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// Ambil id_user dari session
$id_user = $_SESSION['id_user'] ?? 1;

// PROSES EDIT SECTION CONTENT KONSULTATIF
if (isset($_POST['submit_section_content'])) {
    $section_title = trim($_POST['section_title'] ?? '');
    $section_description = trim($_POST['section_description'] ?? '');
    
    // GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
    $id_page = ensure_page_exists($conn, 'layanan_konsultatif');
    
    if (!$id_page) {
        $_SESSION['error'] = 'Gagal membuat atau mendapatkan halaman Konsultatif!';
        header("Location: ../layanan/lihat_pesan.php");
        exit();
    }

    // GUNAKAN HELPER FUNCTION untuk upsert content
    $resultJudul = upsert_page_content($conn, $id_page, 'section_title', $section_title, $id_user);
    $resultDeskripsi = upsert_page_content($conn, $id_page, 'section_description', $section_description, $id_user);

    if ($resultJudul && $resultDeskripsi) {
        $_SESSION['success'] = 'Konten halaman Konsultatif berhasil diperbarui!';
    } else {
        $_SESSION['error'] = 'Gagal memperbarui konten halaman Konsultatif!';
    }
    
    header("Location: ../layanan/lihat_pesan.php");
    exit();
}

// PROSES KIRIM EMAIL BALASAN (OPSIONAL)
if (isset($_POST['balas_email'])) {
    $id_pesan = (int)($_POST['id_pesan'] ?? 0);
    $email_tujuan = $_POST['email_tujuan'] ?? '';
    $subjek = $_POST['subjek'] ?? '';
    $isi_balasan = $_POST['isi_balasan'] ?? '';
    
    if (!empty($email_tujuan) && !empty($isi_balasan)) {
        // Kirim email
        $headers = "From: admin@laboratory.com\r\n";
        $headers .= "Reply-To: admin@laboratory.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $message = "<html><body>";
        $message .= "<h2>Balasan dari Laboratorium Network & Cyber Security</h2>";
        $message .= "<p>" . nl2br(htmlspecialchars($isi_balasan)) . "</p>";
        $message .= "<hr>";
        $message .= "<p><small>Email ini dikirim sebagai balasan atas pesan konsultatif Anda.</small></p>";
        $message .= "</body></html>";
        
        if (mail($email_tujuan, $subjek, $message, $headers)) {
            // Update status menjadi replied
            pg_query_params($conn, 
                "UPDATE konsultatif SET status = 'replied' WHERE id_konsultatif = $1",
                array($id_pesan)
            );
            
            $_SESSION['success'] = 'Email balasan berhasil dikirim!';
        } else {
            $_SESSION['error'] = 'Gagal mengirim email balasan!';
        }
    } else {
        $_SESSION['error'] = 'Email tujuan dan isi balasan harus diisi!';
    }
    
    header("Location: ../layanan/lihat_pesan.php");
    exit();
}

// Jika tidak ada aksi yang valid
$_SESSION['error'] = 'Akses tidak valid!';
header("Location: ../layanan/lihat_pesan.php");
exit();
?>