<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO courses (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $created_by); // Assuming 'created_by' comes from the session

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Course created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create the course']);
    }

    $stmt->close();
    exit();
}
?>
