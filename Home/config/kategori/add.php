<?php
// Mulai sesi
session_start();

// Include the database connection
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $is_deleted = 0;
    if (!empty($name)) {
        // Prepare an SQL statement to insert data
        $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori , is_deleted) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $is_deleted);

        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Kategori added successfully!'
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
            'text' => 'Kategori name is required.'
        ];
    }
}

$conn->close();
header("Location: ../../kategori.php");
exit();
?>
