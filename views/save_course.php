<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['courseId'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (empty($id)) { // Add new course
        $stmt = $conn->prepare("INSERT INTO courses (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $description);
        $stmt->execute();
        $newId = $stmt->insert_id; // Get the newly inserted ID
        $response = [
            'action' => 'add',
            'data' => [
                'id' => $newId,
                'title' => $title,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
    } else { // Update existing course
        $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
        $stmt->execute();
        $response = [
            'action' => 'edit',
            'data' => [
                'id' => $id,
                'title' => $title,
                'description' => $description
            ]
        ];
    }
    echo json_encode($response);
}
?>
