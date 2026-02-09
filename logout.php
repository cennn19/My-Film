<?php
session_start();
session_destroy(); // Hancurkan semua sesi (Login hilang)

// Tampilkan pesan manis lalu lempar ke Home Utama
echo "<script>
    alert('Anda telah berhasil Logout. Sampai jumpa lagi!');
    window.location = 'index.php';
</script>";
?>