<?php
session_start();
require "koneksi.php";

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form sudah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Tidak perlu escape string untuk password yang akan diverifikasi

    // Query untuk memeriksa kredensial pengguna
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Untuk admin dengan plain text password
        if ($user['role'] == 'admin' && $password == $user['password']) {
            // Set variabel sesi
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            echo "<script>alert('Selamat datang, Admin!');</script>";
            echo "<script>window.location.href = 'dashboard.php';</script>";
            exit;
        }
        // Untuk user biasa dengan hashed password
        else if ($user['role'] == 'user' && password_verify($password, $user['password'])) {
            // Set variabel sesi
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            echo "<script>alert('Selamat datang, User!');</script>";
            echo "<script>window.location.href = 'index2.php';</script>";
            exit;
        } else {
            echo "<script>alert('Password salah. Silakan coba lagi.');</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan. Silakan periksa kembali.');</script>";
    }
}

// Sisanya adalah HTML form yang sama...
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="logindanregistrasi.css">
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
                <a href="tentangkami.php">Tentang Kami</a>
            </div>
            <button class="menu-btn">☰</button>
        </div>
    </nav>

    <div class="latar">
        <section class="kotak-login">
            <hgroup>
                <h1 class="judul">Login</h1>
            </hgroup>
            <form class="login-container" method="POST">
                <div class="form-isi">
                    <label for="name" class="form-judul">Nama</label>
                    <input type="text" id="name" name="name" required placeholder="Masukkan Nama" class="form-input">
                </div>
                <div class="form-isi">
                    <label for="email" class="form-judul">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan Email"
                        class="form-input">
                </div>
                <div class="form-isi password-container">
                    <label for="password" class="form-judul">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password"
                        class="form-input">
                    <img src="assets/show-icon.png" alt="Toggle Password Visibility" class="toggle-password"
                        id="toggle-password">
                </div>

                <div>
                    <button type="submit" name="submit" class="login-button">
                        Login
                    </button>
                </div>
                <p>
                    Belum Punya Akun?
                    <a href="registrasi.php" class="link"> Daftar Sekarang</a>
                </p>
            </form>
        </section>
    </div>

    <!-- Footer -->
    <?php
    include("footer.php")
    ?>
    
    <script src="js/logindanregis.js"></script>
    <script src="js/logindanregistrasi.js"></script>
</body>

</html>