<?php
include '../../config/koneksi.php';
session_start();

$id_user = $_SESSION['id_user'] ?? 1; // fallback jika belum ada session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $edit_type = $_POST['edit_type'];

    // Jika mengedit VISI
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

    }

    // Jika mengedit MISI
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
    }

} else {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../profil/edit_visi_misi.php';
          </script>";
}
?>
