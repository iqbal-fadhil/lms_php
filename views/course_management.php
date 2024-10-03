<?php
include('../includes/auth.php'); // Ensure only logged-in users can access
include('../includes/db.php');   // Database connection

// Fetch all courses from the database
$result = $conn->query("SELECT id, title, description, image, video, metadata FROM courses");

// Check if the user is an admin
$is_admin = ($_SESSION['role'] === 'admin');
?>

<?php include('../includes/header.php'); ?>

<div class="container">
    <h2 class="my-4">Course Management</h2>

    <!-- Create New Course Button (Visible only to admin users) -->
    <?php if ($is_admin): ?>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createCourseModal">Create New Course</button>
    <?php endif; ?>

    <!-- Display Courses in a Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Metadata</th>
                <?php if ($is_admin): ?> <!-- Show Actions column only for admin -->
                    <th>Actions</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Video</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($course = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $course['id'] ?></td>
                <td>
                    <a href="course_detail.php?id=<?= $course['id'] ?>"><?= $course['title'] ?></a>
                </td>
                <td><?= $course['metadata'] ?></td>
                <?php if ($is_admin): ?> <!-- Show Actions column only for admin -->
                <td><?= $course['description'] ?></td>
                <td>
                    <?php if ($course['image']): ?>
                        <img src="<?= $course['image'] ?>" alt="Course Image" style="width: 100px;">
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($course['video']): ?>
                        <video width="200" controls>
                            <source src="<?= $course['video'] ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                </td>
                <?php endif; ?>

                <?php if ($is_admin): ?> <!-- Show actions only for admin users -->
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="openEditCourseModal(<?= $course['id'] ?>, '<?= $course['title'] ?>', '<?= $course['description'] ?>')">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteCourse(<?= $course['id'] ?>)">Delete</button>
                    </td>
                <?php endif; ?>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modals and JavaScript remain unchanged -->
