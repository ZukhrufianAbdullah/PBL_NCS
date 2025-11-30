<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session (fallback 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;

// Helper function untuk ensure page exists
function ensure_page($conn, string $pageName): int
{
    $pageResult = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", array($pageName));
    if ($pageResult && pg_num_rows($pageResult) > 0) {
        $page = pg_fetch_assoc($pageResult);
        return (int) $page['id_page'];
    }

    $insertPage = pg_query_params($conn, "INSERT INTO pages (nama) VALUES ($1) RETURNING id_page", array($pageName));
    $page = pg_fetch_assoc($insertPage);
    return (int) $page['id_page'];
}

// Helper function untuk upsert page content
function upsert_konsultatif_page_content($conn, int $pageId, string $contentKey, string $value, int $userId): void
{
    $checkSql = "SELECT id_page_content FROM page_content WHERE id_page = $1 AND content_key = $2";
    $existing = pg_query_params($conn, $checkSql, array($pageId, $contentKey));

    if ($existing && pg_num_rows($existing) > 0) {
        $updateSql = "
            UPDATE page_content
            SET content_type = 'text', content_value = $1, id_user = $2
            WHERE id_page = $3 AND content_key = $4
        ";
        pg_query_params($conn, $updateSql, array($value, $userId, $pageId, $contentKey));
    } else {
        $insertSql = "
            INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
            VALUES ($1, $2, 'text', $3, $4)
        ";
        pg_query_params($conn, $insertSql, array($pageId, $contentKey, $value, $userId));
    }
}

// PROSES EDIT SECTION CONTENT KONSULTATIF
if (isset($_POST['submit_section_content'])) {
    $section_title = trim($_POST['section_title'] ?? '');
    $section_description = trim($_POST['section_description'] ?? '');
    $page_key = "layanan_konsultatif";

    // Pastikan page exists (auto-create jika belum ada)
    $pageId = ensure_page($conn, $page_key);
    
    // Update atau insert section title
    upsert_konsultatif_page_content($conn, $pageId, 'section_title', $section_title, $id_user);
    
    // Update atau insert section description  
    upsert_konsultatif_page_content($conn, $pageId, 'section_description', $section_description, $id_user);

    echo "<script>
            alert('Konten halaman Konsultatif berhasil diperbarui!');
            window.location.href = '../layanan/lihat_pesan.php';
          </script>";
    exit();
} else {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../layanan/lihat_pesan.php';
          </script>";
    exit();
}
?>