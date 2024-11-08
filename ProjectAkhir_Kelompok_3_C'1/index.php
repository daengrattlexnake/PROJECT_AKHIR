<?php
session_start();
require "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GASP Game Station</title>
    <link rel="icon" href="assets/elogo.png">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">

</head>

<body>
    <!-- Navigation -->
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
                <a href="index.php">Beranda</a>
                <a href="toko.php">Toko</a>
                <a href="perpustakaan.php">Perpustakaan</a>
                <a href="lihatsaran.php">Kotak Saran</a>
                <a href="tentangkami.php">Tentang Kami</a>
                <a href="editprofil.php">Edit Profil</a>
            </div>
            <button class="menu-btn">☰</button>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Mulai Mencari Game Anda Sekarang</h1>
                <p>Koleksi Ribuan Game Yang Tersedia</p>
                <div class="cta-buttons">
                    <a href="login.php">
                        <button class="btn btn-primary">Login</button>
                    </a>
                    <a href="registrasi.php">
                        <button class="btn btn-secondary">Daftar</button>
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/elogo.png" alt="">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2 class="section-title">Fitur Fitur GASP?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237FB3FA' stroke-width='2'%3E%3Cpath d='M6 11h4M8 9v4M14 11h4'/%3E%3Crect x='2' y='6' width='20' height='12' rx='2'/%3E%3C/svg%3E"
                    alt="Games" class="feature-icon">
                <h3 class="feature-title">Telurusi Toko Game</h3>
                <p class="feature-description">Bermacam Macam Game</p>
            </div>
            <div class="feature-card">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237FB3FA' stroke-width='2'%3E%3Ccircle cx='12' cy='8' r='5'/%3E%3Cpath d='M3 21v-2a7 7 0 0 1 14 0v2'/%3E%3C/svg%3E"
                    alt="Community" class="feature-icon">
                <h3 class="feature-title">Mendengarkan Saran Anda</h3>
                <p class="feature-description">Dengan Mengirim Di Kotak Saran</p>
            </div>
            <div class="feature-card">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237FB3FA' stroke-width='2'%3E%3Cpath d='M12 15l-8-8h16l-8 8z'/%3E%3C/svg%3E"
                    alt="Tournament" class="feature-icon">
                <h3 class="feature-title">Selalu Diperbarui</h3>
                <p class="feature-description">Dengan Game Terkini Dan Terbaru</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats" id="stats">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">1M+</div>
                <div class="stat-label">Pengguna</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">Games Yang Tersedia</div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php
    include("footer.php")
    ?>
    
    <script src="js/logindanregis.js"></script>
</body>

</html>