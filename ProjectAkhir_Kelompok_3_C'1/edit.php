<?php
session_start();
require_once 'koneksi.php';

// Cek apakah sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$game_id = $_GET['id'] ?? null;
if (!$game_id) {
    header('Location: CRUD.php');
    exit();
}

// Fetch game data
$sql = "SELECT g.*, gi.image_url 
        FROM game_store g 
        LEFT JOIN game_images gi ON g.game_id = gi.game_id 
        WHERE g.game_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    header('Location: CRUD.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $game_name = $_POST['game-name'];
    $description = $_POST['description'];
    $download_link = $_POST['download-link'];

    $conn->begin_transaction();

    try {
        // Update game data
        $sql = "UPDATE game_store SET game_name = ?, description = ?, download_link = ? WHERE game_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $game_name, $description, $download_link, $game_id);
        $stmt->execute();

        // Handle new image upload if provided
        if (isset($_FILES['Game-Images']) && $_FILES['Game-Images']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['Game-Images']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array($ext, $allowed)) {
                // Delete old image
                if ($game['image_url']) {
                    $old_path = 'uploads/games/' . $game['image_url'];
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                    $sql = "DELETE FROM game_images WHERE game_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $game_id);
                    $stmt->execute();
                }

                // Upload new image
                $newname = uniqid('game_') . "." . $ext;
                $upload_dir = 'uploads/games/';
                $path = $upload_dir . $newname;

                if (move_uploaded_file($_FILES['Game-Images']['tmp_name'], $path)) {
                    $sql = "INSERT INTO game_images (game_id, image_url) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("is", $game_id, $newname);
                    $stmt->execute();
                }
            }
        }

        $conn->commit();
        header("Location: CRUD.php?success=2");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: edit.php?id=$game_id&error=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/edit.css">
    <title>Edit Game</title>
</head>

<body>
    <div class="container">
        <h1>Edit Game</h1>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Terjadi kesalahan saat mengupdate game!
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="game-name">Game Name</label>
                <input type="text" id="game-name" name="game-name"
                    value="<?php echo htmlspecialchars($game['game_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                    required><?php echo htmlspecialchars($game['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="download-link">Download Link</label>
                <input type="text" id="download-link" name="download-link"
                    value="<?php echo htmlspecialchars($game['download_link']); ?>" required>
            </div>
            <div class="form-group">
                <label>Current Image</label>
                <?php if ($game['image_url']): ?>
                    <img src="uploads/games/<?php echo htmlspecialchars($game['image_url']); ?>" alt="Current game image"
                        style="max-width: 200px;">
                <?php else: ?>
                    <p>No image available</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="Game-Images">Update Game Image</label>
                <input type="file" id="Game-Images" name="Game-Images" accept="image/*">
            </div>
            <button type="submit">Simpan</button>
            <button type="button" onclick="window.location.href='CRUD.php';">Batal</button>
        </form>
    </div>
</body>

</html>