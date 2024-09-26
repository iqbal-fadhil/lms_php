<?php
include('../includes/auth.php'); // Ensure only logged-in users can access
include('../includes/db.php');   // Database connection

// Fetch all users from the database
$result = $conn->query("SELECT id, username, role, created_at FROM users");
?>

<?php include('../includes/header.php'); ?>

<div class="container">
    <h2 class="my-4">User Management</h2>

    <!-- Create New User Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">Create New User</button>

    <!-- Display Users in a Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['role'] ?></td>
                <td><?= $user['created_at'] ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="openEditUserModal(<?= $user['id'] ?>, '<?= $user['username'] ?>', '<?= $user['role'] ?>')">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createUserForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Role</label>
                        <select class="form-select" id="editRole" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for handling async operations -->
<script>
document.getElementById('createUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    const response = await fetch('create_user.php', {
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

function openEditUserModal(userId, username, role) {
    document.getElementById('editUserId').value = userId;
    document.getElementById('editUsername').value = username;
    document.getElementById('editRole').value = role;
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}

document.getElementById('editUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    const response = await fetch('edit_user.php', {
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

async function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        const formData = new FormData();
        formData.append('id', userId);

        const response = await fetch('delete_user.php', {
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
