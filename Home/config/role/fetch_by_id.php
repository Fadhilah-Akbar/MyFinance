<?php
// Mulai sesi
session_start();

// Include the database connection
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        // Prepare an SQL statement to fetch data
        $stmt = $conn->prepare("SELECT id, name FROM role WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $role = $result->fetch_assoc();

            if ($role) {
                // Return the role data as JSON
                header('Content-Type: application/json');
                echo json_encode($role);
            } else {
                // If no data found
                header('HTTP/1.0 404 Not Found');
                echo json_encode(['message' => 'Role not found.']);
            }
        } else {
            // Query execution error
            header('HTTP/1.0 500 Internal Server Error');
            echo json_encode(['message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        // Invalid ID
        header('HTTP/1.0 400 Bad Request');
        echo json_encode(['message' => 'Invalid ID.']);
    }
}

$conn->close();
?>
