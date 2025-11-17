<?php
include '../../config/koneksi.php';
session_start();

$id_user = $_SESSION['id_user'] ?? 1; // fallback jika tidak ada session

// Fungsi konversi status form menjadi boolean PostgreSQL
function toBoolean($value) {
    return ($value === "1" || strtolower($value) === "true") ? 'TRUE' : 'FALSE';
}

// =============================================================
// 1. TAMBAH AGENDA
// =============================================================
if (isset($_POST['tambah'])) {

    $tanggal    = $_POST['tanggal'];
    $judul      = $_POST['judul'];
    $deskripsi  = $_POST['deskripsi'];
    $status_raw = $_POST['status'];
    $status     = toBoolean($status_raw);

    $query = "
        INSERT INTO agenda (tanggal, judul, deskripsi, status, id_user)
        VALUES ($1, $2, $3, $status, $4)
    ";

    $result = pg_query_params($conn, $query, array($tanggal, $judul, $deskripsi, $id_user));

    if ($result) {
        echo "<script>alert('Agenda berhasil ditambahkan!'); window.location.href='../galeri/edit_agenda.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan agenda!'); window.location.href='../galeri/edit_agenda.php';</script>";
    }
    exit();
}


// =============================================================
// 2. EDIT / UPDATE AGENDA
// =============================================================
if (isset($_POST['edit'])) {

    $id_agenda  = $_POST['id_agenda'];
    $tanggal    = $_POST['tanggal'];
    $judul      = $_POST['judul'];
    $deskripsi  = $_POST['deskripsi'];
    $status_raw = $_POST['status'];
    $status     = toBoolean($status_raw);

    $query = "
        UPDATE agenda
        SET tanggal = $1, judul = $2, deskripsi = $3, status = $status, id_user = $4
        WHERE id_agenda = $5
    ";

    $result = pg_query_params($conn, $query, array($tanggal, $judul, $deskripsi, $id_user, $id_agenda));

    if ($result) {
        echo "<script>alert('Agenda berhasil diperbarui!'); window.location.href='../galeri/edit_agenda.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui agenda!'); window.location.href='../galeri/edit_agenda.php';</script>";
    }
    exit();
}


// =============================================================
// 3. HAPUS AGENDA
// =============================================================
if (isset($_POST['hapus'])) {

    $id_agenda = $_POST['id_agenda'];

    $query = "DELETE FROM agenda WHERE id_agenda = $1";
    $result = pg_query_params($conn, $query, array($id_agenda));

    if ($result) {
        echo "<script>alert('Agenda berhasil dihapus!'); window.location.href='../galeri/edit_agenda.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus agenda!'); window.location.href='../galeri/edit_agenda.php';</script>";
    }
    exit();
}

?>