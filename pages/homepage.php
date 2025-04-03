<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please <a href='login.php'>login</a> first.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <title>Homepage</title>
</head>

<body>
    <?php include "../components/_nav.php"; ?>

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="text-center">
            <h1 class="mb-4">Welcome to Your Dashboard</h1>
            <div class="d-flex justify-content-center gap-3">
                <a href="./dashboard.php" class="btn btn-primary btn-lg">See Dashboard</a>
                <a href="./find_job.php" class="btn btn-success btn-lg">Find Jobs</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>