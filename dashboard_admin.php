<?php
session_start();
include 'koneksi.php';

// 1. CEK KEAMANAN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda bukan Admin!'); window.location='login.php';</script>";
    exit();
}

// ==========================================
// LOGIKA SENSOR FILM (BLACKLIST)
// ==========================================
if (isset($_POST['sembunyikan'])) {
    $id_tmdb = $_POST['id_tmdb'];
    $judul_film = $_POST['judul_film'];
    
    $cek = mysqli_query($koneksi, "SELECT * FROM film_hidden WHERE tmdb_id = '$id_tmdb'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Film ini sudah disembunyikan!');</script>";
    } else {
        mysqli_query($koneksi, "INSERT INTO film_hidden (tmdb_id, judul) VALUES ('$id_tmdb', '$judul_film')");
        echo "<script>alert('Film berhasil disembunyikan!'); window.location='dashboard_admin.php';</script>";
    }
}

if (isset($_GET['munculkan'])) {
    $id_hidden = $_GET['munculkan'];
    mysqli_query($koneksi, "DELETE FROM film_hidden WHERE id = '$id_hidden'");
    echo "<script>alert('Film dimunculkan kembali!'); window.location='dashboard_admin.php';</script>";
}

// ==========================================
// LOGIKA MANAJEMEN IKLAN
// ==========================================
if (isset($_POST['tambah_iklan'])) {
    $judul = $_POST['judul_iklan'];
    $gambar = $_POST['url_gambar'];
    $link = $_POST['url_tujuan'];
    $posisi = $_POST['posisi_iklan'];

    $query_iklan = "INSERT INTO iklan (judul, gambar_url, link_url, posisi, status) VALUES ('$judul', '$gambar', '$link', '$posisi', 'aktif')";
    
    if(mysqli_query($koneksi, $query_iklan)){
        echo "<script>alert('Iklan berhasil ditambahkan!'); window.location='dashboard_admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah iklan.');</script>";
    }
}

