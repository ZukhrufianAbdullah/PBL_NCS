<?php
session_start();
include "../../config/koneksi.php";

// Ambil id_user
$id_user = $_SESSION['id_user'] ?? 1;

// Folder upload
$uploadDir = "../../uploads/sarana/";

// Ekstensi file yang diperbolehkan
$allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

/* ============================================================
   FUNGSI UPDATE PAGE CONTENT (Judul & Deskripsi)
   ============================================================ */
function updatePageContent($conn, $judul, $deskripsi, $id_user) {
    $page_key = "sarana_page";

    $query = "UPDATE page_content
              SET judul = $1, deskripsi = $2, id_user = $3
              WHERE page_key = $4";

    return pg_query_params($conn, $query, array($judul, $deskripsi, $id_user, $page_key));
}

/* ============================================================
   1. UPDATE JUDUL & DESKRIPSI SARANA PRASARANA (PAGE CONTENT)
   ============================================================ */
if (isset($_POST['update_page'])) {

    $judul_page = $_POST['judul_page'];
    $deskripsi_page = $_POST['deskripsi_page'];

    updatePageContent($conn, $judul_page, $deskripsi_page, $id_user);

    echo "<script>
            alert('Judul dan deskripsi berhasil diperbarui!');
            window.location.href = '../sarana_prasarana/index.php';
          </script>";
    exit();
}

/* ============================================================
   2. TAMBAH SARANA
   ============================================================ */
if (isset($_POST['tambah_sarana'])) {

    $nama_sarana = $_POST['nama_sarana'];
    $file = $_FILES['media']['name'];
    $tmp = $_FILES['media']['tmp_name'];

    if (!empty($file)) {

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Format file tidak valid!');history.back();</script>";
            exit();
        }

        $newFile = time() . "_" . $file;

        move_uploaded_file($tmp, $uploadDir . $newFile);

        $query = "INSERT INTO sarana (nama_sarana, media_path, id_user)
                  VALUES ($1, $2, $3)";

        pg_query_params($conn, $query, array($nama_sarana, $newFile, $id_user));
    }

    echo "<script>
            alert('Sarana berhasil ditambahkan!');
            window.location.href = '../sarana_prasarana/index.php';
          </script>";
    exit();
}

/* ============================================================
   3. EDIT SARANA
   ============================================================ */
if (isset($_POST['edit_sarana'])) {

    $id_sarana   = $_POST['id_sarana'];
    $nama_sarana = $_POST['nama_sarana'];

    // Ambil gambar lama
    $oldQuery = pg_query($conn, "SELECT media_path FROM sarana WHERE id_sarana = $id_sarana");
    $oldData  = pg_fetch_assoc($oldQuery);
    $oldFoto  = $oldData['media_path'];

    $file = $_FILES['media']['name'];
    $tmp  = $_FILES['media']['tmp_name'];

    // Jika admin mengupload foto baru
    if (!empty($file)) {

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Format file tidak valid!');history.back();</script>";
            exit();
        }

        $newFoto = time() . "_" . $file;
        move_uploaded_file($tmp, $uploadDir . $newFoto);

        // Hapus foto lama
        if (!empty($oldFoto) && file_exists($uploadDir . $oldFoto)) {
            unlink($uploadDir . $oldFoto);
        }

    } else {
        $newFoto = $oldFoto; // pakai foto lama
    }

    $query = "UPDATE sarana 
              SET nama_sarana = $1, media_path = $2, id_user = $3
              WHERE id_sarana = $4";

    pg_query_params($conn, $query, array($nama_sarana, $newFoto, $id_user, $id_sarana));

    echo "<script>
            alert('Sarana berhasil diperbarui!');
            window.location.href = '../sarana_prasarana/index.php';
          </script>";
    exit();
}

/* ============================================================
   4. HAPUS SARANA
   ============================================================ */
if (isset($_GET['hapus'])) {

    $id_sarana = $_GET['hapus'];

    // Ambil foto
    $oldQuery = pg_query($conn, "SELECT media_path FROM sarana WHERE id_sarana = $id_sarana");
    $oldData  = pg_fetch_assoc($oldQuery);
    $oldFoto  = $oldData['media_path'];

    // Hapus foto fisik
    if (!empty($oldFoto) && file_exists($uploadDir . $oldFoto)) {
        unlink($uploadDir . $oldFoto);
    }

    // Hapus data
    pg_query($conn, "DELETE FROM sarana WHERE id_sarana = $id_sarana");

    echo "<script>
            alert('Sarana berhasil dihapus!');
            window.location.href = '../sarana_prasarana/index.php';
          </script>";
    exit();
}

?>
