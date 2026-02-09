<?php
session_start();
include 'koneksi.php';

// 1. CEK KEAMANAN: Cuma Admin yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda bukan Admin!'); window.location='login.php';</script>";
    exit();
}

// 2. LOGIKA SEMBUNYIKAN FILM (BLACKLIST)
if (isset($_POST['sembunyikan'])) {
    $id_tmdb = $_POST['id_tmdb'];
    $judul_film = $_POST['judul_film']; // Buat catatan aja

    // Cek dulu biar gak dobel
    $cek = mysqli_query($koneksi, "SELECT * FROM film_hidden WHERE tmdb_id = '$id_tmdb'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Film ini sudah disembunyikan sebelumnya!');</script>";
    } else {
        mysqli_query($koneksi, "INSERT INTO film_hidden (tmdb_id, judul) VALUES ('$id_tmdb', '$judul_film')");
        echo "<script>alert('Film berhasil disembunyikan dari Home!'); window.location='dashboard_admin.php';</script>";
    }
}

// 3. LOGIKA MUNCULKAN KEMBALI (HAPUS DARI BLACKLIST)
if (isset($_GET['munculkan'])) {
    $id_hidden = $_GET['munculkan'];
    mysqli_query($koneksi, "DELETE FROM film_hidden WHERE id = '$id_hidden'");
    echo "<script>alert('Film dimunculkan kembali!'); window.location='dashboard_admin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Sensor Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-200 font-sans">

    <nav class="bg-red-900 p-4 shadow-lg border-b border-red-700">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-user-shield text-2xl text-white"></i>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-wider">ADMIN SENSOR</h1>
                    <p class="text-xs text-red-200">Panel Kontrol Blacklist</p>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <span class="text-sm italic">Halo, <b><?php echo $_SESSION['username']; ?></b></span>
                <a href="logout.php" class="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded text-sm font-bold transition border border-slate-600">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6 max-w-4xl">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold border-l-4 border-red-600 pl-4">Kelola Sensor Film</h2>
            <a href="index.php" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm">
                <i class="fas fa-external-link-alt"></i> Lihat Website Utama
            </a>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 mb-8 shadow-lg">
            <h3 class="text-xl font-bold text-red-400 mb-4 flex items-center gap-2">
                🚫 Sembunyikan Film dari API (Blacklist)
            </h3>
            <p class="text-slate-400 text-sm mb-4">Masukkan ID Film dari TMDB yang ingin dihilangkan dari halaman utama.</p>
            
            <form method="POST" class="flex flex-col md:flex-row gap-4 mb-4">
                <input type="text" name="id_tmdb" placeholder="ID TMDB (Contoh: 939243)" required 
                       class="bg-slate-900 border border-slate-600 text-white px-4 py-2 rounded md:w-48 focus:border-red-500 outline-none">
                <input type="text" name="judul_film" placeholder="Judul Film (Untuk catatan admin)" required 
                       class="bg-slate-900 border border-slate-600 text-white px-4 py-2 rounded flex-1 focus:border-red-500 outline-none">
                <button type="submit" name="sembunyikan" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded font-bold transition">
                    Sembunyikan
                </button>
            </form>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 shadow-lg">
            <h4 class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-widest border-b border-slate-700 pb-2">
                Daftar Film Tersembunyi:
            </h4>
            
            <div class="flex flex-col gap-2">
                <?php 
                $hidden_query = mysqli_query($koneksi, "SELECT * FROM film_hidden ORDER BY id DESC");
                if (mysqli_num_rows($hidden_query) > 0) {
                    while($h = mysqli_fetch_assoc($hidden_query)): 
                ?>
                    <div class="bg-slate-900 border-l-4 border-red-600 p-3 rounded flex justify-between items-center hover:bg-slate-900/80 transition">
                        <div>
                            <span class="text-white font-bold block"><?php echo $h['judul']; ?></span>
                            <span class="text-xs text-slate-500">ID TMDB: <?php echo $h['tmdb_id']; ?></span>
                        </div>
                        <a href="dashboard_admin.php?munculkan=<?php echo $h['id']; ?>" 
                           onclick="return confirm('Yakin mau memunculkan kembali film ini?')"
                           class="bg-slate-700 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-bold transition">
                           Buka Blokir
                        </a>
                    </div>
                <?php 
                    endwhile; 
                } else {
                    echo "<div class='text-center py-8 text-slate-600 italic'>Belum ada film yang disembunyikan. Aman terkendali!</div>";
                }
                ?>
            </div>
        </div>

    </div>

</body>
</html>