<?php
// Mulai session terlebih dahulu untuk menggunakan $_SESSION
session_start();

// Include the database connection file
include '../database.php';

// Periksa apakah user_id ada di dalam session
if (isset($_SESSION['user_id'])) {
    // Query to fetch data from the cash_flow table with join to kategori table
    $sql = "SELECT cash_flow.id id, cash_flow.judul judul, cash_flow.nominal nominal, kategori.nama_kategori nama, cash_flow.date tanggal
            FROM cash_flow 
            JOIN kategori ON cash_flow.kategori_id = kategori.id 
            WHERE cash_flow.user_id = ? and cash_flow.jenis = 'spend'";

    // Persiapkan statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']); // Bind user_id from session
    
    // Eksekusi dan ambil hasilnya
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize an empty array to store the data
    $incomes = [];

    if ($result->num_rows > 0) {
        // Fetch each row as an associative array and push it to the incomes array
        while ($row = $result->fetch_assoc()) {
            $incomes[] = $row;
        }
    }

    // Return the data as JSON to be used in JavaScript
    echo json_encode($incomes);
    
    // Tutup statement
    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "User ID tidak ditemukan di session"
    ]);
}
?>
