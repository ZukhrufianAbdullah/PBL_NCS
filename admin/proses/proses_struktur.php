<?php
// File: admin/proses/proses_struktur.php
session_start();
include '../../config/koneksi.php';

// Folder upload
$upload_dir = "../../uploads/dosen/";
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

// Foto default
$default_foto = "default.png";

// Ambil id_user
$id_user = $_SESSION['id_user'] ?? 1;

// -----------------------------
// 0) Update page content (judul + deskripsi)
// -----------------------------
if (isset($_POST['edit_page_content']) && isset($_POST['submit'])) {
    $judul_page     = trim($_POST['judul_page'] ?? '');
    $deskripsi_page = trim($_POST['deskripsi_page'] ?? '');
    $page_key       = "profil_struktur";

    // Ambil id_page
    $pg = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1 LIMIT 1", array($page_key));
    if (!$pg || pg_num_rows($pg) === 0) {
        echo "<script>alert('Halaman tidak ditemukan.'); window.location.href='../profil/edit_struktur.php';</script>"; exit();
    }
    $page = pg_fetch_assoc($pg);
    $id_page = $page['id_page'];

    // upsert judul
    $check = pg_query_params($conn, "SELECT id_page_content FROM page_content WHERE id_page=$1 AND content_key='judul' LIMIT 1", array($id_page));
    if (pg_num_rows($check) > 0) {
        pg_query_params($conn, "UPDATE page_content SET content_value=$1, id_user=$2 WHERE id_page=$3 AND content_key='judul'",
            array($judul_page, $id_user, $id_page));
    } else {
        pg_query_params($conn, "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) VALUES ($1,'judul','text',$2,$3)",
            array($id_page, $judul_page, $id_user));
    }

    // upsert deskripsi
    $check2 = pg_query_params($conn, "SELECT id_page_content FROM page_content WHERE id_page=$1 AND content_key='deskripsi' LIMIT 1", array($id_page));
    if (pg_num_rows($check2) > 0) {
        pg_query_params($conn, "UPDATE page_content SET content_value=$1, id_user=$2 WHERE id_page=$3 AND content_key='deskripsi'",
            array($deskripsi_page, $id_user, $id_page));
    } else {
        pg_query_params($conn, "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) VALUES ($1,'deskripsi','text',$2,$3)",
            array($id_page, $deskripsi_page, $id_user));
    }

    echo "<script>alert('Konten halaman Struktur Organisasi berhasil diperbarui!'); window.location.href='../profil/edit_struktur.php';</script>";
    exit();
}

// -----------------------------
// 1) EDIT anggota (nama, jabatan, foto opsional) - DIPERBAIKI
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

    // PERBAIKAN: Handle file upload dengan lebih baik
    $new_file = null;
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $file_size = $_FILES['foto']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','svg','webp'];
        
        // Validasi file
        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format foto tidak valid! Hanya JPG, PNG, GIF, SVG, WEBP yang diizinkan.'); window.location.href='../profil/edit_struktur.php';</script>"; 
            exit();
        }
        
        if ($file_size > 5 * 1024 * 1024) { // 5MB max
            echo "<script>alert('Ukuran file terlalu besar! Maksimal 5MB.'); window.location.href='../profil/edit_struktur.php';</script>"; 
            exit();
        }

        // Generate unique filename
        $new_file = "dosen_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        
        if (!move_uploaded_file($tmp_file, $upload_dir . $new_file)) {
            echo "<script>alert('Gagal mengupload foto.'); window.location.href='../profil/edit_struktur.php';</script>"; 
            exit();
        }

        // Ambil foto lama untuk dihapus
        $old = pg_fetch_assoc(pg_query_params($conn, "SELECT media_path FROM dosen WHERE id_dosen = $1", array($id_dosen)));
        if (!empty($old['media_path']) && $old['media_path'] !== $default_foto) {
            $oldpath = $upload_dir . $old['media_path'];
            if (file_exists($oldpath)) @unlink($oldpath);
        }

        // Update dengan foto baru
        pg_query_params($conn, "UPDATE dosen SET media_path = $1, nama_dosen=$2, id_user=$3 WHERE id_dosen = $4",
            array($new_file, $nama, $id_user, $id_dosen));
    } else {
        // Update tanpa mengubah foto
        pg_query_params($conn, "UPDATE dosen SET nama_dosen = $1, id_user = $2 WHERE id_dosen = $3",
            array($nama, $id_user, $id_dosen));
    }

    // Update jabatan pada anggota_lab
    pg_query_params($conn, "UPDATE anggota_lab SET jabatan = $1 WHERE id_anggota = $2",
        array($jabatan, $id_anggota));

    echo "<script>alert('Data anggota berhasil diperbarui!'); window.location.href='../profil/edit_struktur.php';</script>";
    exit();
}

