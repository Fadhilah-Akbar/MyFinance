<?php
session_start();

include '../database.php';

if (isset($_SESSION['user_id'])) {
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly';
    $start = isset($_GET['start']) ? $_GET['start'] : '';
    $end = isset($_GET['end']) ? $_GET['end'] : '';

    $sql = "SELECT GROUP_CONCAT(cf.judul ORDER BY cf.judul SEPARATOR ', ') detail, 
                ";

    if ($filter === 'yearly') {
        $sql = $sql . "DATE_FORMAT(cf.date, '%Y - %M') date, cf.jenis type, SUM(cf.nominal) total, k.nama_kategori title
                FROM cash_flow cf
                LEFT JOIN kategori k ON cf.kategori_id = k.id
                WHERE user_id = ? AND cf.date >= CURDATE() - INTERVAL 1 YEAR";
    }else {
        $sql = $sql . "DATE_FORMAT(cf.date, '%Y - %M') date, cf.jenis type, SUM(cf.nominal) total, k.nama_kategori title
                FROM cash_flow cf
                LEFT JOIN kategori k ON cf.kategori_id = k.id
                WHERE user_id = ? AND cf.date >= CURDATE() - INTERVAL 1 MONTH";
    }

    if(!empty($start) && !empty($end)){
        $sql = $sql . " AND cf.date BETWEEN ? AND ? GROUP BY title ORDER BY type desc";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $_SESSION['user_id'], $start, $end); 
    }else{
        $sql = $sql . " GROUP BY title ORDER BY type desc";
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
