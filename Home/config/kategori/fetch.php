<?php
// Include the database connection file
// Assuming $conn is already defined in the connection file
include '../database.php';

// Query to fetch data from the role table
$sql = "SELECT id, nama_kategori FROM kategori";
$result = $conn->query($sql);

// Initialize an empty array to store the data
$categories = [];

if ($result->num_rows > 0) {
    // Fetch each row as an associative array and push it to the categories array
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Return the data as JSON to be used in JavaScript
echo json_encode($categories);
?>
