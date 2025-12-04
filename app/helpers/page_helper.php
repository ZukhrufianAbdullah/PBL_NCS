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

/**
 * Fungsi untuk menginisialisasi halaman home dan pengaturan section
 * Ini akan otomatis dipanggil jika belum ada data
 */
function init_home_page_and_sections($conn, $user_id = 1) {
    // 1. Buat halaman 'home' jika belum ada
    $home_page_id = ensure_page_exists($conn, 'home');
    
    if ($home_page_id === 0) {
        return false; // Gagal membuat halaman
    }
    
    // 2. Tambahkan deskripsi default jika belum ada
    $check_desc = pg_query($conn, 
        "SELECT id_page_content FROM page_content 
         WHERE id_page = $home_page_id AND content_key = 'deskripsi'");
    
    if (pg_num_rows($check_desc) == 0) {
        $default_desc = null;
        
        pg_query_params($conn, 
            "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) 
             VALUES ($1, 'deskripsi', 'text', $2, $3)",
            array($home_page_id, $default_desc, $user_id));
    }
    
    // 3. Tambahkan pengaturan visibility untuk setiap section
    $sections = [
        'show_visi_misi' => ['value' => 'true', 'label' => 'Visi & Misi'],
        'show_logo' => ['value' => 'true', 'label' => 'Logo'],
        'show_struktur' => ['value' => 'true', 'label' => 'Struktur Organisasi'],
        'show_agenda' => ['value' => 'true', 'label' => 'Agenda'],
        'show_galeri' => ['value' => 'true', 'label' => 'Galeri'],
        'show_penelitian' => ['value' => 'true', 'label' => 'Penelitian'],
        'show_pengabdian' => ['value' => 'true', 'label' => 'Pengabdian kepada Masyarakat'],
        'show_sarana' => ['value' => 'true', 'label' => 'Sarana & Prasarana']
    ];
    
    foreach ($sections as $key => $data) {
        $check = pg_query($conn, 
            "SELECT id_page_content FROM page_content 
             WHERE id_page = $home_page_id AND content_key = '$key'");
        
        if (pg_num_rows($check) == 0) {
            pg_query_params($conn, 
                "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) 
                 VALUES ($1, $2, 'boolean', $3, $4)",
                array($home_page_id, $key, $data['value'], $user_id));
        }
    }
    
    return $home_page_id;
}
?>