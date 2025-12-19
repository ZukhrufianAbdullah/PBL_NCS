<?php
// File: admin/proses/proses_struktur.php

// Memulai session untuk autentikasi user
session_start();

// Memanggil file koneksi database
include '../../config/koneksi.php';

// Memanggil helper page untuk manajemen halaman & konten
include __DIR__ . "/../../app/helpers/page_helper.php";

// Menentukan folder upload foto dosen
$upload_dir = "../../uploads/dosen/";

// Membuat folder upload jika belum ada
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Path foto default dosen
$default_foto = "../../assets/site/img/struktur/default.jpg";

// Mengambil id_user dari session login
$id_user = $_SESSION['id_user'] ?? 1;

// --------------------------------------------------
// 0) UPDATE PAGE CONTENT (Judul & Deskripsi Halaman)
// --------------------------------------------------
if (isset($_POST['edit_page_content']) && isset($_POST['submit'])) {

    // Ambil dan rapikan input judul & deskripsi halaman
    $judul_page     = trim($_POST['judul_page'] ?? '');
    $deskripsi_page = trim($_POST['deskripsi_page'] ?? '');

    // Menggunakan helper untuk memastikan halaman ada
    $id_page = ensure_page_exists($conn, 'profil_struktur');

    // Jika gagal mendapatkan atau membuat halaman
    if (!$id_page) {
        echo "<script>
                alert('Gagal membuat atau mendapatkan halaman Struktur Organisasi!');
                window.location.href='../profil/edit_struktur.php';
              </script>";
        exit();
    }

    // Simpan / update judul halaman
    $resultJudul = upsert_page_content(
        $conn,
        $id_page,
        'section_title',
        $judul_page,
        $id_user
    );

    // Simpan / update deskripsi halaman
    $resultDeskripsi = upsert_page_content(
        $conn,
        $id_page,
        'section_description',
        $deskripsi_page,
        $id_user
    );

    // Notifikasi hasil proses
    if ($resultJudul && $resultDeskripsi) {
        echo "<script>
                alert('Konten halaman Struktur Organisasi berhasil diperbarui!');
                window.location.href='../profil/edit_struktur.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman!');
                window.location.href='../profil/edit_struktur.php';
              </script>";
    }
    exit();
}

// --------------------------------------------------
// 1) EDIT ANGGOTA (Nama, Jabatan, Foto Opsional)
// --------------------------------------------------
if (isset($_POST['edit'])) {

    // Ambil data anggota dari form
    $id_anggota = $_POST['id_anggota'] ?? null;
    $id_dosen   = $_POST['id_dosen'] ?? null;
    $nama       = trim($_POST['nama_dosen'] ?? '');
    $jabatan    = trim($_POST['jabatan'] ?? '');

    // Validasi parameter wajib
    if (!$id_anggota || !$id_dosen) {
        echo "<script>
                alert('Parameter tidak lengkap.');
                window.location.href='../profil/edit_struktur.php';
              </script>";
        exit();
    }

    // Variabel untuk menyimpan nama file foto baru
    $new_file = null;

    // Jika user mengupload foto baru
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        // Ambil informasi file upload
        $file_name = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $file_size = $_FILES['foto']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Daftar ekstensi file yang diizinkan
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

        // Validasi ekstensi file
        if (!in_array($ext, $allowed)) {
            echo "<script>
                    alert('Format foto tidak valid! Hanya JPG, PNG, GIF, SVG, WEBP yang diizinkan.');
                    window.location.href='../profil/edit_struktur.php';
                  </script>";
            exit();
        }

        // Validasi ukuran file (maksimal 5MB)
        if ($file_size > 5 * 1024 * 1024) {
            echo "<script>
                    alert('Ukuran file terlalu besar! Maksimal 5MB.');
                    window.location.href='../profil/edit_struktur.php';
                  </script>";
            exit();
        }

        // Ambil foto lama dari database
        $old = pg_fetch_assoc(
            pg_query_params(
                $conn,
                "SELECT media_path FROM dosen WHERE id_dosen = $1",
                array($id_dosen)
            )
        );

        $old_file = $old['media_path'] ?? null;

        // Hapus foto lama jika bukan foto default
        if (!empty($old_file) && $old_file !== $default_foto) {
            $old_path = $upload_dir . $old_file;
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        }

        // Generate nama file baru & upload
        $new_file = "dosen_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        move_uploaded_file($tmp_file, $upload_dir . $new_file);
    }

    // Panggil stored procedure untuk update anggota
    pg_query_params(
        $conn,
        "CALL sp_edit_anggota($1, $2, $3, $4, $5, $6)",
        array(
            $id_anggota,
            $id_dosen,
            $nama,
            $jabatan,
            $new_file,
            $id_user
        )
    );

    // Notifikasi sukses
    echo "<script>
            alert('Data anggota berhasil diperbarui!');
            window.location.href='../profil/edit_struktur.php';
          </script>";
    exit();
}

/* ============================================================
   2) TAMBAH ANGGOTA BARU
   ============================================================ */
if (isset($_POST['tambah'])) {

    // Ambil data anggota baru
    $nama     = trim($_POST['nama_dosen']);
    $jabatan  = trim($_POST['jabatan']);
    $new_file = null;

    // Proses upload foto anggota baru
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        // Ambil informasi file
        $file_name = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validasi ekstensi file
        $allowed = ['jpg','jpeg','png','gif','svg','webp'];
        if (!in_array($ext, $allowed)) {
            echo "<script>
                    alert('Format foto tidak valid!');
                    window.location.href='../profil/edit_struktur.php';
                  </script>";
            exit();
        }

        // Generate nama file & upload
        $new_file = "dosen_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        move_uploaded_file($tmp_file, $upload_dir . $new_file);
    }

    // Panggil stored procedure untuk menambah anggota
    pg_query_params(
        $conn,
        "CALL sp_tambah_anggota($1,$2,$3,$4)",
        [$nama, $new_file, $jabatan, $id_user]
    );

    // Notifikasi sukses
    echo "<script>
            alert('Anggota baru berhasil ditambahkan!');
            window.location.href='../profil/edit_struktur.php';
          </script>";
    exit();
}

// --------------------------------------------------
// 3) HAPUS ANGGOTA
// --------------------------------------------------
if (isset($_POST['hapus'])) {

    // Ambil ID anggota yang akan dihapus
    $id_anggota = $_POST['id_anggota'] ?? null;

    // Validasi parameter
    if (!$id_anggota) {
        echo "<script>
                alert('Parameter tidak lengkap.');
                window.location.href='../profil/edit_struktur.php';
              </script>";
        exit();
    }

    // Ambil path foto dosen terkait
    $q = pg_fetch_assoc(
        pg_query_params(
            $conn,
            "SELECT d.media_path 
             FROM dosen d
             JOIN anggota_lab a ON a.id_dosen = d.id_dosen
             WHERE a.id_anggota = $1",
            [$id_anggota]
        )
    );

    $foto = $q['media_path'] ?? null;

    // Hapus file foto fisik jika bukan foto default
    if (!empty($foto) && $foto !== $default_foto) {
        $path = $upload_dir . $foto;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    // Panggil stored procedure untuk menghapus anggota
    pg_query_params(
        $conn,
        "CALL sp_hapus_anggota($1)",
        [$id_anggota]
    );

    // Notifikasi sukses
    echo "<script>
            alert('Anggota berhasil dihapus!');
            window.location.href='../profil/edit_struktur.php';
          </script>";
    exit();
}
?>
