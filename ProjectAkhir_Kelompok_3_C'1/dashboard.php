<?php
session_start();
require_once 'koneksi.php';

// Cek apakah sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Hitung total users
$users_query = "SELECT COUNT(*) as total_users FROM users";
$users_result = $conn->query($users_query);
$total_users = $users_result->fetch_assoc()['total_users'];

// Hitung total games
$games_query = "SELECT COUNT(*) as total_games FROM game_store";
$games_result = $conn->query($games_query);
$total_games = $games_result->fetch_assoc()['total_games'];

// Ambil aktivitas terbaru (penambahan game)
$activity_query = "SELECT game_id, game_name, description, created_at 
                   FROM game_store 
                   ORDER BY created_at DESC 
                   LIMIT 5";
$activity_result = $conn->query($activity_query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>

<body class="flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
        <nav>
            <a href="dashboard.php" class="sidebar-link active">Dashboard</a>
            <a href="users.php" class="sidebar-link">Users</a>
            <a href="CRUD.php" class="sidebar-link">Toko</a>
            <a href="logout_admin.php" class="sidebar-link text-red-500 mt-auto"
                onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                Logout
            </a>
        </nav>
    </div>

    <!-- Content -->
    <div class="content flex-1">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Dashboard</h1>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div class="card">
                <h3 class="text-lg font-bold mb-2">Total Users</h3>
                <p class="text-4xl font-bold"><?php echo $total_users; ?></p>
            </div>
            <div class="card">
                <h3 class="text-lg font-bold mb-2">Total Games</h3>
                <p class="text-4xl font-bold"><?php echo $total_games; ?></p>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Recent Game Additions</h2>
            <div class="card">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="py-2 px-4 text-left">Game ID</th>
                            <th class="py-2 px-4 text-left">Game Name</th>
                            <th class="py-2 px-4 text-left">Description</th>
                            <th class="py-2 px-4 text-left">Added Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($activity_result->num_rows > 0) {
                            while ($row = $activity_result->fetch_assoc()) {
                                ?>
                                <tr class="border-b">
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($row['game_id']); ?></td>
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($row['game_name']); ?></td>
                                    <td class="py-2 px-4">
                                        <?php echo htmlspecialchars(substr($row['description'], 0, 50) . '...'); ?>
                                    </td>
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($row['created_at']); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" class="py-2 px-4 text-center">No recent game additions</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>