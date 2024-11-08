<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tentangkami</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/stylettgkami.css">
    <link rel="stylesheet" href="styles/footer.css">
</head>
<body>

<nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="assets/elogo2.png" alt="GASP">
                GASP
            </div>
            <div class="nav-links">
                <div class="mobile-menu-header">
                    <div class="logo">
                        <img src="assets/elogo.png" alt="GASP">
                        GASP
                    </div>
                    <button class="close-menu-btn">×</button>
                </div>
                <a href="index2.php">Beranda</a>
                <a href="toko.php">Toko</a>
                <a href="#perpus">Perpustakaan</a>
                <a href="lihatsaran.php">Kotak Saran</a>
                <a href="tentangkami.php">Tentang Kami</a>
                <a href="editprofil.php">Edit Profil</a>
                <a href="logout.php">Keluar</a>
            </div>
            <button class="menu-btn">☰</button>
        </div>
    </nav>

    <header>
        <video id="bgvideo" poster="assets/posterbg.jpg" autoplay muted loop>
             <source src="assets/videoloop.mp4" type="video/mp4" />
        </video>
        <div class="videodesc">
            <img src="assets/gasp-removebg-previewcoppy1111.png" alt="">
            <h2>Tujuan kami Mengenalkan game</h2>
            <h1>GEN ALPHA</h1>
            <p>sebuah platform game untuk generasi baru</p>

        </div>
    </header>

    <div class="deskripsi" id="deskripsi">
        <img id="gambarlogo" src="assets/elogohalloween.png" alt="Slideshow Gambar">
        <h1 class="judul">TENTANG KAMI</h1>
        <p class="tulisan">
            <strong>
                Di Game Alpha Shop Platform, kami mengutamakan pengalaman gaming terbaik untuk para gamer, namun kami juga percaya bahwa kesehatan yang terjaga akan membuat setiap pemain lebih bersemangat dan tangguh.
            Di tengah petualangan seru, misi menantang, dan aksi tanpa batas, ingatlah untuk menjaga tubuh tetap bugar agar bisa terus menikmati serunya setiap permainan.
            Dengan tubuh yang sehat dan energi penuh, Anda siap menaklukkan setiap tantangan di dunia virtual maupun nyata. 
            Game Alpha Shop Platform mendukung para gamer yang tidak hanya tangguh di dalam game, tapi juga kuat dalam kehidupan nyata.</strong>
        </p>
    </div>

    <section class="developer-section">
        <h2 class="developer-title">Tim Developer Kami</h2>
        <div class="developer-grid">
            <div class="developer-card">
                <img src="assets/iammm.jpg" alt="Developer 1" class="developer-image">
                <h3 class="developer-name">Najwan Wi'am Asroshan</h3>
                <p class="developer-description">Kepala Keluarga/Ketua kelompok pencetus ide Project akhir Praktikum Pemrograman Web,<br> Menciptakan backend untuk website.</p>
            </div>
    
            <div class="developer-card">
                <img src="assets/malsss.jpg" alt="Developer 2" class="developer-image">
                <h3 class="developer-name">Muhammad Khemal Arsenio</h3>
                <p class="developer-description">Ahli dalam desain tampilan website kami.</p>
            </div>
    
            <div class="developer-card">
                <img src="assets/renoooo.jpg" alt="Developer 3" class="developer-image">
                <h3 class="developer-name">Vireno Imanuel Desclovic</h3>
                <p class="developer-description">Menciptakan pengalaman intraktif antara kami dan user di luar sana.</p>
            </div>
    
            <div class="developer-card">
                <img src="assets/opikk.jpg" alt="Developer 4" class="developer-image">
                <h3 class="developer-name">Taufiqurrahman Al Baihaqi</h3>
                <p class="developer-description">ahli dalam referensi dan assets berkembangnya website ini.</p>
            </div>
        </div>
    </section>

    <?php
    include("footer.php")
    ?>

    <script src="js/ttgkami.js"></script>
    <script src="js/logindanregis.js"></script>

</body>
</html>