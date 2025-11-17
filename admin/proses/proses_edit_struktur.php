<?php
include '../../config/koneksi.php';
session_start();

// Folder upload
$upload_dir = "../../uploads/dosen/";

// Foto default jika tidak ada foto
$default_foto = "default.png";

// Ambil id_user (fallback 1 jika tidak ada session)
$id_user = $_SESSION['id_user'] ?? 1;

// =============================================================
// 1. UPDATE DATA ANGGOTA (nama, jabatan, foto opsional)
// =============================================================
if (isset($_POST['edit'])) {

    $id_anggota = $_POST['id_anggota'];
    $id_dosen   = $_POST['id_dosen'];
    $nama       = $_POST['nama_dosen'];
    $jabatan    = $_POST['jabatan'];

    // --- Jika ada file foto baru ---
    if (!empty($_FILES['foto']['name'])) {

        $file_name = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format foto tidak valid!'); window.location.href='../profil/edit_struktur.php';</script>";
            exit();
        }

        // Rename file
        $new_file = "dosen_" . time() . "." . $ext;

        // Upload file
        move_uploaded_file($tmp_file, $upload_dir . $new_file);

        // UPDATE foto dosen
        $q1 = "UPDATE dosen SET media_path = $1, nama_dosen = $2, id_user = $3 WHERE id_dosen = $4";
        pg_query_params($conn, $q1, array($new_file, $nama, $id_user, $id_dosen));

    } else {

        // UPDATE tanpa foto
        $q1 = "UPDATE dosen SET nama_dosen = $1, id_user = $2 WHERE id_dosen = $3";
        pg_query_params($conn, $q1, array($nama, $id_user, $id_dosen));
    }

    // UPDATE jabatan anggota
    $q2 = "UPDATE anggota_lab SET jabatan = $1 WHERE id_anggota = $2";
    pg_query_params($conn, $q2, array($jabatan, $id_anggota));

    echo "<script>
            alert('Data anggota berhasil diperbarui!');
            window.location.href='../profil/edit_struktur.php';
          </script>";
    exit();
}


// =============================================================
// 2. TAMBAH ANGGOTA BARU
// =============================================================
if (isset($_POST['tambah'])) {

    $nama    = $_POST['nama_dosen'];
    $jabatan = $_POST['jabatan'];

    // ========== UPLOAD FOTO (opsional) ==========
    if (!empty($_FILES['foto']['name'])) {

        $file_name = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Format foto tidak valid!'); window.location.href='../profil/edit_struktur.php';</script>";
            exit();
        }

        $new_file = "dosen_" . time() . "." . $ext;
        move_uploaded_file($tmp_file, $upload_dir . $new_file);

    } else {
        // Jika tidak upload → default
        $new_file = $default_foto;
    }

    // INSERT ke tabel dosen
    $q1 = "INSERT INTO dosen (nama_dosen, media_path, id_user) VALUES ($1, $2, $3) RETURNING id_dosen";
    $result = pg_query_params($conn, $q1, array($nama, $new_file, $id_user));

    $row = pg_fetch_assoc($result);
    $id_dosen_baru = $row['id_dosen'];

    // INSERT ke anggota_lab
    $q2 = "INSERT INTO anggota_lab (id_profil, id_dosen, jabatan) VALUES (1, $1, $2)";
    pg_query_params($conn, $q2, array($id_dosen_baru, $jabatan));

    echo "<script>
            alert('Anggota baru berhasil ditambahkan!');
            window.location.href='../profil/edit_struktur.php';
          </script>";
    exit();
}


// =============================================================
// 3. HAPUS ANGGOTA
// =============================================================
if (isset($_POST['hapus'])) {

    $id_anggota = $_POST['id_anggota'];

    // HAPUS dari anggota_lab
    $q1 = "DELETE FROM anggota_lab WHERE id_anggota = $1";
    pg_query_params($conn, $q1, array($id_anggota));

    echo "<script>
            alert('Anggota berhasil dihapus!');
            window.location.href='../profil/edit_struktur.php';
          </script>";
    exit();
}

?>
