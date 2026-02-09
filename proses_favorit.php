<?php
include 'koneksi.php';

if (isset($_POST['simpan_favorit'])) {
    // Tangkap data dari form
    $tmdb_id = $_POST['tmdb_id'];
    $judul   = $_POST['judul'];
    $gambar  = $_POST['gambar'];
    $rating  = $_POST['rating'];
    
    // Default status (biar gak error di tabel lama)
    $status  = "Embed"; 

    // 1. CEK DULU: Jangan sampai ada film kembar
    $cek = mysqli_query($koneksi, "SELECT * FROM film WHERE tmdb_id = '$tmdb_id'");
    
    if (mysqli_num_rows($cek) > 0) {
        // Kalau sudah ada, kasih tau
        echo "<script>
            alert('Film $judul sudah ada di Daftar Favorit!');
            window.location = 'index.php';
        </script>";
    } else {
        // 2. KALAU BELUM ADA, SIMPAN
        // Pastikan nama tabelmu benar ('film' atau 'film_favorit')
        $query = "INSERT INTO film (tmdb_id, judul_film, cover, rating, status_link) 
                  VALUES ('$tmdb_id', '$judul', '$gambar', '$rating', '$status')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>
                alert('Berhasil menambahkan $judul ke Favorit! ❤️');
                window.location = 'favorit.php';
            </script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}
?>