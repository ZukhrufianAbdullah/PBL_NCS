<?php
// Konfigurasi koneksi ke PostgreSQL
$host = "localhost";       // Nama host server
$port = "5432";            // Port default PostgreSQL
$dbname = "lab_profile";   // Nama database
$user = "postgres";        // Username PostgreSQL
$password = "12345";       // Password PostgreSQL

try {
    // Membuat koneksi menggunakan PDO
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);

    // Set mode error agar mudah dideteksi (exception)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Jika koneksi berhasil
    // echo "Koneksi ke database berhasil!";
} catch (PDOException $e) {
    // Jika koneksi gagal
    echo "Koneksi gagal: " . $e->getMessage();
    exit;
}
?>
