<?php
// File: admin/proses/proses_agenda.php
session_start();
include '../../config/koneksi.php';
require_once __DIR__ . '/../../app/helpers/agenda_helper.php';

$id_user = $_SESSION['id_user'] ?? 1;

// helper convert status param -> boolean string
function agenda_bool_param($value): string
{
    return ($value === "1" || strtolower($value) === "true" || $value === true) ? 'true' : 'false';
}

/* ===================================================================
   1) TAMBAH AGENDA
   expects: tambah, judul_agenda, deskripsi, tanggal_agenda, status
   =================================================================== */
if (isset($_POST['tambah'])) {

    $tanggal    = trim($_POST['tanggal_agenda'] ?? '');
    $judul      = trim($_POST['judul_agenda'] ?? '');
    $deskripsi  = trim($_POST['deskripsi'] ?? '');
    $status_raw = $_POST['status'] ?? '1';
    $status     = agenda_bool_param($status_raw);

    if ($judul === '' || $tanggal === '') {
        echo "<script>alert('Judul dan tanggal wajib diisi!'); window.location.href='../galeri/tambah_agenda.php';</script>";
        exit();
    }

    $query = "INSERT INTO agenda (tanggal_agenda, judul_agenda, deskripsi, status, id_user)
              VALUES ($1, $2, $3, $4::boolean, $5)";
    $params = array($tanggal, $judul, $deskripsi, $status, $id_user);
    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Agenda berhasil ditambahkan!'); window.location.href='../galeri/tambah_agenda.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan agenda!'); window.location.href='../galeri/tambah_agenda.php';</script>";
    }
    exit();
}

/* ===================================================================
   2) EDIT AGENDA
   expects: edit, id_agenda, judul_agenda, deskripsi, tanggal_agenda, status
   =================================================================== */
if (isset($_POST['edit'])) {
    $id_agenda  = $_POST['id_agenda'] ?? null;
    $tanggal    = trim($_POST['tanggal_agenda'] ?? '');
    $judul      = trim($_POST['judul_agenda'] ?? '');
    $deskripsi  = trim($_POST['deskripsi'] ?? '');
    $status_raw = $_POST['status'] ?? '1';
    $status     = agenda_bool_param($status_raw);

    if (!$id_agenda) {
        echo "<script>alert('Parameter id_agenda hilang!'); window.location.href='../galeri/tambah_agenda.php';</script>"; exit();
    }

    $query = "UPDATE agenda SET tanggal_agenda = $1, judul_agenda = $2, deskripsi = $3, status = $4::boolean, id_user = $5 WHERE id_agenda = $6";
    $params = array($tanggal, $judul, $deskripsi, $status, $id_user, $id_agenda);
    $res = pg_query_params($conn, $query, $params);

    if ($res) {
        echo "<script>alert('Agenda berhasil diperbarui!'); window.location.href='../galeri/tambah_agenda.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui agenda!'); window.location.href='../galeri/tambah_agenda.php';</script>";
    }
    exit();
}

/* ===================================================================
   3) HAPUS AGENDA
   expects: hapus, id_agenda
   =================================================================== */
if (isset($_POST['hapus'])) {
    $id_agenda = $_POST['id_agenda'] ?? null;
    if (!$id_agenda) {
        echo "<script>alert('Parameter id_agenda hilang!'); window.location.href='../galeri/tambah_agenda.php';</script>"; exit();
    }

    $res = pg_query_params($conn, "DELETE FROM agenda WHERE id_agenda = $1", array($id_agenda));
    if ($res) {
        echo "<script>alert('Agenda berhasil dihapus!'); window.location.href='../galeri/tambah_agenda.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus agenda!'); window.location.href='../galeri/tambah_agenda.php';</script>";
    }
    exit();
}

/* ===================================================================
   4) UPDATE PAGE CONTENT (section title + description)
   expects: edit_page, judul_page, deskripsi_page
   =================================================================== */
if (isset($_POST['edit_page'])) {
    $judul_pc     = trim($_POST['judul_page'] ?? '');
    $deskripsi_pc = trim($_POST['deskripsi_page'] ?? '');
    $page_key     = 'galeri_agenda';

    $pageId = agenda_ensure_page($conn, $page_key);
    agenda_upsert_content($conn, $pageId, 'section_title', $judul_pc, $id_user);
    agenda_upsert_content($conn, $pageId, 'section_description', $deskripsi_pc, $id_user);

    echo "<script>alert('Konten halaman Agenda berhasil diperbarui!'); window.location.href='../galeri/tambah_agenda.php';</script>";
    exit();
}

echo "<script>alert('Aksi tidak dikenali.'); window.location.href='../galeri/tambah_agenda.php';</script>";
exit();
