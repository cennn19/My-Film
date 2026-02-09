<?php
session_start();
include 'koneksi.php';

// 1. Tangkap Input dari Form Login
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password']; // Password yang diketik (misal: suki123)

// 2. Cari Username di Database
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$user = mysqli_fetch_assoc($query);

// 3. Cek Apakah User Ditemukan?
if ($user) {
    // 4. BANDINGKAN PASSWORD (Tanpa Enkripsi/Hash)
    // Logika: Apakah "suki123" (input) SAMA DENGAN "suki123" (database)?
    if ($password == $user['password']) { 
        
        // SUKSES! Simpan identitas ke sesi
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Arahkan sesuai jabatan (Role)
        if ($user['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_uploader.php");
        }
        exit(); // Penting biar kode berhenti di sini

    } else {
        // Password beda
        echo "<script>alert('Password Salah! Input: $password | DB: " . $user['password'] . "'); window.location='login.php';</script>";
    }
} else {
    // Username tidak ada
    echo "<script>alert('Username tidak ditemukan!'); window.location='login.php';</script>";
}
?>