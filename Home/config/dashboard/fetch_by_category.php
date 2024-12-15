<?php
session_start();

include '../database.php';

if (isset($_SESSION['user_id'])) {
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly';

    if ($filter === 'yearly') {
        $sql = "SELECT GROUP_CONCAT(cf.judul ORDER BY cf.judul SEPARATOR ', ') detail, DATE_FORMAT(cf.date, '%Y') date, cf.jenis type, SUM(cf.nominal) total, k.nama_kategori title
                FROM cash_flow cf
                LEFT JOIN kategori k ON cf.kategori_id = k.id
                WHERE user_id = ? AND date >= CURDATE() - INTERVAL 1 YEAR
                GROUP BY YEAR(cf.date), title
                ORDER BY YEAR(cf.date), type desc";
    }else {
        $sql = "SELECT GROUP_CONCAT(cf.judul ORDER BY cf.judul SEPARATOR ', ') detail, DATE_FORMAT(date, '%b-%Y') date, cf.jenis type, SUM(cf.nominal) total, k.nama_kategori title
                FROM cash_flow cf
                LEFT JOIN kategori k ON cf.kategori_id = k.id
                WHERE user_id = ? AND MONTH(cf.date) = MONTH(CURDATE())
                GROUP BY title
                ORDER BY type desc";
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
