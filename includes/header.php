<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">LMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user_management.php">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="course_management.php">Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="task_management.php">Tasks</a>
                </li>
                                    <!-- Show Logout Button if Logged In -->
                                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-danger" href="login.php">Login</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
