<?php
// File: app/helpers/agenda_helper.php
// Helper untuk modul Agenda

if (!function_exists('agenda_ensure_page')) {
    function agenda_ensure_page($conn, string $name): int
    {
        $pageRes = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1 LIMIT 1", array($name));
        if ($pageRes && pg_num_rows($pageRes) > 0) {
            return (int) pg_fetch_result($pageRes, 0, 'id_page');
        }
        $insert = pg_query_params($conn, "INSERT INTO pages (nama) VALUES ($1) RETURNING id_page", array($name));
        return (int) pg_fetch_result($insert, 0, 'id_page');
    }
}

if (!function_exists('agenda_upsert_content')) {
    function agenda_upsert_content($conn, int $pageId, string $key, string $value, int $userId): bool
    {
        $check = pg_query_params($conn, "SELECT id_page_content FROM page_content WHERE id_page = $1 AND content_key = $2 LIMIT 1", array($pageId, $key));
        if ($check && pg_num_rows($check) > 0) {
            return (bool) pg_query_params($conn,
                "UPDATE page_content SET content_type='text', content_value = $1, id_user = $2 WHERE id_page = $3 AND content_key = $4",
                array($value, $userId, $pageId, $key)
            );
        } else {
            return (bool) pg_query_params($conn,
                "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) VALUES ($1, $2, 'text', $3, $4)",
                array($pageId, $key, $value, $userId)
            );
        }
    }
}

if (!function_exists('get_agenda_items')) {
    /**
     * Mengembalikan array agenda terbaru.
     * Optional: $onlyActive = true akan filter status = true.
     */
    function get_agenda_items($conn, bool $onlyActive = false)
    {
        $sql = "SELECT id_agenda, judul_agenda, deskripsi, tanggal_agenda, status FROM agenda";
        if ($onlyActive) $sql .= " WHERE status = true";
        $sql .= " ORDER BY tanggal_agenda DESC";
        $res = pg_query($conn, $sql);
        $items = [];
        if ($res && pg_num_rows($res) > 0) {
            while ($r = pg_fetch_assoc($res)) $items[] = $r;
        }
        return $items;
    }
}

if (!function_exists('get_agenda_paginated')) {
    function get_agenda_paginated($conn, int $limit, int $offset)
    {
        $sql = "
            SELECT id_agenda, judul_agenda, deskripsi, tanggal_agenda, status
            FROM agenda
            ORDER BY tanggal_agenda DESC
            LIMIT $1 OFFSET $2
        ";

        $res = pg_query_params($conn, $sql, array($limit, $offset));

        $items = [];
        if ($res && pg_num_rows($res) > 0) {
            while ($r = pg_fetch_assoc($res)) {
                $items[] = $r;
            }
        }
        return $items;
    }
}


if (!function_exists('get_agenda_count')) {
    function get_agenda_count($conn)
    {
        $sql = "SELECT COUNT(*) AS total FROM agenda";
        $res = pg_query($conn, $sql);
        if ($res) {
            return (int) pg_fetch_result($res, 0, 'total');
        }
        return 0;
    }
}
