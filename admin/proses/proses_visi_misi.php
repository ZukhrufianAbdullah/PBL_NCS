<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session (fallback ke 1)
$id_user = $_SESSION['id_user'] ?? 1;

// Pastikan request melalui tombol submit
if (isset($_POST['submit'])) {

    $edit_type = $_POST['edit_type'];

    // ============================================================
    // 1. UPDATE VISI
    // ============================================================
    if ($edit_type === "visi") {

        $visi = $_POST['visi'];

        $query = "UPDATE profil 
                  SET visi = $1, id_user = $2 
                  WHERE id_profil = 1";

        $result = pg_query_params($conn, $query, array($visi, $id_user));

        if ($result) {
            echo "<script>
                    alert('Visi berhasil diperbarui!');
                    window.location.href = '../profil/edit_visi_misi.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memperbarui visi!');
                    window.location.href = '../profil/edit_visi_misi.php';
                  </script>";
        }
        exit();
    }

    // ============================================================
    // 2. UPDATE MISI
    // ============================================================
    elseif ($edit_type === "misi") {

        $misi = $_POST['misi'];

        $query = "UPDATE profil 
                  SET misi = $1, id_user = $2 
                  WHERE id_profil = 1";

        $result = pg_query_params($conn, $query, array($misi, $id_user));

        if ($result) {
            echo "<script>
                    alert('Misi berhasil diperbarui!');
                    window.location.href = '../profil/edit_visi_misi.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memperbarui misi!');
                    window.location.href = '../profil/edit_visi_misi.php';
                  </script>";
        }
        exit();
    }

    // ============================================================
    // 3. UPDATE PAGE CONTENT (judul + deskripsi singkat)
    // ============================================================
    elseif ($edit_type === "page_content") {

        $judul      = $_POST['judul'];
        $deskripsi  = $_POST['deskripsi'];
        $page_key   = "profil_visi_misi";

        $query = "UPDATE page_content
                  SET judul = $1, deskripsi = $2, id_user = $3
                  WHERE page_key = $4";

        $params = array($judul, $deskripsi, $id_user, $page_key);

        $result = pg_query_params($conn, $query, $params);

        if ($result) {
            echo "<script>
                    alert('Konten halaman Visi & Misi berhasil diperbarui!');
                    window.location.href = '../profil/edit_visi_misi.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memperbarui konten halaman!');
                    window.location.href = '../profil/edit_visi_misi.php';
                  </script>";
        }
        exit();
    }

} else {
    // Jika akses langsung TANPA submit
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../profil/edit_visi_misi.php';
          </script>";
    exit();
}
?>
