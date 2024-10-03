<?php
include('../includes/auth.php'); // Ensure only logged-in users can access
include('../includes/db.php');   // Database connection

// Get the course ID from the URL
$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($courseId === 0) {
    echo "Invalid course ID.";
    exit;
}

// Fetch the course details from the database
$stmt = $conn->prepare("SELECT id, title, description, image, video, created_by FROM courses WHERE id = ?");
$stmt->bind_param('i', $courseId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Course not found.";
    exit;
}

$course = $result->fetch_assoc();
?>

<?php include('../includes/header.php'); ?>

<div class="container">
    <h2 class="my-4"><?= $course['title'] ?></h2>

    <p><strong>Description:</strong> <?= $course['description'] ?></p>
    
    <!-- Display course image if available -->
    <?php if (!empty($course['image'])): ?>
        <div>
            <img src="<?= $course['image'] ?>" alt="Course Image" style="width: 300px;">
        </div>
    <?php endif; ?>

    <!-- Display course video if available -->
    <?php if (!empty($course['video'])): ?>
        <div class="mt-4">
            <video width="600" controls>
                <source src="<?= $course['video'] ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    <?php endif; ?>

    <p><strong>Created By:</strong> <?= $course['created_by'] ?></p>

    <!-- Add a back button to go to the course list -->
    <a href="course_management.php" class="btn btn-secondary">Back to Course Management</a>
</div>

<?php include('../includes/footer.php'); ?>
