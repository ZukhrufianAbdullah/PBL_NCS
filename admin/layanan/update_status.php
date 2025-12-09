<?php
// File: admin/layanan/update_status.php
session_start();
header('Content-Type: application/json');

include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['pesan_id'] ?? 0);
    $status = $_POST['status'] ?? 'pending';
    
    if ($id > 0) {
        $sql = "UPDATE konsultatif SET status = $1 WHERE id_konsultatif = $2";
        $result = pg_query_params($conn, $sql, array($status, $id));
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Status updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
exit();
?>