<?php
include('../includes/auth.php');
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update the course in the database
    $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $course_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Course updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update course']);
    }
    exit();
}
?>
