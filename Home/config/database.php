<?php
// Konfigurasi database
$host = "localhost";        // Nama host
$user = "root";         // Username database
$password = "";     // Password database
$database = "finance";// Nama database

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>
