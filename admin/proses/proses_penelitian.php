<?php
// Memanggil file koneksi database
include '../../config/koneksi.php';

// Memanggil helper page untuk manajemen halaman & konten
include __DIR__ . "/../../app/helpers/page_helper.php";

// Memulai session untuk mengambil data user login
session_start();

// Mengambil id_user dari session (fallback ke 1 jika tidak ada)
$id_user = $_SESSION['id_user'] ?? 1;

// Menentukan folder upload file PDF penelitian
$upload_dir = "../../uploads/penelitian/";

// Membuat folder upload jika belum ada
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ==================================================================
// 1. TAMBAH PENELITIAN
// ==================================================================
if (isset($_POST['tambah'])) {

    // Ambil dan rapikan data dari form
    $judul_penelitian = trim($_POST['judul_penelitian']);
    $tahun            = (int) $_POST['tahun'];
    $deskripsi        = $_POST['deskripsi'];
    $id_author        = !empty($_POST['id_author']) ? (int) $_POST['id_author'] : null;

    // Variabel untuk menyimpan nama file PDF (opsional)
    $mediapath = null;

    // Proses upload file PDF jika ada
    if (!empty($_FILES['pdf']['name'])) {

        // Ambil informasi file upload
        $file_name = $_FILES['pdf']['name'];
        $tmp_file  = $_FILES['pdf']['tmp_name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validasi hanya file PDF yang diperbolehkan
        if ($ext !== 'pdf') {
            echo "<script>
                    alert('File harus berupa PDF!');
                    window.location.href='../arsip/edit_penelitian.php';
                  </script>";
            exit();
        }

        // Generate nama file PDF baru
        $new_pdf = "penelitian_" . time() . ".pdf";

        // Upload file ke folder penelitian
        move_uploaded_file($tmp_file, $upload_dir . $new_pdf);

        // Simpan nama file ke database
        $mediapath = $new_pdf;
    }

    // Query insert data penelitian
    $query = "
        INSERT INTO penelitian 
        (judul_penelitian, tahun, deskripsi, media_path, id_author, id_user)
        VALUES ($1, $2, $3, $4, $5, $6)
    ";

    // Parameter query
    $params = array(
        $judul_penelitian,
        $tahun,
        $deskripsi,
        $mediapath,
        $id_author,
        $id_user
    );

    // Eksekusi query
    $result = pg_query_params($conn, $query, $params);

    // Notifikasi hasil proses
    echo $result
        ? "<script>
                alert('Penelitian berhasil ditambahkan!');
                window.location.href='../arsip/edit_penelitian.php';
           </script>"
        : "<script>
                alert('Gagal menambahkan penelitian!');
                window.location.href='../arsip/edit_penelitian.php';
           </script>";
    exit();
}

// ==================================================================
// 2. UPDATE PENELITIAN
// ==================================================================
if (isset($_POST['edit'])) {

    // Ambil data penelitian yang akan diupdate
    $id_penelitian    = (int) $_POST['id_penelitian'];
    $judul_penelitian = trim($_POST['judul_penelitian']);
    $tahun            = (int) $_POST['tahun'];
    $deskripsi        = $_POST['deskripsi'];
    $id_author        = !empty($_POST['id_author']) ? (int) $_POST['id_author'] : null;

    // Jika user mengupload file PDF baru
    if (!empty($_FILES['pdf']['name'])) {

        // Ambil informasi file upload
        $file_name = $_FILES['pdf']['name'];
        $tmp_file  = $_FILES['pdf']['tmp_name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validasi hanya PDF yang diizinkan
        if ($ext !== 'pdf') {
            echo "<script>
                    alert('File harus berupa PDF!');
                    window.location.href='../arsip/edit_penelitian.php';
                  </script>";
            exit();
        }

        // Generate nama file PDF baru
        $new_pdf = "penelitian_" . time() . ".pdf";

        // Upload file PDF baru
        move_uploaded_file($tmp_file, $upload_dir . $new_pdf);

        // Query update termasuk file PDF baru
        $query = "
            UPDATE penelitian
            SET judul_penelitian = $1, 
                tahun = $2, 
                deskripsi = $3, 
                media_path = $4, 
                id_author = $5, 
                id_user = $6
            WHERE id_penelitian = $7
        ";

        // Parameter query update
        $params = array(
            $judul_penelitian,
            $tahun,
            $deskripsi,
            $new_pdf,
            $id_author,
            $id_user,
            $id_penelitian
        );

    } else {

        // Query update tanpa mengganti file PDF
        $query = "
            UPDATE penelitian
            SET judul_penelitian = $1, 
                tahun = $2, 
                deskripsi = $3, 
                id_author = $4, 
                id_user = $5
            WHERE id_penelitian = $6
        ";

        // Parameter query update
        $params = array(
            $judul_penelitian,
            $tahun,
            $deskripsi,
            $id_author,
            $id_user,
            $id_penelitian
        );
    }

    // Eksekusi query update
    $result = pg_query_params($conn, $query, $params);

    // Notifikasi hasil proses
    echo $result
        ? "<script>
                alert('Penelitian berhasil diperbarui!');
                window.location.href='../arsip/edit_penelitian.php';
           </script>"
        : "<script>
                alert('Gagal memperbarui penelitian!');
                window.location.href='../arsip/edit_penelitian.php';
           </script>";
    exit();
}

// ==================================================================
// 3. HAPUS PENELITIAN
// ==================================================================
if (isset($_POST['hapus'])) {

    // Ambil ID penelitian yang akan dihapus
    $id_penelitian = $_POST['id_penelitian'];

    // Ambil nama file PDF dari database
    $q = "SELECT media_path FROM penelitian WHERE id_penelitian = $1";
    $get = pg_query_params($conn, $q, array($id_penelitian));
    $row = pg_fetch_assoc($get);

    // Jika ada file PDF, hapus dari server
    if ($row && $row['media_path']) {
        $file_path = $upload_dir . $row['media_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Query hapus data penelitian
    $query = "DELETE FROM penelitian WHERE id_penelitian = $1";
    $result = pg_query_params($conn, $query, array($id_penelitian));

    // Notifikasi hasil proses
    echo $result
        ? "<script>
                alert('Penelitian berhasil dihapus!');
                window.location.href='../arsip/edit_penelitian.php';
           </script>"
        : "<script>
                alert('Gagal menghapus penelitian!');
                window.location.href='../arsip/edit_penelitian.php';
           </script>";
    exit();
}

// ==================================================================
// 4. UPDATE PAGE CONTENT (Judul & Deskripsi Halaman)
// ==================================================================
if (isset($_POST['edit_page'])) {

    // Ambil judul dan deskripsi halaman
    $judul_pc     = $_POST['judul_page'];
    $deskripsi_pc = $_POST['deskripsi_page'];
    
    // Gunakan helper untuk mendapatkan atau membuat halaman penelitian
    $id_page = ensure_page_exists($conn, 'arsip_penelitian');
    
    // Jika gagal mendapatkan halaman
    if (!$id_page) {
        echo "<script>
                alert('Gagal membuat atau mendapatkan halaman Penelitian!');
                window.location.href='../arsip/edit_penelitian.php';
              </script>";
        exit();
    }

    // Simpan / update judul halaman
    $resultJudul = upsert_page_content(
        $conn,
        $id_page,
        'section_title',
        $judul_pc,
        $id_user
    );

    // Simpan / update deskripsi halaman
    $resultDeskripsi = upsert_page_content(
        $conn,
        $id_page,
        'section_description',
        $deskripsi_pc,
        $id_user
    );

    // Notifikasi hasil proses
    if ($resultJudul && $resultDeskripsi) {
        echo "<script>
                alert('Konten halaman Penelitian berhasil diperbarui!');
                window.location.href='../arsip/edit_penelitian.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman Penelitian!');
                window.location.href='../arsip/edit_penelitian.php';
              </script>";
    }
    exit();
}
?>
