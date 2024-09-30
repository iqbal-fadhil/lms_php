<?php
include('../includes/auth.php'); // Ensure only logged-in users can access
include('../includes/db.php');   // Database connection

// Fetch all courses from the database
$result = $conn->query("SELECT id, title, description, created_by FROM courses");
?>

<?php include('../includes/header.php'); ?>

<div class="container">
    <h2 class="my-4">Course Management</h2>

    <!-- Create New Course Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createCourseModal">Create New Course</button>

    <!-- Display Courses in a Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($course = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $course['id'] ?></td>
                <td><?= $course['title'] ?></td>
                <td><?= $course['description'] ?></td>
                <td><?= $course['created_by'] ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="openEditCourseModal(<?= $course['id'] ?>, '<?= $course['title'] ?>', '<?= $course['description'] ?>')">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCourse(<?= $course['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Create Course Modal -->
<div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCourseModalLabel">Create Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createCourseForm">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCourseForm">
                    <input type="hidden" id="editCourseId" name="id">
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for handling async operations -->
<script>
document.getElementById('createCourseForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    const response = await fetch('create_course.php', {
        method: 'POST',
        body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
        alert(result.message);
        location.reload(); // Refresh the page
    } else {
        alert(result.message);
    }
});

function openEditCourseModal(courseId, title, description) {
    document.getElementById('editCourseId').value = courseId;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDescription').value = description;
    const modal = new bootstrap.Modal(document.getElementById('editCourseModal'));
    modal.show();
}

document.getElementById('editCourseForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    const response = await fetch('edit_course.php', {
        method: 'POST',
        body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
        alert(result.message);
        location.reload(); // Refresh the page
    } else {
        alert(result.message);
    }
});

async function deleteCourse(courseId) {
    if (confirm('Are you sure you want to delete this course?')) {
        const formData = new FormData();
        formData.append('id', courseId);

        const response = await fetch('delete_course.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            location.reload(); // Refresh the page
        } else {
            alert(result.message);
        }
    }
}
</script>

<?php include('../includes/footer.php'); ?>
