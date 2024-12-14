<?php
// Mulai sesi
session_start();

// Include the database connection
include '../database.php';

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        // Prepare an SQL statement to delete data
        $stmt = $conn->prepare("DELETE FROM cash_flow WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Spending deleted successfully!'
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
header("Location: ../../spend.php");
exit();
?>
