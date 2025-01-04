<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu!");
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

// Menangani perubahan status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    $status = isset($_POST['status']) ? 1 : 0;  // Status menjadi 1 jika dicentang, 0 jika tidak
    
    // Update status tugas di database
    $update_query = "UPDATE tugas SET status = $status WHERE id = $task_id AND user_id = $user_id";
    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Status tugas berhasil diperbarui!');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . $conn->error . "');</script>";
    }
}

$result = $conn->query("SELECT * FROM tugas WHERE user_id = $user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #2f3338; /* Warna latar belakang gelap */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px black;
        }

        .task-list {
            width: 100%;
            max-width: 400px; /* Batas lebar maksimal */
            background-color: #e4e4e4;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            overflow-y: auto; /* Scroll jika tugas banyak */
            max-height: 500px; /* Batas tinggi maksimal */
        }

        .task-item {
            display: flex;
            align-items: flex-start;
            padding: 10px;
            margin-bottom: 10px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .task-item input[type="checkbox"] {
            margin-right: 10px;
            margin-top: 8px;
            width: 20px;
            height: 20px;
            border: 2px solid black;
            border-radius: 50%;
            cursor: pointer;
        }

        .task-details {
            flex-grow: 1;
        }

        .task-details div {
            background-color: #d4d4d4;
            margin: 5px 0;
            padding: 10px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: bold;
            color: black;
        }

        .task-details div:first-child {
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <h1>DAFTAR TUGAS</h1>
    <div class="task-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="task-item">
                    <form action="" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                        <input type="checkbox" name="status" value="1" <?php echo $row['status'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                    </form>
                    <div class="task-details">
                        <div>Nama Tugas: <?php echo htmlspecialchars($row['nama_tugas']); ?></div>
                        <div>Tenggat Waktu: <?php echo htmlspecialchars($row['tenggat_waktu']); ?></div>
                        <div>Kategori: <?php echo htmlspecialchars($row['kategori']); ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada tugas.</p>
        <?php endif; ?>
    </div>
</body>
</html>