// -----------------------------
// 2) TAMBAH anggota baru
// -----------------------------
if (isset($_POST['tambah'])) {
    $nama    = trim($_POST['nama_dosen'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');

    if (empty($nama)) {
        echo "<script>alert('Nama wajib diisi'); window.location.href='../profil/edit_struktur.php';</script>";
        exit();
    }

    // upload foto (opsional)
    $new_file = $default_foto;
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $file_size = $_FILES['foto']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','svg','webp'];
        
        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format foto tidak valid! Hanya JPG, PNG, GIF, SVG, WEBP yang diizinkan.'); window.location.href='../profil/edit_struktur.php';</script>"; 
            exit();
        }
        
        if ($file_size > 5 * 1024 * 1024) {
            echo "<script>alert('Ukuran file terlalu besar! Maksimal 5MB.'); window.location.href='../profil/edit_struktur.php';</script>"; 
            exit();
        }
        
        $new_file = "dosen_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        if (!move_uploaded_file($tmp_file, $upload_dir . $new_file)) {
            echo "<script>alert('Gagal mengupload foto.'); window.location.href='../profil/edit_struktur.php';</script>"; 
            exit();
        }
    }

    // insert dosen
    $ins = pg_query_params($conn, "INSERT INTO dosen (nama_dosen, media_path, id_user) VALUES ($1, $2, $3) RETURNING id_dosen",
        array($nama, $new_file, $id_user));
    if (!$ins) {
        echo "<script>alert('Gagal menambahkan dosen.'); window.location.href='../profil/edit_struktur.php';</script>"; exit();
    }
    $row = pg_fetch_assoc($ins);
    $id_dosen_baru = $row['id_dosen'];

    // insert anggota_lab
    $q2 = pg_query_params($conn, "INSERT INTO anggota_lab (id_dosen, jabatan) VALUES ($1, $2)",
        array($id_dosen_baru, $jabatan));
    if (!$q2) {
        echo "<script>alert('Gagal menambahkan anggota_lab.'); window.location.href='../profil/edit_struktur.php';</script>"; exit();
    }

    echo "<script>alert('Anggota baru berhasil ditambahkan!'); window.location.href='../profil/edit_struktur.php';</script>";
    exit();
}

// -----------------------------
// 3) HAPUS anggota
// -----------------------------
if (isset($_POST['hapus'])) {
    $id_anggota = $_POST['id_anggota'] ?? null;
    if (!$id_anggota) {
        echo "<script>alert('Parameter tidak lengkap.'); window.location.href='../profil/edit_struktur.php';</script>"; exit();
    }

    // ambil id_dosen terkait
    $res = pg_query_params($conn, "SELECT id_dosen FROM anggota_lab WHERE id_anggota = $1 LIMIT 1", array($id_anggota));
    if (!$res || pg_num_rows($res) === 0) {
        echo "<script>alert('Anggota tidak ditemukan.'); window.location.href='../profil/edit_struktur.php';</script>"; exit();
    }
    $r = pg_fetch_assoc($res);
    $id_dosen = $r['id_dosen'];

    // hapus entry anggota_lab
    pg_query_params($conn, "DELETE FROM anggota_lab WHERE id_anggota = $1", array($id_anggota));

    // hapus dosen jika tidak ada referensi lain
    $checkRef = pg_query_params($conn, "SELECT COUNT(*) as cnt FROM anggota_lab WHERE id_dosen = $1", array($id_dosen));
    $cnt = intval(pg_fetch_result($checkRef, 0, 'cnt'));
    if ($cnt === 0) {
        // ambil media_path untuk dihapus file fisik
        $d = pg_fetch_assoc(pg_query_params($conn, "SELECT media_path FROM dosen WHERE id_dosen = $1", array($id_dosen)));
        if (!empty($d['media_path']) && $d['media_path'] !== $default_foto) {
            $oldpath = $upload_dir . $d['media_path'];
            if (file_exists($oldpath)) @unlink($oldpath);
        }
        // hapus dosen
        pg_query_params($conn, "DELETE FROM dosen WHERE id_dosen = $1", array($id_dosen));
    }

    echo "<script>alert('Anggota berhasil dihapus!'); window.location.href='../profil/edit_struktur.php';</script>";
    exit();
}

// Jika tidak ada action dikenali
echo "<script>alert('Aksi tidak dikenali.'); window.location.href='../profil/edit_struktur.php';</script>";
exit();
?>