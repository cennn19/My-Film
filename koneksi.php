<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "peler"; // Sesuaikan dengan nama yang baru kamu buat

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>