<?php
session_start();
include "../../config/koneksi.php";

// Ambil id_user
$id_user = $_SESSION['id_user'] ?? 1;

// Folder upload
$uploadDir = "../../uploads/sarana/";

// Ekstensi file yang diperbolehkan
$allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'svg'];


/* =============================================================
   0) UPDATE PAGE CONTENT SARANA & PRASARANA
   ============================================================= */
if (isset($_POST['edit_page_content'])) {

    $judul_page     = $_POST['judul_page'];
    $deskripsi_page = $_POST['deskripsi_page'];
    $page_key       = "sarana_page";  // sesuai database page_content

    $query = "UPDATE page_content
              SET judul = $1, deskripsi = $2, id_user = $3
              WHERE page_key = $4";

    $result = pg_query_params($conn, $query, array(
        $judul_page,
        $deskripsi_page,
        $id_user,
        $page_key
    ));

    if ($result) {
        echo "<script>
                alert('Konten halaman Sarana & Prasarana berhasil diperbarui!');
                window.location.href='../sarana_prasarana/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui konten halaman!');
                window.location.href='../sarana_prasarana/index.php';
              </script>";
    }
    exit();
}



/* =============================================================
   1) TAMBAH SARANA
   ============================================================= */
if (isset($_POST['tambah_sarana'])) {

    $nama_sarana = $_POST['nama_sarana'];
    $file = $_FILES['media']['name'];
    $tmp  = $_FILES['media']['tmp_name'];

    if (!empty($file)) {

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Format file tidak valid!'); history.back();</script>";
            exit();
        }

        // Rename file
        $newFile = "sarana_" . time() . "." . $ext;

        // Upload
        move_uploaded_file($tmp, $uploadDir . $newFile);

        // Insert ke database
        $query = "INSERT INTO sarana (nama_sarana, media_path, id_user)
                  VALUES ($1, $2, $3)";

        pg_query_params($conn, $query, array($nama_sarana, $newFile, $id_user));
    }

    echo "<script>
            alert('Sarana berhasil ditambahkan!');
            window.location.href='../sarana_prasarana/index.php';
          </script>";
    exit();
}



/* =============================================================
   2) EDIT SARANA
   ============================================================= */
if (isset($_POST['edit_sarana'])) {

    $id_sarana   = $_POST['id_sarana'];
    $nama_sarana = $_POST['nama_sarana'];

    // Ambil foto lama
    $oldQuery = pg_query($conn, "SELECT media_path FROM sarana WHERE id_sarana = $id_sarana");
    $oldData  = pg_fetch_assoc($oldQuery);
    $oldFoto  = $oldData['media_path'];

    $file = $_FILES['media']['name'];
    $tmp  = $_FILES['media']['tmp_name'];

    // Jika ada upload file baru
    if (!empty($file)) {

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            echo "<script>alert('Format file tidak valid!'); history.back();</script>";
            exit();
        }

        // Nama file unik
        $newFoto = "sarana_" . time() . "." . $ext;

        move_uploaded_file($tmp, $uploadDir . $newFoto);

        // Hapus foto lama
        if (!empty($oldFoto) && file_exists($uploadDir . $oldFoto)) {
            unlink($uploadDir . $oldFoto);
        }

    } else {
        $newFoto = $oldFoto; // tetap foto lama
    }

    // Update data sarana
    $query = "UPDATE sarana 
              SET nama_sarana = $1, media_path = $2, id_user = $3
              WHERE id_sarana = $4";

    pg_query_params($conn, $query, array($nama_sarana, $newFoto, $id_user, $id_sarana));

    echo "<script>
            alert('Sarana berhasil diperbarui!');
            window.location.href='../sarana_prasarana/index.php';
          </script>";
    exit();
}



/* =============================================================
   3) HAPUS SARANA
   ============================================================= */
if (isset($_GET['hapus'])) {

    $id_sarana = $_GET['hapus'];

    // Ambil foto lama
    $oldQuery = pg_query($conn, "SELECT media_path FROM sarana WHERE id_sarana = $id_sarana");
    $oldData  = pg_fetch_assoc($oldQuery);
    $oldFoto  = $oldData['media_path'];

    // Hapus foto fisik
    if (!empty($oldFoto) && file_exists($uploadDir . $oldFoto)) {
        unlink($uploadDir . $oldFoto);
    }

    // Hapus dari database
    pg_query($conn, "DELETE FROM sarana WHERE id_sarana = $id_sarana");

    echo "<script>
            alert('Sarana berhasil dihapus!');
            window.location.href='../sarana_prasarana/index.php';
          </script>";
    exit();
}



/* =============================================================
   4) JIKA AKSI TIDAK VALID
   ============================================================= */
echo "<script>
        alert('Aksi tidak valid!');
        window.location.href='../sarana_prasarana/index.php';
      </script>";
exit();

?>
