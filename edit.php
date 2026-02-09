<?php
include 'koneksi.php';

$id = $_GET['id'];
// Ambil data lama
$query = mysqli_query($koneksi, "SELECT * FROM film_favorit WHERE id = '$id'");
$row = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $rating_baru = mysqli_real_escape_string($koneksi, $_POST['rating']);
    $komentar_baru = mysqli_real_escape_string($koneksi, $_POST['komentar']);
    $link_baru = mysqli_real_escape_string($koneksi, $_POST['link_trailer']); 
    
    $update = "UPDATE film_favorit SET rating = '$rating_baru', komentar = '$komentar_baru', link_trailer = '$link_baru' WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $update)) {
        header("Location: favorit.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-200 p-10">
    <div class="max-w-md mx-auto bg-slate-800 p-6 rounded-lg shadow-lg border border-slate-700">
        <h2 class="text-xl font-bold mb-4 text-yellow-500 text-center">Edit Data Film</h2>
        
        <form method="POST">
            <label class="block mb-2 text-sm text-gray-400">Rating (0-10):</label>
            <input type="number" step="0.1" name="rating" value="<?php echo $row['rating']; ?>" class="w-full bg-slate-900 border border-slate-600 p-2 rounded mb-4 text-white">

            <label class="block mb-2 text-sm text-gray-400">Link YouTube (Trailer/Full):</label>
            <input type="text" name="link_trailer" value="<?php echo $row['link_trailer']; ?>" placeholder="Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ" class="w-full bg-slate-900 border border-slate-600 p-2 rounded mb-4 text-white">
            <p class="text-xs text-gray-500 mb-4">*Copy paste link lengkap dari YouTube ke sini.</p>

            <label class="block mb-2 text-sm text-gray-400">Catatan Pribadi:</label>
            <textarea name="komentar" rows="3" class="w-full bg-slate-900 border border-slate-600 p-2 rounded mb-4 text-white"><?php echo $row['komentar']; ?></textarea>

            <div class="flex gap-2">
                <button type="submit" name="update" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-2 rounded">Simpan</button>
                <a href="favorit.php" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded text-center">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>