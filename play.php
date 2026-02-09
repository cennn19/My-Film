<?php
include 'koneksi.php';

// Ambil ID dari URL
if (!isset($_GET['tmdb_id'])) {
    die("Pilih film dulu bos.");
}

$tmdb_id = mysqli_real_escape_string($koneksi, $_GET['tmdb_id']);

// ============================================================
// LOGIKA SAKTI (AUTO EMBED)
// ============================================================
// Kita punya 2 Pilihan Sumber (Server). Kalau satu macet, ganti ke yang lain.

// Opsi 1: VidSrc (Biasanya paling lengkap)
$stream_url = "https://vidsrc.xyz/embed/movie?tmdb=" . $tmdb_id;

// Opsi 2: SuperEmbed (Cadangan kalau VidSrc mati)
// $stream_url = "https://multiembed.mov/?video_id=" . $tmdb_id . "&tmdb=1";

// Cek apakah film ini ada di database kita? (Sekedar buat nampilin judul yg benar)
// Kalau nggak ada di DB, kita ambil judul dari API nanti (tapi player tetep jalan)
$cek_db = mysqli_query($koneksi, "SELECT * FROM film_favorit WHERE tmdb_id = '$tmdb_id'");
$data_lokal = mysqli_fetch_assoc($cek_db);
$judul = $data_lokal ? $data_lokal['judul'] : "Nonton Film";

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nonton: <?php echo $judul; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0f172a; color: white; }
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* Rasio 16:9 */
            height: 0;
            overflow: hidden;
            background: #000;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <nav class="bg-slate-800 p-4 border-b border-slate-700">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-yellow-500 font-bold hover:text-yellow-400">
                &larr; Kembali ke Home
            </a>
            <span class="text-xs text-slate-400">Server: Auto-Embed v1</span>
        </div>
    </nav>

    <div class="container mx-auto p-4 md:p-8 flex-1">
        
        <h1 class="text-2xl md:text-3xl font-bold mb-4 text-yellow-500">
            <?php echo htmlspecialchars($judul); ?>
        </h1>

        <div class="video-container border border-slate-700">
            <iframe src="<?php echo $stream_url; ?>" allowfullscreen allow="autoplay; encrypted-media"></iframe>
        </div>

        <div class="mt-6 bg-slate-800 p-4 rounded text-sm text-slate-300">
            <p><strong>Info:</strong> Film ini diputar menggunakan server otomatis. Jika film tidak muncul atau error:</p>
            <ul class="list-disc ml-5 mt-2 text-slate-400">
                <li>Tunggu beberapa detik, server sedang mencari file film.</li>
                <li>Gunakan Browser Chrome/Edge terbaru.</li>
                <li>Matikan AdBlock jika player tidak muncul.</li>
                <li>Jika tetap error, berarti film ini belum tersedia di server pusat (biasanya film yang terlalu baru).</li>
            </ul>
        </div>

    </div>

</body>
</html>