<?php
include '../../config/koneksi.php';
session_start();

$id_user = $_SESSION['id_user'] ?? 1;

// Folder upload PDF
$upload_dir = "../../uploads/penelitian/";


// =============================================================
// 1. TAMBAH PENELITIAN
// =============================================================
if (isset($_POST['tambah'])) {

    $judul_penelitian = $_POST['judul_penelitian'];
    $tahun            = $_POST['tahun'];
    $deskripsi        = $_POST['deskripsi'];

    // ---- Upload PDF (opsional) ----
    $mediapath = null;

    if (!empty($_FILES['pdf']['name'])) {

        $file_name = $_FILES['pdf']['name'];
        $tmp_file  = $_FILES['pdf']['tmp_name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($ext !== 'pdf') {
            echo "<script>alert('File harus berupa PDF!'); window.location.href='../arsip/edit_penelitian.php';</script>";
            exit();
        }

        // Rename agar unik
        $new_pdf = "penelitian_" . time() . ".pdf";
        move_uploaded_file($tmp_file, $upload_dir . $new_pdf);

        $mediapath = $new_pdf;
    }

    $query = "
        INSERT INTO penelitian (judul_penelitian, tahun, deskripsi, mediapath, id_user)
        VALUES ($1, $2, $3, $4, $5)
    ";

    $params = array($judul_penelitian, $tahun, $deskripsi, $mediapath, $id_user);
    $result = pg_query_params($conn, $query, $params);

    echo $result
        ? "<script>alert('Penelitian berhasil ditambahkan!'); window.location.href='../arsip/edit_penelitian.php';</script>"
        : "<script>alert('Gagal menambahkan penelitian!'); window.location.href='../arsip/edit_penelitian.php';</script>";

    exit();
}



// =============================================================
// 2. UPDATE PENELITIAN
// =============================================================
if (isset($_POST['edit'])) {

    $id_penelitian    = $_POST['id_penelitian'];
    $judul_penelitian = $_POST['judul_penelitian'];
    $tahun            = $_POST['tahun'];
    $deskripsi        = $_POST['deskripsi'];

    // Jika upload PDF baru
    if (!empty($_FILES['pdf']['name'])) {

        $file_name = $_FILES['pdf']['name'];
        $tmp_file  = $_FILES['pdf']['tmp_name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($ext !== 'pdf') {
            echo "<script>alert('File harus berupa PDF!'); window.location.href='../arsip/edit_penelitian.php';</script>";
            exit();
        }

        $new_pdf = "penelitian_" . time() . ".pdf";
        move_uploaded_file($tmp_file, $upload_dir . $new_pdf);

        // Update termasuk PDF baru
        $query = "
            UPDATE penelitian
            SET judul_penelitian = $1, tahun = $2, deskripsi = $3, mediapath = $4, id_user = $5
            WHERE id_penelitian = $6
        ";

        $params = array($judul_penelitian, $tahun, $deskripsi, $new_pdf, $id_user, $id_penelitian);

    } else {

        // Update tanpa PDF
        $query = "
            UPDATE penelitian
            SET judul_penelitian = $1, tahun = $2, deskripsi = $3, id_user = $4
            WHERE id_penelitian = $5
        ";

        $params = array($judul_penelitian, $tahun, $deskripsi, $id_user, $id_penelitian);
    }

    $result = pg_query_params($conn, $query, $params);

    echo $result
        ? "<script>alert('Penelitian berhasil diperbarui!'); window.location.href='../arsip/edit_penelitian.php';</script>"
        : "<script>alert('Gagal memperbarui penelitian!'); window.location.href='../arsip/edit_penelitian.php';</script>";

    exit();
}



// =============================================================
// 3. HAPUS PENELITIAN
// =============================================================
if (isset($_POST['hapus'])) {

    $id_penelitian = $_POST['id_penelitian'];

    // Ambil mediapath untuk hapus file
    $q = "SELECT mediapath FROM penelitian WHERE id_penelitian = $1";
    $get = pg_query_params($conn, $q, array($id_penelitian));
    $row = pg_fetch_assoc($get);

    if ($row && $row['mediapath']) {
        $file_path = $upload_dir . $row['mediapath'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Hapus data penelitian
    $query = "DELETE FROM penelitian WHERE id_penelitian = $1";
    $result = pg_query_params($conn, $query, array($id_penelitian));

    echo $result
        ? "<script>alert('Penelitian berhasil dihapus!'); window.location.href='../arsip/edit_penelitian.php';</script>"
        : "<script>alert('Gagal menghapus penelitian!'); window.location.href='../arsip/edit_penelitian.php';</script>";

    exit();
}

?>
