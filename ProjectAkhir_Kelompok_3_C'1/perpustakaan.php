<?php
session_start();
require "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan - GASP Game Store</title>

    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/toko.css">
    <link rel="stylesheet" href="styles/footer.css">
    <style>
        .alert {
            padding: 15px;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 5px;
            text-align: center;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
        }

        .alert-info {
            background-color: #17a2b8;
            color: white;
        }

        .empty-library {
            text-align: center;
            padding: 50px;
            color: #ccc;
        }
    </style>
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
                <a href="index.php">Beranda</a>
                <a href="toko.php">Toko</a>
                <a href="perpustakaan.php">Perpustakaan</a>
                <a href="lihatsaran.php">Kotak Saran</a>
                <a href="tentangkami.php">Tentang Kami</a>
                <a href="editprofil.php">Edit Profil</a>
                <a href="logout.php">Keluar</a>
            </div>
            <button class="menu-btn">☰</button>
        </div>
    </nav>

    <!-- Alert Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Game berhasil ditambahkan ke perpustakaan!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['exists'])): ?>
        <div class="alert alert-info">
            Game sudah ada di perpustakaan Anda!
        </div>
    <?php endif; ?>

    <!-- Game Library Section -->
    <section id="featured-cars" class="section">
        <?php
        // Query untuk mengambil game di perpustakaan user
        $user_id = $_SESSION['user_id'];
        $query = "SELECT gs.*, gi.image_url 
                FROM game_library gl
                JOIN game_store gs ON gl.game_id = gs.game_id
                LEFT JOIN game_images gi ON gs.game_id = gi.game_id
                WHERE gl.user_id = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($game = mysqli_fetch_assoc($result)) {
                $image_url = $game['image_url'] ? 'uploads/games/' . htmlspecialchars($game['image_url']) : 'assets/default-game.jpg';
                ?>
                <div class="car-item">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($game['game_name']); ?>"
                        onerror="this.src='assets/default-game.jpg'">
                    <h3><?php echo htmlspecialchars($game['game_name']); ?></h3>
                    <p><?php echo htmlspecialchars($game['description']); ?></p>
                    <a href="<?php echo htmlspecialchars($game['download_link']); ?>" class="buy-now">Unduh</a>
                </div>
                <?php
            }
        } else {
            echo '<div class="empty-library">
                    <h2>Perpustakaan Anda Kosong</h2>
                    <p>Kunjungi <a href="toko.php" style="color: #2563EB;">toko</a> untuk menambahkan game!</p>
                  </div>';
        }
        ?>
    </section>

    <!-- Footer -->
    <?php
    include("footer.php")
    ?>
    
    <script src="js/toko.js"></script>
    <script src="js/logindanregis.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.querySelector('.menu-btn');
            const closeMenuBtn = document.querySelector('.close-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            let isMenuOpen = false;

            function openMenu() {
                navLinks.classList.add('active');
                document.body.style.overflow = 'hidden';
                isMenuOpen = true;
            }

            function closeMenu() {
                navLinks.classList.remove('active');
                document.body.style.overflow = '';
                isMenuOpen = false;
            }

            menuBtn.addEventListener('click', () => {
                if (!isMenuOpen) {
                    openMenu();
                }
            });

            closeMenuBtn.addEventListener('click', closeMenu);

            // Close mobile menu when clicking a link
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', closeMenu);
            });

            // Close menus when clicking outside
            document.addEventListener('click', (e) => {
                if (isMenuOpen &&
                    !e.target.closest('.nav-links') &&
                    !e.target.closest('.menu-btn')) {
                    closeMenu();
                }
            });
        });
    </script>
</body>

</html>