<?php
session_start();
require "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Buat direktori uploads jika belum ada
$upload_dir = "profil";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Ambil data user
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Proses update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $errors = [];

    // Validasi input
    if (empty($name)) {
        $errors[] = "Nama tidak boleh kosong";
    }
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

    // Jika ada password baru
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password minimal 6 karakter";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Password tidak cocok";
        }
    }

    // Upload foto profil jika ada
    $profile_photo = $user['profile_photo']; // default ke foto yang ada
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        // Validasi error upload
        if ($_FILES['profile_photo']['error'] !== 0) {
            switch ($_FILES['profile_photo']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $errors[] = "File melebihi batas upload_max_filesize pada php.ini";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = "File melebihi batas MAX_FILE_SIZE yang ditentukan pada form";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = "File hanya terupload sebagian";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = "Tidak ada file yang diupload";
                    break;
                default:
                    $errors[] = "Error upload tidak dikenal";
            }
        } else {
            // Validasi ukuran file (5MB)
            if ($_FILES['profile_photo']['size'] > 5000000) {
                $errors[] = "Ukuran file terlalu besar. Maksimal 5MB";
            } else {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['profile_photo']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                if (in_array(strtolower($filetype), $allowed)) {
                    $new_filename = "profile_" . $user_id . "_" . time() . "." . $filetype;
                    $upload_path = $upload_dir . "/" . $new_filename;

                    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                        // Hapus foto lama jika ada dan bukan default
                        if (
                            $user['profile_photo'] &&
                            file_exists("profil/" . $user['profile_photo']) &&
                            $user['profile_photo'] != 'default-profile.jpg'
                        ) {
                            unlink("profil/" . $user['profile_photo']);
                        }
                        $profile_photo = $new_filename;
                    } else {
                        $errors[] = "Gagal mengupload foto profil: " . error_get_last()['message'];
                        error_log("Upload error: " . print_r($_FILES['profile_photo'], true));
                    }
                } else {
                    $errors[] = "Format file tidak didukung. Gunakan format: " . implode(', ', $allowed);
                }
            }
        }
    }

    // Update database jika tidak ada error
    if (empty($errors)) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET name = ?, email = ?, password = ?, profile_photo = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $name, $email, $hashed_password, $profile_photo, $user_id);
        } else {
            $query = "UPDATE users SET name = ?, email = ?, profile_photo = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $name, $email, $profile_photo, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Profil berhasil diperbarui!";
            header("Location: editprofil.php");
            exit;
        } else {
            $errors[] = "Gagal memperbarui profil";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GASP - Edit Profil</title>
    <link rel="stylesheet" href="styles/editprofil.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            text-align: center;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
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
                <a href="index2.php">Beranda</a>
                <a href="toko.php">Toko</a>
                <a href="perpustakaan.php">Perpustakaan</a>
                <a href="lihatsaran.php">Kotak Saran</a>
                <a href="tentangkami2.php">Tentang Kami</a>
                <a href="editprofil.php">Edit Profil</a>
            </div>
            <button class="menu-btn">☰</button>
        </div>
    </nav>

    <section class="edit-profile">
        <h2 class="section-title">Edit Profil</h2>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php
                echo htmlspecialchars($_SESSION['success_message']);
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($user['profile_photo'] ? 'profil/' . $user['profile_photo'] : 'default-profile.jpg'); ?>"
                    alt="Current Profile Picture">
                <div class="upload-btn-wrapper">
                    <label for="profile_photo" class="upload-btn">Ubah Foto Profil</label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <button type="submit" class="save-btn">Simpan Perubahan</button>
        </form>
    </section>

    <!-- Footer -->
    <?php
    include("footer.php")
    ?>
    
    <script src="js/logindanregis.js"></script>
    <script src="js/editprofil.js"></script>
</body>

</html>