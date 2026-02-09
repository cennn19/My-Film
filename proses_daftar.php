<?php
include 'koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Amankan password
$role = 'uploader'; // Default untuk pendaftar baru

// Cek apakah username sudah dipakai
$cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Username sudah ada, cari yang lain!'); window.location='register.php';</script>";
} else {
    $ins = mysqli_query($koneksi, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
    if ($ins) {
        echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";
    }
}
?>