<?php
include '../../config/koneksi.php'; // koneksi ke PostgreSQL

// Ambil data dari form login
$username = $_POST['username'];
$password = md5($_POST['password']); 

// Query cek data user
$query  = "SELECT * FROM users WHERE nama_user = '$username' AND password = '$password'";
$result = pg_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$cek = pg_num_rows($result);

// Jika login berhasil
if ($cek > 0) {
    // Redirect langsung ke halaman dashboard admin
    echo "<script>
            alert('Login berhasil! Selamat datang di halaman admin.');
            window.location.href = '../index.php';
          </script>";
    exit();
} else {
    // Jika login gagal, kembali ke halaman login user
    echo "<script>
            alert('Username atau password salah! Silakan coba lagi.');
            window.location.href = '../../user/login_admin.php';
          </script>";
    exit();
}
?>
