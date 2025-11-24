<?php
include '../../config/koneksi.php'; // koneksi ke PostgreSQL

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    echo "<script>
            alert('Email dan password wajib diisi.');
            window.location.href = '../../user/login_admin.php';
          </script>";
    exit();
}

$sql = "SELECT id_user, email, password FROM users WHERE email = $1 LIMIT 1";
$result = pg_query_params($conn, $sql, array($email));

if (!$result || pg_num_rows($result) === 0) {
    echo "<script>
            alert('Email atau password tidak valid.');
            window.location.href = '../../user/login_admin.php';
          </script>";
    exit();
}

$user = pg_fetch_assoc($result);

if (!password_verify($password, $user['password'])) {
    echo "<script>
            alert('Email atau password tidak valid.');
            window.location.href = '../../user/login_admin.php';
          </script>";
    exit();
}

$_SESSION['id_user'] = $user['id_user'];
$_SESSION['email'] = $user['email'];

echo "<script>
        alert('Login berhasil! Selamat datang di halaman admin.');
        window.location.href = '../index.php';
      </script>";
exit();
?>
