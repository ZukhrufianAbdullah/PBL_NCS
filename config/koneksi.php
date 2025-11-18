<?php
// Konfigurasi koneksi database PostgreSQL
$host       = "localhost";             
$port       = "5432";                  
$dbname     = "lab_network_security";  
$user       = "postgres";              
$password   = "123456";                

try {
    // Membuat koneksi ke database PostgreSQL menggunakan PDO
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);

    // Mengatur mode error agar melempar exception saat ada error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke database PostgreSQL gagal: " . $e->getMessage());
}
?>
