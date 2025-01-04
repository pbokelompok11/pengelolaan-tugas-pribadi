<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_users";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Proses pembaruan nama lengkap
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nama_lengkap = $_POST['nama_lengkap'];

    // Update nama lengkap di database
    $update_query = "UPDATE users SET nama_lengkap = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $nama_lengkap, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Profil berhasil diperbarui!');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . $conn->error . "');</script>";
    }
}

// Proses logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2f33;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px black;
        }

        .profile-container {
            width: 90%;
            max-width: 400px;
            background-color: #23272a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .icon {
            width: 50px;
            height: 50px;
            background-color: #2c2f33;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .icon img {
            width: 24px;
            height: 24px;
        }

        label {
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
        }

        button {
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #7289da;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 0 1%;
        }

        button:hover {
            background-color: #5b6eae;
        }

        .button-group {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <h1>PROFIL</h1>
    <div class="profile-container">
        <div class="icon">
            <img src="user.png" alt="User Icon">
        </div>
        <form action="" method="POST">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
            <div class="button-group">
                <button type="submit" name="update_profile">Simpan</button>
                <button type="submit" name="logout">Log Out</button>
            </div>
        </form>
    </div>
</body>
</html>
