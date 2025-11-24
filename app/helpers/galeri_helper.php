<?php

/* ============================================================
   HELPER GALERI - Mirip struktur agenda_helper
   Output pagination HARUS sama format dengan agenda
   ============================================================ */


/* ------------------------------------------------------------
   1. Ambil semua galeri (dipakai dashboard admin)
   ------------------------------------------------------------ */
function get_galeri_items_all($conn)
{
    $sql = "SELECT * FROM galeri ORDER BY tanggal_kegiatan DESC";
    $res = pg_query($conn, $sql);

    if (!$res) return [];

    return pg_fetch_all($res) ?: [];
}


/* ------------------------------------------------------------
   2. Pagination utama (dipakai front-end)
   RETURN FORMAT:
   [
      'data' => [...],
      'total' => 20,
      'total_pages' => 4,
      'current_page' => 1,
      'limit' => 6,
      'offset' => 0
   ]
   ------------------------------------------------------------ */
function get_galeri_paginated($conn, int $page = 1, int $limit = 6)
{
    $page = max(1, $page);
    $offset = ($page - 1) * $limit;

    // Ambil total data
    $resTotal = pg_query($conn, "SELECT COUNT(*) AS total FROM galeri");
    $total = ($resTotal) ? (int) pg_fetch_result($resTotal, 0, 'total') : 0;

    // Ambil data galeri
    $sql = "SELECT * FROM galeri ORDER BY tanggal_kegiatan DESC LIMIT $1 OFFSET $2";
    $res = pg_query_params($conn, $sql, array($limit, $offset));
    $rows = ($res) ? pg_fetch_all($res) : [];

    // Hitung total halaman
    $total_pages = ($limit > 0) ? ceil($total / $limit) : 1;

    return [
        'data' => $rows ?: [],
        'total' => $total,
        'total_pages' => $total_pages,
        'current_page' => $page,
        'limit' => $limit,
        'offset' => $offset
    ];
}


/* ------------------------------------------------------------
   3. Pastikan page galeri terdaftar di tabel pages
   ------------------------------------------------------------ */
function galeri_ensure_page($conn, string $pageName): int
{
    $q = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", array($pageName));

    if ($q && pg_num_rows($q) > 0)
        return (int) pg_fetch_result($q, 0, 'id_page');

    $insert = pg_query_params(
        $conn,
        "INSERT INTO pages (nama) VALUES ($1) RETURNING id_page",
        array($pageName)
    );

    return (int) pg_fetch_result($insert, 0, 'id_page');
}


/* ------------------------------------------------------------
   4. Update / insert page_content
   ------------------------------------------------------------ */
function galeri_upsert_page_content($conn, int $pageId, string $key, string $value, int $userId)
{
    $check = pg_query_params(
        $conn,
        "SELECT id_page_content FROM page_content 
         WHERE id_page = $1 AND content_key = $2",
        array($pageId, $key)
    );

    if ($check && pg_num_rows($check) > 0) {
        pg_query_params(
            $conn,
            "UPDATE page_content SET content_value = $1, id_user = $2 
             WHERE id_page = $3 AND content_key = $4",
            array($value, $userId, $pageId, $key)
        );
    } else {
        pg_query_params(
            $conn,
            "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
             VALUES ($1, $2, 'text', $3, $4)",
            array($pageId, $key, $value, $userId)
        );
    }
}


/* ------------------------------------------------------------
   5. Ambil section title + description
   ------------------------------------------------------------ */
function get_galeri_page_content($conn)
{
    $pageId = galeri_ensure_page($conn, 'galeri_dokumentasi');

    $res = pg_query_params(
        $conn,
        "SELECT content_key, content_value FROM page_content WHERE id_page = $1",
        array($pageId)
    );

    $data = [];
    if ($res && pg_num_rows($res) > 0) {
        while ($row = pg_fetch_assoc($res)) {
            $data[$row['content_key']] = $row['content_value'];
        }
    }
    return $data;
}
