<?php
session_start();
include "../database/db.php"; 

if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please <a href='login.php'>login</a> first.";
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
$role = $user['role'];
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
            <?php if ($role == 'admin') : ?>
                <p class="lead mb-4">Welcome, Admin. Manage users and applications efficiently to keep the platform running smoothly.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="manage_users.php" class="btn btn-warning btn-lg">Manage Users</a>
                    <a href="manage_application.php" class="btn btn-primary btn-lg">Manage Applications</a>
                </div>
            <?php elseif ($role == 'employer') : ?>
                <p class="lead mb-4">Welcome, Employer. Post jobs and connect with the best freelancers for your needs.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="post_job.php" class="btn btn-success btn-lg">Post Jobs</a>
                    <a href="profile.php" class="btn btn-info btn-lg">Go to Profile</a>
                </div>
            <?php else : ?>
                <p class="lead mb-4">Your journey to finding exciting job opportunities starts right here. Our platform is designed to connect freelancers with top employers, offering a seamless and efficient experience. Whether you're looking for a quick gig or a long-term career, our user-friendly interface will guide you every step of the way.</p>
                <p class="text mb-4">You're now part of a community that's dedicated to making the job search process easy and effective. Explore the wide variety of opportunities tailored to your skills, and start making the connections that will help you thrive.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="find_job.php" class="btn btn-success btn-lg">Find Jobs</a>
                    <a href="profile.php" class="btn btn-info btn-lg">Go to Profile</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
