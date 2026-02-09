<?php
$id = $_GET['id'];
// Gunakan ID ini untuk request detail film ke API TMDB lagi
// Atau ambil link video dari database Anda sendiri jika punya
?>
<h1>Sedang Menonton ID: <?php echo $id; ?></h1>

<video width="100%" controls>
    <source src="videos/film-sample.mp4" type="video/mp4">
    Browser Anda tidak support video.
</video>