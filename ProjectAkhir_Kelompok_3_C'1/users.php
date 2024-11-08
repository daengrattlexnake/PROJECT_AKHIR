<?php
session_start();
require_once 'koneksi.php';

// Cek apakah sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fungsi Delete User
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];

    // Mulai transaksi untuk memastikan konsistensi data
    $conn->begin_transaction();

    try {
        // Hapus foto profil jika ada
        $query_select_photo = "SELECT profile_photo FROM users WHERE user_id = ?";
        $stmt_select = $conn->prepare($query_select_photo);
        $stmt_select->bind_param("i", $user_id);
        $stmt_select->execute();
        $result_photo = $stmt_select->get_result();
        $user_photo = $result_photo->fetch_assoc()['profile_photo'];

        // Hapus file foto profil dari server jika ada
        if (!empty($user_photo) && file_exists('profiles/' . $user_photo)) {
            unlink('profiles/' . $user_photo);
        }

        // Hapus user dari database
        $query_delete = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query_delete);
        $stmt->bind_param("i", $user_id);

        // Eksekusi query
        if ($stmt->execute()) {
            // Commit transaksi
            $conn->commit();
            $_SESSION['pesan'] = "User berhasil dihapus";
        } else {
            // Rollback jika gagal
            $conn->rollback();
            $_SESSION['error'] = "Gagal menghapus user: " . $stmt->error;
        }

        // Tutup statement
        $stmt_select->close();
        $stmt->close();
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $conn->rollback();
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    }

    header("Location: users.php");
    exit();
}

// Ambil data users
$query = "SELECT * FROM users";
$result = $conn->query($query); // Ganti $koneksi menjadi $conn
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/users.css">
</head>

<body class="flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
        <nav>
            <a href="dashboard.php" class="sidebar-link">Dashboard</a>
            <a href="users.php" class="sidebar-link active">Users</a>
            <a href="CRUD.php" class="sidebar-link">Toko</a>
            <a href="logout_admin.php" class="sidebar-link text-red-500 mt-auto"
                onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                Logout
            </a>
        </nav>
    </div>

    <!-- Content -->
    <div class="data-user-section">
        <h2 class="table-heading">User Information</h2>

        <!-- Tampilkan pesan sukses/error jika ada -->
        <?php
        if (isset($_SESSION['pesan'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['pesan'] . "</div>";
            unset($_SESSION['pesan']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <table class="table-mahasiswa">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Profile Photo</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>
                            <img src="<?php
                            echo !empty($row['profile_photo'])
                                ? 'profil/' . htmlspecialchars($row['profile_photo'])
                                : 'default.png';
                            ?>" alt="Profile Photo" width="50">
                        </td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <a href="users.php?delete=<?php echo $row['user_id']; ?>"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');"
                                class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>