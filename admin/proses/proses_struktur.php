<?php
// File: admin/proses/proses_struktur.php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// Folder upload
$upload_dir = "../../uploads/dosen/";
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

// Foto default
$default_foto = "../../assets/site/img/struktur/default.jpg";

// Ambil id_user
$id_user = $_SESSION['id_user'] ?? 1;

// -----------------------------
// 0) Update page content (judul + deskripsi)
// -----------------------------
if (isset($_POST['edit_page_content']) && isset($_POST['submit'])) {
    $judul_page     = trim($_POST['judul_page'] ?? '');
    $deskripsi_page = trim($_POST['deskripsi_page'] ?? '');

    // GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
    $id_page = ensure_page_exists($conn, 'profil_struktur');

    if (!$id_page) {
        echo "<script>alert('Gagal membuat atau mendapatkan halaman Struktur Organisasi!'); 
              window.location.href='../profil/edit_struktur.php';</script>";
        exit();
    }

    // Gunakan helper untuk upsert content
    $resultJudul = upsert_page_content($conn, $id_page, 'section_title', $judul_page, $id_user);
    $resultDeskripsi = upsert_page_content($conn, $id_page, 'section_description', $deskripsi_page, $id_user);

    if ($resultJudul && $resultDeskripsi) {
        echo "<script>alert('Konten halaman Struktur Organisasi berhasil diperbarui!'); 
              window.location.href='../profil/edit_struktur.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui konten halaman!'); 
              window.location.href='../profil/edit_struktur.php';</script>";
    }
    exit();
}

// -----------------------------
// 1) EDIT anggota (nama, jabatan, foto opsional)
// -----------------------------
if (isset($_POST['edit'])) {
    $id_anggota = $_POST['id_anggota'] ?? null;
    $id_dosen   = $_POST['id_dosen'] ?? null;
    $nama       = trim($_POST['nama_dosen'] ?? '');
    $jabatan    = trim($_POST['jabatan'] ?? '');

    if (!$id_anggota || !$id_dosen) {
        echo "<script>alert('Parameter tidak lengkap.'); window.location.href='../profil/edit_struktur.php';</script>";
        exit();
    }


    $new_file = null;

    //Jika Uploan foto baru
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $file_size = $_FILES['foto']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

        // Validasi file
        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format foto tidak valid! Hanya JPG, PNG, GIF, SVG, WEBP yang diizinkan.'); 
                  window.location.href='../profil/edit_struktur.php';</script>";
            exit();
        }

        if ($file_size > 5 * 1024 * 1024) { // 5MB max
            echo "<script>alert('Ukuran file terlalu besar! Maksimal 5MB.'); 
                  window.location.href='../profil/edit_struktur.php';</script>";
            exit();
        }


        // Ambil foto lama untuk dihapus
        $old = pg_fetch_assoc(pg_query_params(
            $conn,
            "SELECT media_path FROM dosen WHERE id_dosen = $1",
            array($id_dosen)
        ));

        $old_file = $old['media_path'] ?? null;

        // Hapus foto lama jika bukan default
        if (!empty($old_file) && $old_file !== $default_foto) {
            $old_path = $upload_dir . $old_file;
            if (file_exists($old_path)) unlink($old_path);
        }

        // Upload foto baru
        $new_file = "dosen_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        move_uploaded_file($tmp_file, $upload_dir . $new_file);
    }

    // Call Stored Procedure
    pg_query_params(
        $conn,
        "CALL sp_edit_anggota($1, $2, $3, $4, $5, $6)",
        array($id_anggota, $id_dosen, $nama, $jabatan, $new_file, $id_user)
    );


    echo "<script>alert('Data anggota berhasil diperbarui!'); window.location.href='../profil/edit_struktur.php';</script>";
    exit();
}

/* ============================================================
   2) TAMBAH ANGGOTA BARU
   ============================================================ */
if (isset($_POST['tambah'])){

    $nama    = trim($_POST['nama_dosen']);
    $jabatan = trim($_POST['jabatan']);
    $new_file = null;

    // Upload foto
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $file_name = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','gif','svg','webp'];
        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format foto tidak valid!'); window.location.href='../profil/edit_struktur.php';</script>";
            exit();
        }

        $new_file = "dosen_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        move_uploaded_file($tmp_file, $upload_dir . $new_file);
    }

    // CALL PROCEDURE TAMBAH ANGGOTA
    pg_query_params(
        $conn,
        "CALL sp_tambah_anggota($1,$2,$3,$4)",
        [$nama, $new_file, $jabatan, $id_user]
    );

    echo "<script>alert('Anggota baru berhasil ditambahkan!'); window.location.href='../profil/edit_struktur.php';</script>";
    exit();
}

// -----------------------------
// 3) HAPUS anggota
// -----------------------------
if (isset($_POST['hapus'])) {
    $id_anggota = $_POST['id_anggota'] ?? null;

    if (!$id_anggota) {
        echo "<script>alert('Parameter tidak lengkap.'); window.location.href='../profil/edit_struktur.php';</script>";
        exit();
    }

    // 1. Ambil id_dosen + nama file foto
    $q = pg_fetch_assoc(pg_query_params(
        $conn,
        "SELECT d.media_path 
         FROM dosen d
         JOIN anggota_lab a ON a.id_dosen = d.id_dosen
         WHERE a.id_anggota = $1",
        [$id_anggota]
    ));

    $foto = $q['media_path'] ?? null;

    // 2. Hapus foto fisik jika bukan default
    if (!empty($foto) && $foto !== $default_foto) {
        $path = $upload_dir . $foto;
        if (file_exists($path)) unlink($path);
    }

    // 3. Hapus data via Stored Procedure
    pg_query_params($conn, "CALL sp_hapus_anggota($1)", [$id_anggota]);

    echo "<script>alert('Anggota berhasil dihapus!'); window.location.href='../profil/edit_struktur.php';</script>";
    exit();
}

?>