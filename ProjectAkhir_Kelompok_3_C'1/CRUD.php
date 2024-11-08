<?php
session_start();
require_once 'koneksi.php';

// Cek apakah sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Proses delete
if (isset($_GET['delete'])) {
    $game_id = $_GET['delete'];

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Ambil nama file gambar
        $sql_image = "SELECT image_url FROM game_images WHERE game_id = ?";
        $stmt_image = $conn->prepare($sql_image);
        $stmt_image->bind_param("i", $game_id);
        $stmt_image->execute();
        $result_image = $stmt_image->get_result();
        $image = $result_image->fetch_assoc();

        // Hapus file gambar jika ada
        if ($image && $image['image_url']) {
            $image_path = 'uploads/games/' . $image['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Hapus record dari game_images
        $sql_delete_image = "DELETE FROM game_images WHERE game_id = ?";
        $stmt_delete_image = $conn->prepare($sql_delete_image);
        $stmt_delete_image->bind_param("i", $game_id);
        $stmt_delete_image->execute();

        // Hapus record dari game_store
        $sql_delete_game = "DELETE FROM game_store WHERE game_id = ?";
        $stmt_delete_game = $conn->prepare($sql_delete_game);
        $stmt_delete_game->bind_param("i", $game_id);
        $stmt_delete_game->execute();

        // Commit transaksi
        $conn->commit();

        // Redirect dengan pesan sukses
        header("Location: CRUD.php?delete_success=1");
        exit();

    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan
        $conn->rollback();
        header("Location: CRUD.php?delete_error=1");
        exit();
    }
}

// Ambil data game dari database
$sql = "SELECT g.*, gi.image_url 
        FROM game_store g 
        LEFT JOIN game_images gi ON g.game_id = gi.game_id 
        ORDER BY g.game_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/users.css">
    <style>
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }

        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
    <script>
        function confirmDelete(gameId) {
            if (confirm('Apakah Anda yakin ingin menghapus game ini?')) {
                window.location.href = 'CRUD.php?delete=' + gameId;
            }
        }
    </script>
</head>

<body class="flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
        <nav>
            <a href="dashboard.php" class="sidebar-link">Dashboard</a>
            <a href="users.php" class="sidebar-link">Users</a>
            <a href="CRUD.php" class="sidebar-link active">Toko</a>
            <a href="logout_admin.php" class="sidebar-link text-red-500 mt-auto"
                onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                Logout
            </a>
        </nav>
    </div>

    <!-- Content -->
    <div class="data-user-section">
        <h2 class="table-heading">Toko Games</h2>

        <?php
        // Tampilkan pesan sukses atau error
        if (isset($_GET['delete_success'])): ?>
            <div class="alert alert-success">
                Game berhasil dihapus!
            </div>
        <?php endif;

        if (isset($_GET['delete_error'])): ?>
            <div class="alert alert-danger">
                Gagal menghapus game!
            </div>
        <?php endif;

        if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">
                Game berhasil ditambahkan!
            </div>
        <?php endif;

        if (isset($_GET['success']) && $_GET['success'] == 2): ?>
            <div class="alert alert-success">
                Game berhasil diupdate!
            </div>
        <?php endif; ?>

        <div class="tambah-container">
            <a href="tambah.php">
                <button class="tambah">Tambah</button>
            </a>
        </div>
        <table class="table-mahasiswa">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Game Name</th>
                    <th>Game Photo</th>
                    <th>Description</th>
                    <th>Download Link</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['game_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['game_name']); ?></td>
                            <td>
                                <?php
                                $image_url = $row['image_url'] ? 'uploads/games/' . htmlspecialchars($row['image_url']) : 'profile-placeholder.jpg';
                                ?>
                                <img src="<?php echo $image_url; ?>" alt="Game Photo" width="50">
                            </td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($row['download_link']); ?>">click here</a></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['game_id']; ?>">
                                    <button>Edit</button>
                                </a>
                                <button onclick="confirmDelete(<?php echo $row['game_id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6">Tidak ada data game</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>