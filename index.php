<?php
session_start();
include 'koneksi.php';

// ==========================================
// 1. KONFIGURASI API (WAJIB DIISI)
// ==========================================
// Masukkan API Key TMDB kamu di sini!
$apiKey = ""; 

// ==========================================
// 2. LOGIKA SATPAM (BLACKLIST FILM)
// ==========================================
// Kita ambil daftar ID film yang disembunyikan Admin dari database
$list_hidden = [];
$query_hidden = mysqli_query($koneksi, "SELECT tmdb_id FROM film_hidden");
while ($h = mysqli_fetch_assoc($query_hidden)) {
    $list_hidden[] = $h['tmdb_id']; // Masukkan ID ke daftar hitam
}

// ==========================================
// 3. LOGIKA PENCARIAN & FILM POPULER
// ==========================================
if (isset($_GET['cari'])) {
    // Kalau user lagi nyari film
    $keyword = urlencode($_GET['cari']);
    $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&language=id-ID&query=$keyword";
    $judul_halaman = "Hasil Pencarian: " . htmlspecialchars($_GET['cari']);
} else {
    // Kalau buka halaman biasa (Film Populer)
    $url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=id-ID";
    $judul_halaman = "Film Populer Saat Ini";
}

// Ambil Data dari API
$response = @file_get_contents($url);
$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyFilm - Nonton Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white font-sans">

    <nav class="bg-slate-800 p-4 shadow-lg border-b border-slate-700 sticky top-0 z-50">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            
            <a href="index.php" class="text-2xl font-bold text-yellow-500 tracking-wider">
                <i class="fas fa-film"></i> My<span class="text-white">Film</span>
            </a>

            <form action="index.php" method="GET" class="w-full md:w-1/2 flex">
                <input type="text" name="cari" placeholder="Cari film apa hari ini..." 
                       class="w-full p-2 rounded-l bg-slate-700 border border-slate-600 focus:border-yellow-500 outline-none text-white">
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 px-6 py-2 rounded-r font-bold text-slate-900 transition">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <div class="flex gap-2 items-center">
                
                <a href="favorit.php" class="bg-pink-600 px-4 py-2 rounded font-bold text-xs hover:bg-pink-500 mr-2 border border-pink-700">
                    <i class="fas fa-heart"></i> FAVORIT
                </a>

                <?php if(isset($_SESSION['role'])): ?>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <a href="dashboard_admin.php" class="bg-red-600 px-4 py-2 rounded font-bold text-xs hover:bg-red-500">ADMIN PANEL</a>
                    <?php else: ?>
                        <a href="dashboard_uploader.php" class="bg-blue-600 px-4 py-2 rounded font-bold text-xs hover:bg-blue-500">PETUGAS</a>
                    <?php endif; ?>
                    
                    <a href="logout.php" class="bg-slate-600 px-4 py-2 rounded font-bold text-xs hover:bg-slate-500">LOGOUT</a>
                <?php else: ?>
                    <a href="login.php" class="bg-green-600 px-4 py-2 rounded font-bold text-xs hover:bg-green-500">LOGIN</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        
        <h2 class="text-2xl font-bold mb-6 border-l-4 border-yellow-500 pl-4">
            <?php echo $judul_halaman; ?>
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            
            <?php if (isset($data['results']) && count($data['results']) > 0): ?>
                <?php foreach ($data['results'] as $movie): ?>

                    <?php 
                    // ==========================================
                    // 4. PENGECEKAN BLOKIR (SATPAM)
                    // ==========================================
                    // Kalau ID film ini ada di daftar blacklist, SKIP (Jangan ditampilkan)
                    if (in_array($movie['id'], $list_hidden)) {
                        continue; 
                    }

                    // Cek Gambar (Kalau kosong kasih gambar default)
                    $poster = !empty($movie['poster_path']) 
                              ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] 
                              : "https://via.placeholder.com/500x750?text=No+Image";
                    
                    // Rating Bintang
                    $rating = round($movie['vote_average'], 1);
                    ?>

                    <div class="bg-slate-800 rounded-lg overflow-hidden shadow-lg hover:scale-105 transition duration-300 relative group">
                        
                        <div class="relative">
                            <img src="<?php echo $poster; ?>" alt="<?php echo $movie['title']; ?>" class="w-full h-auto">
                            <div class="absolute top-2 right-2 bg-yellow-500 text-black font-bold text-xs px-2 py-1 rounded">
                                ⭐ <?php echo $rating; ?>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-bold text-sm mb-1 truncate" title="<?php echo $movie['title']; ?>">
                                <?php echo $movie['title']; ?>
                            </h3>
                            <p class="text-slate-400 text-xs mb-4">
                                Rilis: <?php echo substr($movie['release_date'], 0, 4); ?>
                            </p>
                            
                            <div class="flex gap-2">
                                <a href="play.php?tmdb_id=<?php echo $movie['id']; ?>" 
                                   class="flex-1 bg-red-600 hover:bg-red-500 text-white text-center py-2 rounded text-xs font-bold transition">
                                    ▶ PLAY
                                </a>

                                <form action="proses_favorit.php" method="POST" class="flex-1">
                                    <input type="hidden" name="tmdb_id" value="<?php echo $movie['id']; ?>">
                                    <input type="hidden" name="judul" value="<?php echo htmlspecialchars($movie['title']); ?>">
                                    <input type="hidden" name="gambar" value="<?php echo $movie['poster_path']; ?>">
                                    <input type="hidden" name="rating" value="<?php echo $movie['vote_average']; ?>">
                                    
                                    <button type="submit" name="simpan_favorit"
                                            class="w-full bg-slate-700 hover:bg-pink-600 text-white py-2 rounded text-xs font-bold transition flex items-center justify-center gap-1 group border border-slate-600">
                                        <i class="fas fa-heart text-pink-500 group-hover:text-white transition"></i> FAV
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-20 text-slate-500">
                    <h3 class="text-xl font-bold">Waduh, Film Tidak Ditemukan!</h3>
                    <p>Coba cari judul lain atau cek koneksi internetmu.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <footer class="bg-slate-900 text-center p-8 mt-10 border-t border-slate-800 text-slate-500 text-sm">
        &copy; <?php echo date('Y'); ?> Project Web Film Kampus. Dibuat dengan ☕ dan Koding.
    </footer>

</body>
</html>