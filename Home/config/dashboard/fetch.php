<?php
session_start();

include '../database.php';

if (isset($_SESSION['user_id'])) {

    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly';
    $start = isset($_GET['start']) ? $_GET['start'] : '';
    $end = isset($_GET['end']) ? $_GET['end'] : '';

    $sql = "SELECT cf.jenis type, cf.judul detail, cf.nominal nominal, kategori.nama_kategori category, DATE_FORMAT(cf.date, '%d-%b-%Y') date
                FROM cash_flow cf 
                LEFT JOIN kategori ON cf.kategori_id = kategori.id 
                WHERE cf.user_id = ?";

    if ($filter === 'yearly') {
        $sql = $sql . " AND date >= CURDATE() - INTERVAL 1 YEAR";
    }else{
        $sql = $sql . " AND date >= CURDATE() - INTERVAL 1 MONTH";
    }

    if(!empty($start) && !empty($end)){
        $sql = $sql . " AND cf.date BETWEEN ? AND ? ORDER BY cf.date desc";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $_SESSION['user_id'],$start,$end); 
    }else{
        $sql = $sql . " ORDER BY cf.date desc";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']); 
    }
    
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
