<?php
// Include the database connection file
// Assuming $conn is already defined in the connection file
include '../database.php';

// Query to fetch data from the role table
$sql = "SELECT id, name FROM role";
$result = $conn->query($sql);

// Initialize an empty array to store the data
$roles = [];

if ($result->num_rows > 0) {
    // Fetch each row as an associative array and push it to the roles array
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Return the data as JSON to be used in JavaScript
echo json_encode($roles);
?>
