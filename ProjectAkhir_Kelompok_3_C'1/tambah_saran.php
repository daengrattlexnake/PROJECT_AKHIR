<?php
session_start();
require "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';
    $message = trim($_POST['message'] ?? '');
    $user_id = $_SESSION['user_id'];
    
    // Validasi input
    if (empty($category)) {
        $error_message = "Silakan pilih kategori saran.";
    } elseif (empty($message)) {
        $error_message = "Pesan tidak boleh kosong.";
    } elseif (strlen($message) > 1000) { // Batasi panjang pesan
        $error_message = "Pesan terlalu panjang. Maksimal 1000 karakter.";
    } else {
        // Siapkan query untuk menyimpan saran
        $query = "INSERT INTO suggestion_box (user_id, category, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $user_id, $category, $message);
        
        if ($stmt->execute()) {
            $success_message = "Saran berhasil ditambahkan!";
            // Clear form setelah berhasil
            $_POST = array();
        } else {
            $error_message = "Gagal menambahkan saran. Silakan coba lagi.";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GASP - Tambah Saran</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/tambahsaran.css">
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
                <a href="tentangkami2.php">Tentang Kami</a>
                <a href="editprofil.php">Edit Profil</a>
                <a href="logout.php">Keluar</a>
            </div>
            <button class="menu-btn">☰</button>
        </div>
    </nav>

    <main class="container">
        <h1 class="section-title">Tambah Saran dan Komplain</h1>

        <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="suggestion-form">
            <div class="form-group">
                <label for="category">Kategori:</label>
                <select name="category" id="category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="bug/error" <?php echo (isset($_POST['category']) && $_POST['category'] === 'bug/error') ? 'selected' : ''; ?>>
                        Bug/Error
                    </option>
                    <option value="feature suggestion" <?php echo (isset($_POST['category']) && $_POST['category'] === 'feature suggestion') ? 'selected' : ''; ?>>
                        Saran Fitur
                    </option>
                    <option value="game suggestion" <?php echo (isset($_POST['category']) && $_POST['category'] === 'game suggestion') ? 'selected' : ''; ?>>
                        Saran Game
                    </option>
                    <option value="others" <?php echo (isset($_POST['category']) && $_POST['category'] === 'others') ? 'selected' : ''; ?>>
                        Lainnya
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Pesan:</label>
                <textarea name="message" id="message" rows="6" required 
                          placeholder="Tuliskan saran atau komplain Anda di sini..."
                          maxlength="1000"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                <div class="char-count">
                    <span id="char-current">0</span>/1000 karakter
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">Kirim Saran</button>
                <a href="lihatsaran.php" class="cancel-btn">Batal</a>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <?php
    include("footer.php")
    ?>

    <script>
        // Script untuk menghitung karakter
        const messageTextarea = document.getElementById('message');
        const charCount = document.getElementById('char-current');

        messageTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength;
            
            if (currentLength >= 1000) {
                charCount.style.color = 'red';
            } else {
                charCount.style.color = 'inherit';
            }
        });
    </script>
    <script src="js/logindanregis.js"></script>
</body>
</html>