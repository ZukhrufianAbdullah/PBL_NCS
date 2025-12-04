<?php
// Konfigurasi koneksi database PostgreSQL
$host       = "localhost";             
$port       = "5433";                  
$dbname     = "pbl";  
$user       = "postgres";              
$password   = "16092005";                

// Gunakan koneksi pgsql prosedural agar kompatibel dengan module legacy
$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Koneksi ke database PostgreSQL gagal: " . pg_last_error());
}

pg_set_client_encoding($conn, "UTF8");
?>
