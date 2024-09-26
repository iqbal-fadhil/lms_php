<?php
session_start();
require '../includes/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Get the data from the POST request
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Prepare the SQL statement to update the course
    $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $id);

    // Execute the statement and handle success/failure
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Course updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the course']);
    }

    $stmt->close(); // Close the statement
    exit(); // Exit the script
}
?>
