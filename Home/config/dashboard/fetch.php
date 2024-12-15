<?php
session_start();

include '../database.php';

if (isset($_SESSION['user_id'])) {

    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly';

    if ($filter === 'yearly') {
        $sql = "SELECT cf.jenis type, cf.judul detail, cf.nominal nominal, kategori.nama_kategori category, DATE_FORMAT(cf.date, '%d-%b-%Y') date
                FROM cash_flow cf 
                LEFT JOIN kategori ON cf.kategori_id = kategori.id 
                WHERE cf.user_id = ? AND date >= CURDATE() - INTERVAL 1 YEAR
                ORDER BY cf.date desc";
    }else{
        $sql = "SELECT cf.jenis type, cf.judul detail, cf.nominal nominal, kategori.nama_kategori category, DATE_FORMAT(cf.date, '%d-%b-%Y') date
                FROM cash_flow cf 
                JOIN kategori ON cf.kategori_id = kategori.id 
                WHERE cf.user_id = ? AND MONTH(date) = MONTH(CURDATE())
                ORDER BY cf.date desc";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']); 
    
    $stmt->execute();
    $result = $stmt->get_result();

    $cashFlow = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cashFlow[] = $row;
        }
    }

    echo json_encode($cashFlow);
    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "User ID tidak ditemukan di session"
    ]);
}
?>
