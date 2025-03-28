<?php

include '../database/db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    die("Access denied. You need to log in first.");
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];


if ($role === 'freelancer') {
    // Fetch jobs applied by freelancer
    $sql = "SELECT j.job_id, j.title, j.description, a.status 
            FROM jobs j 
            JOIN applications a ON j.job_id = a.job_id 
            WHERE a.freelancer_id = $user_id";
    $result = mysqli_query($conn, $sql);
}


if ($role === 'employer') {
    // Fetch jobs posted by employer
    $sql = "SELECT job_id, title, description, status 
            FROM jobs 
            WHERE employer_id = $user_id";
    $jobs_posted = mysqli_query($conn, $sql);


    $applications = [];
    while ($job = mysqli_fetch_assoc($jobs_posted)) {
        $job_id = $job['job_id'];
        $sql = "SELECT u.username, a.status 
                FROM applications a 
                JOIN users u ON a.freelancer_id = u.user_id 
                WHERE a.job_id = $job_id";
        $result = mysqli_query($conn, $sql);
        $applications[$job_id] = $result;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Job Portal</title>
    <style>
        .job-card, .application-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            background-color: #f9f9f9;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Dashboard</h2>

<?php if ($role === 'freelancer'): ?>
    <h3>Jobs You've Applied For</h3>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='job-card'>
                    <h4>{$row['title']}</h4>
                    <p>{$row['description']}</p>
                    <p>Status: {$row['status']}</p>
                  </div>";
        }
    } else {
        echo "<p>You have not applied for any jobs yet.</p>";
    }
    ?>
<?php endif; ?>

<?php if ($role === 'employer'): ?>
    <h3>Your Job Postings</h3>
    <?php
    if (mysqli_num_rows($jobs_posted) > 0) {
        while ($job = mysqli_fetch_assoc($jobs_posted)) {
            echo "<div class='job-card'>
                    <h4>{$job['title']}</h4>
                    <p>{$job['description']}</p>
                    <p>Status: {$job['status']}</p>
                    <h5>Applicants:</h5>";

            if (isset($applications[$job['job_id']])) {
                while ($applicant = mysqli_fetch_assoc($applications[$job['job_id']])) {
                    echo "<div class='application-card'>
                            <p>Username: {$applicant['username']}</p>
                            <p>Status: {$applicant['status']}</p>
                          </div>";
                }
            } else {
                echo "<p>No applicants yet.</p>";
            }

            echo "</div>";
        }
    } else {
        echo "<p>You have not posted any jobs yet.</p>";
    }
    ?>
<?php endif; ?>

</body>
</html>

<?php
mysqli_close($conn);
?>
