<?php

function db_fetch_all($conn, string $sql, array $params = []): array
{
    $result = pg_query_params($conn, $sql, $params);
    if (!$result) {
        return [];
    }

    $rows = pg_fetch_all($result);
    return $rows ?: [];
}

function db_fetch_one($conn, string $sql, array $params = []): ?array
{
    $result = pg_query_params($conn, $sql, $params);
    if (!$result || pg_num_rows($result) === 0) {
        return null;
    }

    return pg_fetch_assoc($result);
}

function build_limit_clause(?int $limit, array &$params): string
{
    if ($limit === null) {
        return '';
    }

    $params[] = $limit;
    $placeholder = '$' . count($params);
    return " LIMIT {$placeholder}";
}

function get_page_content($conn, string $pageName): array
{
    $sql = "
        SELECT pc.content_key, pc.content_type, pc.content_value
        FROM page_content pc
        JOIN pages p ON p.id_page = pc.id_page
        WHERE p.nama = $1
    ";

    $rows = db_fetch_all($conn, $sql, array($pageName));
    $content = [];

    foreach ($rows as $row) {
        $content[$row['content_key']] = $row;
    }

    return $content;
}

function get_settings($conn, array $settingNames = []): array
{
    $params = [];
    $conditions = '';

    if (!empty($settingNames)) {
        $placeholders = [];
        foreach ($settingNames as $index => $name) {
            $params[] = $name;
            $placeholders[] = '$' . count($params);
        }
        $conditions = 'WHERE setting_name IN (' . implode(',', $placeholders) . ')';
    }

    $sql = "SELECT setting_name, setting_type, setting_value FROM settings {$conditions}";
    $rows = db_fetch_all($conn, $sql, $params);

    $settings = [];
    foreach ($rows as $row) {
        $settings[$row['setting_name']] = $row;
    }

    return $settings;
}

function get_logos($conn): array
{
    $sql = "SELECT id_logo, nama_logo, media_path FROM logo ORDER BY id_logo ASC";
    return db_fetch_all($conn, $sql);
}

function get_penelitian($conn, ?int $limit = null): array
{
    $params = [];
    $limitClause = build_limit_clause($limit, $params);

    $sql = "
        SELECT * FROM view_penelitian ORDER BY tahun DESC, id_penelitian DESC
        {$limitClause}
    ";

    return db_fetch_all($conn, $sql, $params);
}

function get_pengabdian($conn, ?int $limit = null): array
{
    $params = [];
    $limitClause = build_limit_clause($limit, $params);

    $sql = "
        SELECT * FROM view_pengabdian ORDER BY tahun DESC
        {$limitClause}
    ";

    return db_fetch_all($conn, $sql, $params);
}

function get_galeri_items($conn, ?int $limit = null): array
{
    $params = [];
    $limitClause = build_limit_clause($limit, $params);

    $sql = "
        SELECT id_galeri, judul, deskripsi, media_path, tanggal_kegiatan
        FROM galeri
        ORDER BY tanggal_kegiatan DESC NULLS LAST, id_galeri DESC
        {$limitClause}
    ";

    return db_fetch_all($conn, $sql, $params);
}

function get_agenda_items($conn, ?int $limit = null): array
{
    $params = [];
    $limitClause = build_limit_clause($limit, $params);

    $sql = "
        SELECT id_agenda, judul_agenda, deskripsi, tanggal_agenda, status
        FROM agenda
        ORDER BY tanggal_agenda DESC
        {$limitClause}
    ";

    return db_fetch_all($conn, $sql, $params);
}

function get_sarana_items($conn): array
{
    $sql = "
        SELECT id_sarana, nama_sarana, media_path
        FROM sarana
        ORDER BY id_sarana DESC
    ";

    return db_fetch_all($conn, $sql);
}

function get_social_links($conn): array
{
    $sql = "
        SELECT nama_sosialmedia, platform, url, username
        FROM sosial_media
        ORDER BY id_sosialmedia ASC
    ";

    return db_fetch_all($conn, $sql);
}

function get_struktur_anggota($conn): array
{
    $sql = "
        SELECT al.id_anggota,
               al.jabatan,
               d.nama_dosen,
               d.media_path
        FROM anggota_lab al
        JOIN dosen d ON d.id_dosen = al.id_dosen
        ORDER BY al.id_anggota ASC
    ";

    return db_fetch_all($conn, $sql);
}

function save_konsultatif_message($conn, string $nama, string $pesan): bool
{
    $sql = "
        INSERT INTO konsultatif (nama_pengirim, isi_pesan)
        VALUES ($1, $2)
    ";

    return (bool) pg_query_params($conn, $sql, array($nama, $pesan));
}

/**
 * Ambil visibility settings untuk home page
 */

if (!function_exists('get_home_visibility')) {
    function get_home_visibility($conn) {
        $query = "
            SELECT pc.content_key, pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'home' AND pc.content_key LIKE 'show_%'
        ";
        
        $result = pg_query($conn, $query);
        $visibility = [];
        
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $visibility[$row['content_key']] = ($row['content_value'] === 'true');
            }
        }
        
        return $visibility;
    }
}
?>