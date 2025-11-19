<?php
// File: admin/layanan/hapus_pesan.php
// Script ini akan dipanggil untuk memproses penghapusan dari tabel 'konsultatif'.

// Ambil ID pesan dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pesan_id = (int)$_GET['id'];
    
    // --- PSEUDO-CODE LOGIKA DATABASE ---
    /*
    // 1. Sertakan koneksi DB
    require_once('../../config_db.php'); 
    
    // 2. Query DELETE
    $sql = "DELETE FROM konsultatif WHERE id_konsultatif = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$pesan_id])) {
        // Beri tahu pengguna sukses dan redirect
        header("Location: lihat_pesan.php?status=success");
        exit;
    } else {
        // Beri tahu pengguna gagal
        header("Location: lihat_pesan.php?status=failed");
        exit;
    }
    */
    
    // Simulasi sukses dan redirect
    header("Location: lihat_pesan.php?status=success_simulasi");
    exit;
} else {
    // Jika ID tidak valid
    header("Location: lihat_pesan.php?status=invalid_id");
    exit;
}
?>