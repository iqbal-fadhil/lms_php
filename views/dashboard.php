<?php include('../includes/auth.php'); ?>
<?php include('../includes/header.php'); ?>

<div class="container">
    <h2 class="my-4">Dashboard</h2>
    <p>Welcome, <?= $_SESSION['username']; ?>!</p>
</div>

<?php include('../includes/footer.php'); ?>
