<?php
// File: admin/proses/proses_agenda.php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

$id_user = $_SESSION['id_user'] ?? 1;

// GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
$id_page = ensure_page_exists($conn, 'galeri_agenda');

if (!$id_page) {
    echo "<script>
            alert('Gagal membuat atau mendapatkan halaman Agenda!');
            window.location.href = '../galeri/edit_agenda.php';
          </script>";
    exit();
}

// Kelola konten halaman Agenda
if (isset($_POST['submit_judul_deskripsi_agenda'])) {
    //Ambil input dari form
    $judul_agenda   = ($_POST['judul_agenda']);
    $deskripsi_agenda = ($_POST['deskripsi_agenda']);

    // Gunakan helper untuk upsert content dengan section_title dan section_description
    $resultJudulAgenda = upsert_page_content($conn, $id_page, 'section_title', $judul_agenda, $id_user);
    $resultDeskripsiAgenda = upsert_page_content($conn, $id_page, 'section_description', $deskripsi_agenda, $id_user);

    // Cek hasil
    if ($resultJudulAgenda && $resultDeskripsiAgenda) {
        echo "<script>
                alert('Konten halaman Agenda berhasil diperbarui!');
                window.location.href = '../galeri/edit_agenda.php';
                </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman Agenda!');
                window.location.href = '../galeri/edit_agenda.php';
                </script>";
    }
    exit();
}

// TAMBAH AGENDA
elseif (isset($_POST['tambah_agenda'])) {
    $judul            = $_POST['judul'];
    $deskripsi        = $_POST['deskripsi'];
    $tanggal          = $_POST['tanggal'];
    $status           = ($_POST['status'] == "1") ? 'TRUE' : 'FALSE';

    $sqlInsert = "
        INSERT INTO agenda (judul, deskripsi, tanggal, status, id_user)
        VALUES ($1, $2, $3, $4, $5)
    ";

    $resultInsert = pg_query_params($conn, $sqlInsert, array(
        $judul,
        $deskripsi,
        $tanggal,
        $status,
        $id_user
    ));

    if ($resultInsert) {
        echo "<script>
                alert('Agenda berhasil ditambahkan!');
                window.location.href = '../galeri/edit_agenda.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambah agenda!');
                window.location.href = '../galeri/edit_agenda.php';
              </script>";
    }
    exit();
}

// UPDATE AGENDA
elseif (isset($_POST['edit_agenda'])) {
    $id_agenda        = $_POST['id_agenda'];
    $judul            = $_POST['judul'];
    $deskripsi        = $_POST['deskripsi'];
    $tanggal          = $_POST['tanggal'];
    $status           = ($_POST['status'] == "1") ? 'TRUE' : 'FALSE';
    
    $sqlUpdate = "
        UPDATE agenda
        SET judul = $1,
            deskripsi = $2,
            tanggal = $3,
            status = $4,
            id_user = $5
        WHERE id_agenda = $6
    ";

    $resultUpdate = pg_query_params($conn, $sqlUpdate, array(
        $judul,
        $deskripsi,
        $tanggal,
        $status,
        $id_user,
        $id_agenda
    ));

    if ($resultUpdate) {
        echo "<script>
                alert('Agenda berhasil diperbarui!');
                window.location.href = '../galeri/edit_agenda.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui agenda!');
                window.location.href = '../galeri/edit_agenda.php';
              </script>";
    }
    exit();
}

// HAPUS AGENDA
elseif (isset($_POST['hapus'])) {
    $id_agenda = $_POST['id_agenda'];

    $sqlDelete = "DELETE FROM agenda WHERE id_agenda = $1";
    $resultDelete = pg_query_params($conn, $sqlDelete, array($id_agenda));

    if ($resultDelete) {
        echo "<script>
                alert('Agenda berhasil dihapus!');
                window.location.href = '../galeri/edit_agenda.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus agenda!');
                window.location.href = '../galeri/edit_agenda.php';
              </script>";
    }
    exit();
} else {
    echo "<script>
            alert('Aksi tidak dikenali.');
            window.location.href = '../galeri/edit_agenda.php';
          </script>";
    exit();
}

?>