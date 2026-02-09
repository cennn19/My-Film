<?php
session_start();
include 'koneksi.php';

// Cek apakah dia Uploader (atau Admin)
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'uploader' && $_SESSION['role'] != 'admin')) {
    header("Location: login.php");
    exit();
}

// Proses Simpan Link
if (isset($_POST['simpan_link'])) {
    $id_film = $_POST['id_film'];
    $link = mysqli_real_escape_string($koneksi, $_POST['link_trailer']);
    
    // Update database
    mysqli_query($koneksi, "UPDATE film_favorit SET link_trailer = '$link' WHERE id = '$id_film'");
    echo "<script>alert('Link berhasil disimpan!'); window.location='dashboard_uploader.php';</script>";
}

// Ambil semua film dari database
$query = mysqli_query($koneksi, "SELECT * FROM film_favorit ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Uploader</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white">

    <nav class="bg-slate-800 p-4 border-b border-slate-700 flex justify-between">
        <h1 class="text-xl font-bold text-yellow-500">Area Kerja Uploader</h1>
        <div class="flex gap-4">
            <span>Halo, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-red-400 font-bold">Logout</a>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6">Daftar Film yang Perlu Link</h2>

        <div class="grid gap-6">
            <?php while($row = mysqli_fetch_assoc($query)): ?>
                <div class="bg-slate-800 p-4 rounded-lg border border-slate-700 flex gap-4 items-start">
                    <img src="https://image.tmdb.org/t/p/w200<?php echo $row['gambar']; ?>" class="w-24 rounded shadow">
                    
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-yellow-500"><?php echo $row['judul']; ?></h3>
                        
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="id_film" value="<?php echo $row['id']; ?>">
                            
                            <label class="block text-sm text-gray-400 mb-1">Link YouTube:</label>
                            <div class="flex gap-2">
                                <input type="text" name="link_trailer" 
                                       value="<?php echo $row['link_trailer']; ?>" 
                                       placeholder="Contoh: https://www.youtube.com/watch?v=xxxxx"
                                       class="flex-1 bg-slate-900 border border-slate-600 rounded px-3 py-2 text-white focus:border-yellow-500 outline-none">
                                
                                <button type="submit" name="simpan_link" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded font-bold">
                                    Simpan
                                </button>
                            </div>
                        </form>

                        <div class="mt-2 text-xs">
                            Status: 
                            <?php if(empty($row['link_trailer'])): ?>
                                <span class="text-red-400 font-bold">❌ Belum ada link</span>
                            <?php else: ?>
                                <span class="text-green-400 font-bold">✅ Siap Tonton</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>