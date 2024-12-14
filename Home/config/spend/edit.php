<?php
// Mulai sesi
session_start();

// Include the database connection
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $judul = isset($_POST['name']) ? trim($_POST['name']) : '';
    $jumlah = isset($_POST['nominal']) ? trim($_POST['nominal']) : '';
    $kategori = isset($_POST['kategori_id']) ? trim($_POST['kategori_id']) : '';
    $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
    $jenis = isset($_POST['jenis']) ? trim($_POST['jenis']) : '';
    $date = isset($_POST['date']) ? $_POST['date'] : date("Y-m-d");

    if ($id > 0 && !empty($judul)) {
        // Prepare an SQL statement to update data
        $stmt = $conn->prepare("UPDATE cash_flow SET judul = ?, nominal = ?, kategori_id = ?, date = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $judul, $jumlah, $kategori, $date, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Data updated successfully!'
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Error: ' . $stmt->error
            ];
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Invalid ID or empty judul.'
        ];
    }
}

$conn->close();
header("Location: ../../spend.php");
exit();
?>
