<?php
session_start();
require "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GASP Game Store</title>

    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/toko.css">
    <link rel="stylesheet" href="styles/footer.css">
</head>

<body>
    <!-- Navigation -->
    <div class="navbar">
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
                    <a href="toko2.php">Toko</a>
                    <a href="perpustakaan.php">Perpustakaan</a>
                    <a href="lihatsaran.php">Kotak Saran</a>
                    <a href="tentangkami.php">Tentang Kami</a>
                    <a href="editprofil.php">Edit Profil</a>
                    <a href="logout.php">Keluar</a>
                </div>
                <button class="menu-btn">☰</button>
            </div>
        </nav>
    </div>

    <!-- Slideshow Section -->
    <div class="slideshow-container">
        <div class="mySlides fade">
            <div class="numbertext">1 / 3</div>
            <img src="assets/val-prev.jpg" alt="Slide 1">
            <div class="text">Game Terbaru</div>
        </div>

        <div class="mySlides fade">
            <div class="numbertext">2 / 3</div>
            <img src="assets/csgo-prev.png" alt="Slide 2">
            <div class="text">Promo Spesial</div>
        </div>

        <div class="mySlides fade">
            <div class="numbertext">3 / 3</div>
            <img src="assets/dbd-prev.jpg" alt="Slide 3">
            <div class="text">Game Populer</div>
        </div>

        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>
    </div>

    <div class="dots-container">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>

    <!-- Featured Games Section -->
    <section id="featured-cars" class="section">
        <?php
        // Query untuk mengambil semua game dan gambarnya
        $query = "SELECT gs.*, gi.image_url 
              FROM game_store gs 
              LEFT JOIN game_images gi ON gs.game_id = gi.game_id";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "Query Error: " . mysqli_error($conn);
            exit;
        }

        if (mysqli_num_rows($result) > 0) {
            while ($game = mysqli_fetch_assoc($result)) {
                // Tentukan URL gambar
                $image_url = $game['image_url'] ? 'uploads/games/' . htmlspecialchars($game['image_url']) : 'assets/default-game.jpg';
                ?>
                <div class="car-item">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($game['game_name']); ?>"
                        onerror="this.src='assets/default-game.jpg'">
                    <h3><?php echo htmlspecialchars($game['game_name']); ?></h3>
                    <p><?php echo htmlspecialchars($game['description']); ?></p>
                    <a href="tambah_library.php?game_id=<?php echo $game['game_id']; ?>" class="buy-now">Tambah</a>
                </div>
                <?php
            }
        } else {
            echo "<p>Tidak ada game yang tersedia.</p>";
        }
        ?>
    </section>

    <?php
    // Tutup koneksi database
    mysqli_close($conn);
    ?>
    </section>
    <!-- Footer -->
    <?php
    include("footer.php")
    ?>

    <script src="js/toko.js"></script>
</body>

</html>