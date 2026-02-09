<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Favorit Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white font-sans">

    <nav class="bg-slate-800 p-4 shadow-lg mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold text-yellow-500">
                <i class="fas fa-arrow-left"></i> KEMBALI KE HOME
            </a>
            <h1 class="text-xl font-bold text-pink-500">❤️ KOLEKSI FAVORIT</h1>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            
            <?php
            // Ambil data dari Database Lokal
            $query = mysqli_query($koneksi, "SELECT * FROM film ORDER BY id DESC");
            
            if (mysqli_num_rows($query) > 0) {
                while ($movie = mysqli_fetch_assoc($query)):
                    // Setup Gambar
                    $poster = "https://image.tmdb.org/t/p/w500" . $movie['cover'];
            ?>
                <div class="bg-slate-800 rounded-lg overflow-hidden shadow-lg relative group border border-pink-900">
                    <img src="<?php echo $poster; ?>" alt="<?php echo $movie['judul_film']; ?>" class="w-full h-auto opacity-80 group-hover:opacity-100 transition">
                    
                    <div class="p-4">
                        <h3 class="font-bold text-sm mb-1 truncate text-pink-200"><?php echo $movie['judul_film']; ?></h3>
                        <div class="flex items-center gap-1 text-yellow-400 text-xs mb-3">
                            <i class="fas fa-star"></i> <?php echo $movie['rating']; ?>
                        </div>

                        <div class="flex gap-2">
                             <a href="play.php?tmdb_id=<?php echo $movie['tmdb_id']; ?>" 
                               class="flex-1 bg-red-600 text-center py-1 rounded text-xs font-bold">
                                PLAY
                            </a>
                            <a href="hapus_favorit.php?id=<?php echo $movie['id']; ?>" 
                               onclick="return confirm('Hapus dari favorit?')"
                               class="bg-slate-700 px-3 py-1 rounded text-xs text-red-400 hover:text-white hover:bg-red-600 transition">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>

            <?php 
                endwhile;
            } else {
                echo "<div class='col-span-full text-center text-slate-500 py-10'>Belum ada film yang difavoritkan.</div>";
            }
            ?>

        </div>
    </div>

</body>
</html>