<?php
// Mulai session terlebih dahulu untuk menggunakan $_SESSION
session_start();

// Include the database connection file
include '../database.php';

// Periksa apakah user_id ada di dalam session
if (isset($_SESSION['user_id'])) {
    // Ambil filter yang dikirimkan dari frontend
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'daily';

    // Initialize arrays untuk labels, incomeData, dan expenseData
    $labels = [];
    $incomeData = [];
    $expenseData = [];

    // Menyesuaikan query SQL berdasarkan filter
    if ($filter === 'yearly') {
        // Query for yearly data
        $sql = "SELECT YEAR(date) AS year, 
                       SUM(CASE WHEN jenis = 'income' THEN nominal ELSE 0 END) AS income,
                       SUM(CASE WHEN jenis = 'spend' THEN nominal ELSE 0 END) AS expense
                FROM cash_flow 
                WHERE user_id = ? 
                GROUP BY YEAR(date)
                ORDER BY YEAR(date)";
    }elseif ($filter === 'monthly') {
        // Query for monthly data (1 year range)
        $sql = "SELECT DATE_FORMAT(date, '%b-%Y') AS month, 
                       SUM(CASE WHEN jenis = 'income' THEN nominal ELSE 0 END) AS income,
                       SUM(CASE WHEN jenis = 'spend' THEN nominal ELSE 0 END) AS expense
                FROM cash_flow 
                WHERE user_id = ? AND date >= CURDATE() - INTERVAL 1 YEAR
                GROUP BY YEAR(date), MONTH(date)
                ORDER BY YEAR(date), MONTH(date)";
    }else {
        // Query for daily data (1 month range)
        $sql = "SELECT DATE_FORMAT(date, '%d-%b') AS day, 
                       SUM(CASE WHEN jenis = 'income' THEN nominal ELSE 0 END) AS income,
                       SUM(CASE WHEN jenis = 'spend' THEN nominal ELSE 0 END) AS expense
                FROM cash_flow 
                WHERE user_id = ? AND date >= CURDATE() - INTERVAL 1 MONTH
                GROUP BY DAY(date)
                ORDER BY DAY(date)";
    }
    

    // Persiapkan statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']); // Bind user_id dari session
    
    // Eksekusi dan ambil hasilnya
    $stmt->execute();
    $result = $stmt->get_result();

    // Proses hasil query
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['year'] ?? $row['month'] ?? $row['day']; // Ambil label yang sesuai
            $incomeData[] = (float)$row['income'];
            $expenseData[] = (float)$row['expense'];
        }
    }

    // Return data dalam format JSON untuk digunakan di JavaScript
    echo json_encode([
        "labels" => $labels,
        "incomeData" => $incomeData,
        "expenseData" => $expenseData
    ]);
    
    // Tutup statement
    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "User ID tidak ditemukan di session"
    ]);
}
?>
