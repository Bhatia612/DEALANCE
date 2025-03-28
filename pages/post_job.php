<?php

include '../database/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    echo $_SESSION['user_id'];
    die("Access denied. Only employers can post jobs.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post-job'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $employer_id = $_SESSION['user_id'];

    $sql = "INSERT INTO jobs (title, description, employer_id) VALUES ('$title', '$description', '$employer_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Job posted successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post Job - Job Portal</title>
</head>
<body>
    <h2>Post a Job</h2>
    <form method="post" action="post_job.php">
        Job Title: <input type="text" name="title" required><br>
        Description: <textarea name="description" required></textarea><br>
        <button type="submit" name="post-job">Post Job</button>
    </form>
</body>
</html>
