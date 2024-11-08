<?php
session_start();
require "koneksi.php";

// Buat direktori uploads jika belum ada
$upload_dir = "profil";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Check if the submit button has been pressed
if (isset($_POST["submit"])) {
    $name = $_POST["nama"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = 'user'; // Default role, unless you provide an option to choose
    $profilePhoto = $_FILES["foto"]["name"];

    // Check if the email is already used
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If the email is already in use
        echo "
        <script>
        alert('Email sudah digunakan! Silakan gunakan email lain.');
        document.location.href = 'registrasi.php';
        </script>
        ";
    } else {
        // Proses upload foto profil
        $profile_photo = 'default-profile.jpg'; // Default photo
        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES["foto"]["name"];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array(strtolower($filetype), $allowed)) {
                $new_filename = "profile_" . time() . "." . $filetype;
                $upload_path = $upload_dir . "/" . $new_filename;
                
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $upload_path)) {
                    $profile_photo = $new_filename;
                } else {
                    echo "
                    <script>
                    alert('Gagal mengupload foto profil.');
                    document.location.href = 'registrasi.php';
                    </script>
                    ";
                    exit;
                }
            } else {
                echo "
                <script>
                alert('Format file tidak didukung. Gunakan format: jpg, jpeg, png, atau gif.');
                document.location.href = 'registrasi.php';
                </script>
                ";
                exit;
            }
        }

        // Insert new user data into the database
        $query = "INSERT INTO users (name, email, profile_photo, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $name, $email, $profile_photo, $password, $role);
        
        if ($stmt->execute()) {
            echo "
            <script>
            alert('Registrasi berhasil!');
            console.log('Redirecting to login.php');
            window.location.href = 'login.php';
            </script>
            ";
            exit;
        } else {
            echo "
            <script>
            alert('Registrasi gagal!');
            document.location.href = 'registrasi.php';
            </script>
            ";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
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
                <h1 class="judul">Registrasi</h1>
            </hgroup>
            <form class="login-container" method="POST" enctype="multipart/form-data">
                <div class="form-isi">
                    <label for="email" class="form-judul">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan Email" class="form-input">
                </div>
                <div class="form-isi">
                    <label for="nama" class="form-judul">Nama</label>
                    <input type="text" id="nama" name="nama" required placeholder="Masukkan Nama" class="form-input">
                </div>
                <div class="form-isi password-container">
                    <label for="password" class="form-judul">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password"
                        class="form-input">
                    <img src="assets/show-icon.png" alt="Toggle Password Visibility" class="toggle-password"
                        id="toggle-password">
                </div>
                <div class="form-isi">
                    <label for="foto" class="form-judul">Foto Profil</label>
                    <input type="file" id="foto" name="foto" required class="form-input">
                </div>
                <div>
                    <button class="login-button" type="submit" name="submit">
                        registrasi
                    </button>
                </div>
                <p>
                    Sudah Punya Akun?
                    <a href="login.php" class="link">Masuk Sekarang</a>
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