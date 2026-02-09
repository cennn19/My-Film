<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Hapus data dari tabel 'film' berdasarkan ID yang diklik
    $query = "DELETE FROM film WHERE id = '$id'";
    $hapus = mysqli_query($koneksi, $query);

    if ($hapus) {
        echo "<script>
            alert('Film berhasil dihapus dari koleksi Favorit!'); 
            window.location='favorit.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data: " . mysqli_error($koneksi) . "'); 
            window.location='favorit.php';
        </script>";
    }
} else {
    // Kalau iseng buka file ini tanpa bawa ID
    header("Location: favorit.php");
}
?>