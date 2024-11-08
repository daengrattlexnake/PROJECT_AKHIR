<?php
// Mulai sesi jika belum dimulai
session_start();

// Periksa apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika bukan admin atau belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Hapus semua data sesi
$_SESSION = array();

// Hapus cookie sesi jika ada
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Hancurkan sesi
session_destroy();

// Redirect ke halaman login dengan pesan
echo "<script>alert('Anda telah berhasil logout!');</script>";
echo "<script>window.location.href = 'login.php';</script>";
exit();
?>