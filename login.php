<?php
require 'koneksi.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        die("Error: Username dan password tidak boleh kosong.");
    }

    // Query untuk mendapatkan data pengguna
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session untuk pengguna
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            echo "Login berhasil! <a href='dashboard.html'>Masuk ke Dashboard</a>";
        } else {
            echo "Error: Password salah.";
        }
    } else {
        echo "Error: Username tidak ditemukan.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Gunakan metode POST untuk mengakses halaman ini.";
}
?>
