<?php
session_start();
require 'db.php'; // Assuming your db.php file contains the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Check if deletion was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Course deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the course']);
    }

    $stmt->close();
    exit();
}
?>
