<?php
// File: admin/helpers/page_helper.php

/**
 * Helper function untuk mendapatkan atau membuat halaman otomatis
 * @param resource $conn Koneksi database PostgreSQL
 * @param string $page_name Nama halaman (contoh: 'profil_logo', 'galeri_galeri', dll)
 * @return int ID halaman yang sudah ada atau baru dibuat
 */
function ensure_page_exists($conn, $page_name) {
    // Cek apakah halaman sudah ada
    $sql = "SELECT id_page FROM pages WHERE nama = $1 LIMIT 1";
    $result = pg_query_params($conn, $sql, array($page_name));
    
    if ($result && pg_num_rows($result) > 0) {
        $page = pg_fetch_assoc($result);
        return (int) $page['id_page'];
    }
    
    // Jika belum ada, buat halaman baru
    $insert_sql = "INSERT INTO pages (nama) VALUES ($1) RETURNING id_page";
    $insert_result = pg_query_params($conn, $insert_sql, array($page_name));
    
    if ($insert_result && pg_num_rows($insert_result) > 0) {
        $new_page = pg_fetch_assoc($insert_result);
        return (int) $new_page['id_page'];
    }
    
    // Fallback ke default jika gagal
    return 0;
}

/**
 * Helper function untuk upsert page content
 * @param resource $conn Koneksi database
 * @param int $page_id ID halaman
 * @param string $content_key Key konten
 * @param string $content_value Nilai konten
 * @param int $user_id ID user
 * @return bool Sukses atau gagal
 */
function upsert_page_content($conn, $page_id, $content_key, $content_value, $user_id) {
    // Cek apakah konten sudah ada
    $check_sql = "SELECT id_page_content FROM page_content 
                  WHERE id_page = $1 AND content_key = $2 LIMIT 1";
    $check_result = pg_query_params($conn, $check_sql, array($page_id, $content_key));
    
    if ($check_result && pg_num_rows($check_result) > 0) {
        // UPDATE
        $update_sql = "UPDATE page_content 
                      SET content_value = $1, id_user = $2
                      WHERE id_page = $3 AND content_key = $4";
        return pg_query_params($conn, $update_sql, 
            array($content_value, $user_id, $page_id, $content_key));
    } else {
        // INSERT
        $insert_sql = "INSERT INTO page_content 
                      (id_page, content_key, content_type, content_value, id_user) 
                      VALUES ($1, $2, 'text', $3, $4)";
        return pg_query_params($conn, $insert_sql, 
            array($page_id, $content_key, $content_value, $user_id));
    }
}
?>