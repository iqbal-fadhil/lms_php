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
                <th class="d-none">Description</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($course = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $course['id'] ?></td>
                <td><?= $course['title'] ?></td>
                <td class="d-none">
                    <!-- Show only an excerpt of the description -->
                    <?= strlen($course['description']) > 100 ? substr($course['description'], 0, 100) . '...' : $course['description']; ?>
                </td>
                <td><?= $course['created_by'] ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="openEditCourseModal(<?= $course['id'] ?>, '<?= addslashes($course['title']) ?>', '<?= addslashes(str_replace(["\r", "\n"], ['\\r', '\\n'], $course['description'])) ?>')">Edit</button>
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
                        <!-- CKEditor text area -->
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
                        <label for="editCourseTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editCourseTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCourseDescription" class="form-label">Description</label>
                        <textarea id="editCourseDescription" name="description" required></textarea>
                    </div>                    
                    <button type="submit" class="btn btn-primary">Update Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include CKEditor -->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->

<script>
    // Initialize CKEditor on the description field
    CKEDITOR.replace('description');
    CKEDITOR.replace('editCourseDescription'); // Initialize CKEditor for editing

    function openEditCourseModal(id, title, description) {
        $('#editCourseId').val(id);
        $('#editCourseTitle').val(title);
        CKEDITOR.instances.editCourseDescription.setData(description); // Set the description in the editor
        $('#editCourseModal').modal('show'); // Show the modal
    }

    document.getElementById('createCourseForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Get the HTML content from CKEditor and assign it to the hidden field
        const descriptionHtml = CKEDITOR.instances.description.getData();
        const formData = new FormData(this);
        formData.set('description', descriptionHtml);

        // Submit form data with AJAX
        fetch('create_course.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(result => {
              if (result.success) {
                  alert(result.message);
                  location.reload(); // Reload the page to update course list
              } else {
                  alert(result.message);
              }
          }).catch(error => {
              console.error('Error:', error);
              alert('An error occurred while creating the course.');
          });
    });

    document.getElementById('editCourseForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Get the HTML content from CKEditor for the edit form
        const descriptionHtml = CKEDITOR.instances.editCourseDescription.getData();
        const formData = new FormData(this);
        formData.set('description', descriptionHtml);

        // Submit form data with AJAX
        fetch('edit_course.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(result => {
              if (result.success) {
                  alert(result.message);
                  location.reload(); // Reload the page to update course list
              } else {
                  alert(result.message);
              }
          }).catch(error => {
              console.error('Error:', error);
              alert('An error occurred while updating the course.');
          });
    });
</script>

<?php include('../includes/footer.php'); ?>