if (isset($_GET['hapus_iklan'])) {
    $id_iklan = $_GET['hapus_iklan'];
    mysqli_query($koneksi, "DELETE FROM iklan WHERE id = '$id_iklan'");
    echo "<script>alert('Iklan dihapus!'); window.location='dashboard_admin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Sensor & Iklan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-200 font-sans">

    <nav class="bg-red-900 p-4 shadow-lg border-b border-red-700 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-cogs text-2xl text-white"></i>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-wider">ADMIN PANEL</h1>
                    <p class="text-xs text-red-200">Sensor Film & Ads Manager</p>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="index.php" target="_blank" class="text-white hover:text-yellow-400 text-sm font-bold">
                    <i class="fas fa-eye"></i> Lihat Web
                </a>
                <a href="logout.php" class="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded text-sm font-bold transition border border-slate-600">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="space-y-8">
            <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 shadow-lg">
                <h3 class="text-xl font-bold text-red-400 mb-4 flex items-center gap-2 border-b border-slate-700 pb-2">
                    🚫 Sensor Film (Blacklist)
                </h3>
                <form method="POST" class="flex flex-col gap-4 mb-4">
                    <input type="text" name="id_tmdb" placeholder="ID TMDB (Cth: 939243)" required 
                           class="bg-slate-900 border border-slate-600 text-white px-4 py-2 rounded focus:border-red-500 outline-none">
                    <input type="text" name="judul_film" placeholder="Judul Film (Catatan Admin)" required 
                           class="bg-slate-900 border border-slate-600 text-white px-4 py-2 rounded focus:border-red-500 outline-none">
                    <button type="submit" name="sembunyikan" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded font-bold transition">
                        Sembunyikan Film
                    </button>
                </form>

                <div class="mt-6">
                    <h4 class="text-xs font-bold text-slate-500 mb-2 uppercase">Daftar Terblokir:</h4>
                    <div class="max-h-60 overflow-y-auto space-y-2 pr-2">
                        <?php 
                        $hidden_query = mysqli_query($koneksi, "SELECT * FROM film_hidden ORDER BY id DESC");
                        while($h = mysqli_fetch_assoc($hidden_query)): 
                        ?>
                            <div class="bg-slate-900 border-l-4 border-red-600 p-3 rounded flex justify-between items-center text-sm">
                                <div>
                                    <span class="text-white font-bold block"><?php echo $h['judul']; ?></span>
                                    <span class="text-xs text-slate-500">ID: <?php echo $h['tmdb_id']; ?></span>
                                </div>
                                <a href="dashboard_admin.php?munculkan=<?php echo $h['id']; ?>" 
                                   onclick="return confirm('Buka blokir film ini?')"
                                   class="text-green-500 hover:text-green-400 font-bold text-xs">
                                   <i class="fas fa-unlock"></i> Buka
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 shadow-lg">
                <h3 class="text-xl font-bold text-yellow-500 mb-4 flex items-center gap-2 border-b border-slate-700 pb-2">
                    📢 Kelola Iklan (Ads)
                </h3>
                
                <form method="POST" class="flex flex-col gap-3 mb-6">
                    <input type="text" name="judul_iklan" placeholder="Nama Iklan (Cth: Promo Judi Slot)" required 
                           class="bg-slate-900 border border-slate-600 text-white px-4 py-2 rounded focus:border-yellow-500 outline-none">
                    
                    <input type="text" name="url_gambar" placeholder="URL Gambar Banner (https://...)" required 
                           class="bg-slate-900 border border-slate-600 text-white px-4 py-2 rounded focus:border-yellow-500 outline-none">
                    
                    <input type="text" name="url_tujuan" placeholder="Link Tujuan Saat Diklik" required 
                           class="bg-slate-900 border border-slate-600 text-white px-4 py-2 rounded focus:border-yellow-500 outline-none">
                    
                    <select name="posisi_iklan" class="bg-slate-900 border border-slate-600 text-white px-4 py-2 rounded focus:border-yellow-500 outline-none">
                        <option value="atas">Posisi ATAS (Header)</option>
                        <option value="bawah">Posisi BAWAH (Footer)</option>
                    </select>

                    <button type="submit" name="tambah_iklan" class="bg-yellow-600 hover:bg-yellow-700 text-slate-900 px-6 py-2 rounded font-bold transition">
                        <i class="fas fa-plus"></i> Pasang Iklan
                    </button>
                </form>

                <div class="mt-6">
                    <h4 class="text-xs font-bold text-slate-500 mb-2 uppercase">Iklan Aktif:</h4>
                    <div class="space-y-3">
                        <?php 
                        $iklan_query = mysqli_query($koneksi, "SELECT * FROM iklan ORDER BY id DESC");
                        if(mysqli_num_rows($iklan_query) > 0):
                            while($ad = mysqli_fetch_assoc($iklan_query)): 
                        ?>
                            <div class="bg-slate-900 p-3 rounded border border-slate-700 relative group">
                                <img src="<?php echo $ad['gambar_url']; ?>" class="w-full h-16 object-cover rounded mb-2 opacity-70 group-hover:opacity-100 transition">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-white font-bold text-sm block"><?php echo $ad['judul']; ?></span>
                                        <span class="text-xs text-yellow-500 uppercase font-bold border border-yellow-500 px-1 rounded">
                                            <?php echo $ad['posisi']; ?>
                                        </span>
                                    </div>
                                    <a href="dashboard_admin.php?hapus_iklan=<?php echo $ad['id']; ?>" 
                                       onclick="return confirm('Hapus iklan ini?')"
                                       class="bg-red-600 hover:bg-red-500 text-white p-2 rounded text-xs transition">
                                       <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                            <p class="text-center text-slate-600 italic text-sm">Belum ada iklan.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>