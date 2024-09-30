<?php
include('../includes/auth.php');
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $created_by = $_POST['created_by']; // Assuming this is the user ID or username of the creator

    // Insert the new course into the database
    $stmt = $conn->prepare("INSERT INTO courses (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $created_by);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Course created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create course']);
    }
    exit();
}
?>
