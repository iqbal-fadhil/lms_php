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
                    <th>Description</th>
                    <th>Image</th>
                    <th>Video</th>
                    <th>Actions</th>
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
                <?php if ($is_admin): ?>
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
                <td>
                    <button class="btn btn-primary btn-sm" onclick="openEditCourseModal(<?= $course['id'] ?>, '<?= $course['title'] ?>', '<?= $course['description'] ?>', '<?= $course['metadata'] ?>')">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCourse(<?= $course['id'] ?>)">Delete</button>
                </td>
                <?php endif; ?>
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
        <h5 class="modal-title" id="createCourseModalLabel">Create New Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createCourseForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="title" class="form-label">Course Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Course Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
            <small>Use HTML tags for formatting (e.g., &lt;h1&gt;, &lt;p&gt;)</small>
          </div>
          <div class="mb-3">
            <label for="metadata" class="form-label">Course Metadata</label>
            <input type="text" class="form-control" id="metadata" name="metadata">
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Course Image</label>
            <input type="file" class="form-control" id="image" name="image">
          </div>
          <div class="mb-3">
            <label for="video" class="form-label">Course Video</label>
            <input type="file" class="form-control" id="video" name="video">
          </div>
          <button type="submit" class="btn btn-primary">Create Course</button>
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
        <form id="editCourseForm" enctype="multipart/form-data">
          <input type="hidden" id="editCourseId" name="id">
          <div class="mb-3">
            <label for="editTitle" class="form-label">Course Title</label>
            <input type="text" class="form-control" id="editTitle" name="title" required>
          </div>
          <div class="mb-3">
            <label for="editDescription" class="form-label">Course Description</label>
            <textarea class="form-control" id="editDescription" name="description" required></textarea>
          </div>
          <div class="mb-3">
            <label for="editMetadata" class="form-label">Course Metadata</label>
            <input type="text" class="form-control" id="editMetadata" name="metadata">
          </div>
          <div class="mb-3">
            <label for="editImage" class="form-label">Course Image</label>
            <input type="file" class="form-control" id="editImage" name="image">
          </div>
          <div class="mb-3">
            <label for="editVideo" class="form-label">Course Video</label>
            <input type="file" class="form-control" id="editVideo" name="video">
          </div>
          <button type="submit" class="btn btn-primary">Update Course</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript to handle Create, Edit, and Delete -->
<script>
// Handle Create Course Form Submission
document.getElementById('createCourseForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch('create_course.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();  // Reload the page to show the new course
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

// Handle Edit Course Modal Opening
function openEditCourseModal(id, title, description, metadata) {
    document.getElementById('editCourseId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDescription').value = description;
    document.getElementById('editMetadata').value = metadata;
    new bootstrap.Modal(document.getElementById('editCourseModal')).show();
}

// Handle Edit Course Form Submission
document.getElementById('editCourseForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch('edit_course.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();  // Reload the page to show updated course
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

// Handle Delete Course
function deleteCourse(id) {
    if (confirm('Are you sure you want to delete this course?')) {
        fetch('delete_course.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();  // Reload the page to show changes
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>

<?php include('../includes/footer.php'); ?>
