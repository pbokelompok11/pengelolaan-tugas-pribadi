<?php 
session_start(); // Pastikan sesi aktif
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_tugas = $_POST['nama_tugas'];
    $tenggat_waktu = $_POST['tenggat_waktu'];
    $kategori = $_POST['kategori'];
    $user_id = $_SESSION['user_id']; // Ambil user_id dari sesi

    if (!empty($nama_tugas) && !empty($tenggat_waktu) && !empty($kategori)) {
        $insert_query = "INSERT INTO tugas (nama_tugas, tenggat_waktu, kategori, user_id) VALUES ('$nama_tugas', '$tenggat_waktu', '$kategori', $user_id)";
        if ($conn->query($insert_query) === TRUE) {
            // Tampilkan alert dan redirect menggunakan JavaScript
            echo "<script>
                alert('Tugas berhasil ditambahkan!');
                window.location.href = 'daftar_tugas.php';
            </script>";
            exit; // Pastikan tidak ada kode lain yang dijalankan
        } else {
            echo "<script>alert('Terjadi kesalahan: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tugas</title>
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

        .task-container {
            width: 90%;
            max-width: 400px;
            background-color: #23272a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        label {
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #7289da;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #5b6eae;
        }

        .add-task {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: #7289da;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .add-task:hover {
            background-color: #5b6eae;
        }

        .scrollable {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 10px;
        }

        .scrollable::-webkit-scrollbar {
            width: 6px;
        }

        .scrollable::-webkit-scrollbar-thumb {
            background-color: #7289da;
            border-radius: 3px;
        }

    </style>
</head>
<body>
    <h1>KELOLA TUGAS</h1>
    <div class="task-container scrollable">
        <form action="" method="POST">
            <label for="nama_tugas">Nama Tugas:</label>
            <input type="text" id="nama_tugas" name="nama_tugas" required>

            <label for="tenggat_waktu">Tenggat Waktu:</label>
            <input type="date" id="tenggat_waktu" name="tenggat_waktu" required>

            <label for="kategori">Kategori:</label>
            <select id="kategori" name="kategori" required>
                <option value="Akademi">Akademi</option>
                <option value="Organisasi">Organisasi</option>
                <option value="Lomba">Lomba</option>
            </select>

            <button type="submit">Simpan</button>
        </form>
    </div>
    <div class="add-task">+</div>
</body>
</html>
