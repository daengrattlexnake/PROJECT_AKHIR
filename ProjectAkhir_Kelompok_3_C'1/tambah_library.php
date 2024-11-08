<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['game_id'])) {
    header("Location: toko.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$game_id = $_GET['game_id'];

// Cek apakah game sudah ada di perpustakaan user
$check_query = "SELECT * FROM game_library WHERE user_id = ? AND game_id = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Jika belum ada, tambahkan ke perpustakaan
    $insert_query = "INSERT INTO game_library (user_id, game_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $game_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: perpustakaan.php?success=1");
    } else {
        header("Location: toko.php?error=1");
    }
} else {
    header("Location: perpustakaan.php?exists=1");
}

mysqli_close($conn);
?>