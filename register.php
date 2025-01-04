<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        die("Error: Username dan password tidak boleh kosong.");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert data ke database
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Registrasi berhasil! <a href='login.html'>Login di sini</a>";
    } else {
        if (strpos($conn->error, 'Duplicate entry') !== false) {
            echo "Error: Username sudah digunakan.";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Gunakan metode POST untuk mengakses halaman ini.";
}
?>
