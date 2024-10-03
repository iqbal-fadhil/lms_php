<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imagePath = 'uploads/images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    } else {
        $imagePath = null;
    }

    // Handle Video Upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $videoPath = 'uploads/videos/' . basename($_FILES['video']['name']);
        move_uploaded_file($_FILES['video']['tmp_name'], $videoPath);
    } else {
        $videoPath = null;
    }

    // Insert course into the database
    $stmt = $conn->prepare("INSERT INTO courses (title, description, image, video) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $title, $description, $imagePath, $videoPath);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Course created successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create course.']);
    }
}
