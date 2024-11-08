<?php
session_start();
require "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data saran dan komplain dari database
$query = "SELECT s.*, u.name, u.profile_photo 
          FROM suggestion_box s 
          LEFT JOIN users u ON s.user_id = u.user_id 
          ORDER BY s.created_at DESC";
$result = $conn->query($query);
$suggestions = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GASP - Saran Dan Komplain</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/lihatsaran.css">
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
                <a href="perpustakaan.php">Perpustakaan</a>
                <a href="lihatsaran.php">Kotak Saran</a>
                <a href="tentangkami2.php">Tentang Kami</a>
                <a href="editprofil.php">Edit Profil</a>
                <a href="logout.php">Keluar</a>
            </div>
            <button class="menu-btn">☰</button>
        </div>
    </nav>

    <main class="reviews-container">
        <h1 class="section-title">Saran Dan Komplain</h1>

        <div class="reviews-grid">
            <?php foreach ($suggestions as $suggestion): ?>
                <div class="review-card">
                    <div class="review-header">
                        <div class="user-info">
                            <img src="<?php echo $suggestion['profile_photo'] ? 'profil/' . htmlspecialchars($suggestion['profile_photo']) : 'default.png'; ?>"
                                alt="User Profile" class="profile-pic">
                            <div class="user-details">
                                <h3><?php echo htmlspecialchars($suggestion['name'] ?? 'Anonymous'); ?></h3>
                                <span class="post-date">
                                    Diposting: <?php echo date('d F Y', strtotime($suggestion['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <div class="recommendation"><?php echo htmlspecialchars($suggestion['category']); ?></div>
                    </div>

                    <div class="review-content">
                        <p><?php echo nl2br(htmlspecialchars($suggestion['message'])); ?></p>
                    </div>

                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <div class="admin-actions">
                            <form method="POST" action="delete_suggestion.php" class="delete-form">
                                <input type="hidden" name="suggestion_id" value="<?php echo $suggestion['suggestion_id']; ?>">
                                <button type="submit" class="delete-btn"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus saran ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if (empty($suggestions)): ?>
                <div class="no-suggestions">
                    <p>Belum ada saran atau komplain yang ditambahkan.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($_SESSION['role'] == 'user'): ?>
            <div class="add-suggestion">
                <a href="tambah_saran.php" class="add-btn">Tambah Saran/Komplain</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php
    include("footer.php")
    ?>

    <script src="js/logindanregis.js"></script>
    <script src="js/1lihatsaran.js"></script>
</body>

</html>