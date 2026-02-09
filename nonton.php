<?php
include 'koneksi.php';

// Cek apakah ada ID yang dikirim melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID Film tidak ditemukan. Silakan kembali ke halaman utama.");
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data dari database lokal (film_favorit atau tabel film kamu nanti)
$query = mysqli_query($koneksi, "SELECT * FROM film_favorit WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ada di database
if (!$data) {
    die("Error: Film tidak ditemukan di database kami.");
}

// Fungsi Sakti: Ubah Link YouTube Biasa jadi Embed
function getYoutubeEmbedUrl($url) {
    if (empty($url)) return "";
    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
    $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $id_youtube = $matches[count($matches) - 1];
    } elseif (preg_match($shortUrlRegex, $url, $matches)) {
        $id_youtube = $matches[1];
    }
    return isset($id_youtube) ? "https://www.youtube.com/embed/$id_youtube" : ""; 
}

$embedUrl = getYoutubeEmbedUrl($data['link_trailer']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nonton: <?php echo htmlspecialchars($data['judul']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #000; color: #fff; }
        .iframe-container { position: relative; width: 100%; padding-bottom: 56.25%; height: 0; }
        .iframe-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>

    <div class="bg-gray-900 p-4 border-b border-gray-800 flex justify-between items-center">
        <h1 class="text-xl font-bold text-yellow-500">MyFilm</h1>
        <a href="favorit.php" class="bg-red-600 px-4 py-1 rounded text-sm font-bold hover:bg-red-500 transition">❌ Tutup</a>
    </div>

    <div class="container mx-auto p-4 md:p-8">
        <div class="w-full max-w-4xl mx-auto bg-black border border-gray-800 shadow-2xl rounded-lg overflow-hidden">
            <?php if (!empty($embedUrl)): ?>
                <div class="iframe-container">
                    <iframe src="<?php echo $embedUrl; ?>?autoplay=1&rel=0" allowfullscreen allow="autoplay"></iframe>
                </div>
            <?php else: ?>
                <div class="h-64 flex flex-col items-center justify-center text-gray-500 bg-[#111]">
                    <p class="text-3xl mb-2">⚠️</p>
                    <p>Link Video belum tersedia atau sedang diperbaiki.</p>
                    <a href="edit.php?id=<?php echo $data['id']; ?>" class="mt-4 text-yellow-500 underline">Edit Link (Hanya Uploader/Admin)</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="max-w-4xl mx-auto mt-6">
            <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($data['judul']); ?></h1>
            <div class="flex items-center gap-4 text-sm text-gray-400 mb-6">
                <span class="bg-yellow-600 text-black px-2 py-0.5 rounded font-bold">⭐ <?php echo $data['rating']; ?></span>
                <span class="text-gray-500">Ditambahkan: <?php echo date('d M Y', strtotime($data['tanggal_simpan'])); ?></span>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg border border-gray-800">
                <h3 class="text-yellow-500 font-bold mb-2 uppercase text-xs tracking-widest">Sinopsis / Catatan Uploader:</h3>
                <p class="text-gray-300 leading-relaxed italic">
                    <?php echo !empty($data['komentar']) ? htmlspecialchars($data['komentar']) : "Belum ada deskripsi untuk film ini."; ?>
                </p>
            </div>
        </div>
    </div>

</body>
</html>