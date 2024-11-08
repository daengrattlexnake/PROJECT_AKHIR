<?php
session_start();
require_once 'koneksi.php';

// Cek apakah sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $game_name = $_POST['game-name'];
    $description = $_POST['description'];
    $download_link = $_POST['download-link'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert game data
        $sql = "INSERT INTO game_store (game_name, description, download_link) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $game_name, $description, $download_link);
        $stmt->execute();
        
        $game_id = $conn->insert_id;
        
        // Handle image upload
        if(isset($_FILES['Game-Images']) && $_FILES['Game-Images']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['Game-Images']['name'];
            $filetype = $_FILES['Game-Images']['type'];
            $filesize = $_FILES['Game-Images']['size'];
            
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if(in_array($ext, $allowed)) {
                $newname = uniqid('game_') . "." . $ext;
                $upload_dir = 'uploads/games/';
                
                if(!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $path = $upload_dir . $newname;
                
                if(move_uploaded_file($_FILES['Game-Images']['tmp_name'], $path)) {
                    // Insert image record
                    $sql = "INSERT INTO game_images (game_id, image_url) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("is", $game_id, $newname);
                    $stmt->execute();
                }
            }
        }
        
        $conn->commit();
        header("Location: CRUD.php?success=1");
        exit();
        
    } catch(Exception $e) {
        $conn->rollback();
        header("Location: tambah.php?error=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/tambah.css">
    <title>Game Store Data Entry</title>
</head>
<body>
    <div class="container">
        <h1>Tambah Game Baru</h1>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Terjadi kesalahan saat menambahkan game!
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="game-name">Game Name</label>
                <input type="text" id="game-name" name="game-name" placeholder="Enter game name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter game description" required></textarea>
            </div>
            <div class="form-group">
                <label for="download-link">Download Link</label>
                <input type="text" id="download-link" name="download-link" placeholder="Enter download link" required>
            </div>
            <div class="form-group">
                <label for="Game-Images">Gambar Game</label>
                <input type="file" id="Game-Images" name="Game-Images" accept="image/*" required>
            </div>
            <button type="submit">Tambah</button>
            <button type="button" onclick="window.location.href='CRUD.php';">Batal</button>
        </form>
    </div>
</body>
</html>