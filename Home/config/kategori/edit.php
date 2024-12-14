<?php
// Mulai sesi
session_start();

// Include the database connection
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if ($id > 0 && !empty($name)) {
        // Prepare an SQL statement to update data
        $stmt = $conn->prepare("UPDATE kategori SET nama_kategori = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'kategori updated successfully!'
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
            'text' => 'Invalid ID or empty kategori name.'
        ];
    }
}

$conn->close();
header("Location: ../../kategori.php");
exit();
?>
