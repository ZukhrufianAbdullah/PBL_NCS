<?php
// File: admin/proses/proses_agenda.php
session_start();
include '../../config/koneksi.php';

$id_user = $_SESSION['id_user'] ?? 1;

// ambil id_page untuk halaman 'galeri_agenda'
    $sqlPage = "SELECT id_page FROM pages WHERE nama = 'galeri_agenda' LIMIT 1";
    $resultPage = pg_query($conn, $sqlPage);

    if (!$resultPage || pg_num_rows($resultPage) === 0) {
        echo "<script>
                alert('Halaman Agenda tidak ditemukan di tabel pages!');
                window.location.href = '../galeri/edit_agenda.php';
                </script>";
        exit();
    }
    $page = pg_fetch_assoc($resultPage);
    $id_page = $page['id_page'];


// Kelola konten halaman Agenda
if (isset($_POST['submit_judul_deskripsi_agenda'])) {
    //Ambil input dari form
    $judul_agenda   = ($_POST['judul_agenda']);
    $deskripsi_agenda = ($_POST['deskripsi_agenda']);

    //Update atau Insert judul agenda
    $checkJudulAgenda = "SELECT id_page_content FROM page_content
                WHERE id_page = $1 AND content_key = 'judul_agenda' LIMIT 1";
    $checkResultJudulAgenda = pg_query_params($conn, $checkJudulAgenda, array($id_page));

    if (pg_num_rows($checkResultJudulAgenda) > 0) {
        // UPDATE
        $updateJudulAgenda = "UPDATE page_content
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = 'judul_agenda'";
        $resultJudulAgenda = pg_query_params($conn, $updateJudulAgenda, array($judul_agenda, $id_user, $id_page));
    } else {
        // INSERT
        $insertJudulAgenda = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, 'judul_agenda', 'text', $2, $3)";
        $resultJudulAgenda = pg_query_params($conn, $insertJudulAgenda, array($id_page, $judul_agenda, $id_user));
    }

    // Update atau Insert deskripsi agenda
    $checkDeskripsiAgenda = "SELECT id_page_content FROM page_content
                WHERE id_page = $1 AND content_key = 'deskripsi_agenda' LIMIT 1";
    $checkResultDeskripsiAgenda = pg_query_params($conn, $checkDeskripsiAgenda, array($id_page));

    if (pg_num_rows($checkResultDeskripsiAgenda) > 0) {
        // UPDATE
        $updateDeskripsiAgenda = "UPDATE page_content
                   SET content_value = $1, id_user = $2
                   WHERE id_page = $3 AND content_key = 'deskripsi_agenda'";
        $resultDeskripsiAgenda = pg_query_params($conn, $updateDeskripsiAgenda, array($deskripsi_agenda, $id_user, $id_page));
    } else {
        // INSERT
        $insertDeskripsiAgenda = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                   VALUES ($1, 'deskripsi_agenda', 'text', $2, $3)";
        $resultDeskripsiAgenda = pg_query_params($conn, $insertDeskripsiAgenda, array($id_page, $deskripsi_agenda, $id_user));
    }

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
} else {
    echo "<script>
            alert('Aksi tidak dikenali.');
            window.location.href = '../galeri/edit_agenda.php';
          </script>";
    exit();
}

?>

