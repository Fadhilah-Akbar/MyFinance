<?php
// Mulai sesi
session_start();

// Include the database connection
include '../database.php';

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        // Prepare an SQL statement to delete data
        $stmt = $conn->prepare("DELETE FROM kategori WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'kategori deleted successfully!'
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
            'text' => 'Invalid ID.'
        ];
    }

$conn->close();
header("Location: ../../kategori.php");
exit();
?>
