<?php
session_start(); // Memulai sesi

// Hapus sesi pengguna
session_unset();  // Menghapus semua variabel sesi
session_destroy(); // Menghancurkan sesi

// Redirect ke halaman login setelah logout
header("Location: login.html");
exit();
?>
