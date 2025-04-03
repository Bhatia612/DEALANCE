<?php

include '../database/db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    die("Access denied. You need to log in first.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, email, role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = $user['username'];
$email = $user['email'];
$role = $user['role'];

$application_count = 0;
$pending_count = 0;
$rejected_count = 0;

if ($role === 'freelancer') {
    $sql = "SELECT j.job_id, j.title, j.description, a.status 
            FROM jobs j 
            JOIN applications a ON j.job_id = a.job_id 
            WHERE a.freelancer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $application_count++;
        if ($row['status'] === 'pending') {
            $pending_count++;
        } elseif ($row['status'] === 'rejected') {
            $rejected_count++;
        }
    }
}

if ($role === 'employer') {
    $sql = "SELECT job_id, title, description, status 
            FROM jobs 
            WHERE employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $jobs_posted = $stmt->get_result();

    $applications = [];
    while ($job = $jobs_posted->fetch_assoc()) {
        $job_id = $job['job_id'];
        $sql = "SELECT u.username, a.status 
                FROM applications a 
                JOIN users u ON a.freelancer_id = u.user_id 
                WHERE a.job_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $job_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $applications[$job_id] = $result;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .dashboard-container {
            display: flex;
            gap: 20px;
            margin: 20px;
        }

        .sidebar {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 12px;
            width: 300px;
        }

        .job-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
    </style>
</head>

<body>
    <?php include "../components/_nav.php"; ?>

    <div class="dashboard-container">
        <div class="sidebar">
            <h4><?= $username ?></h4>
            <p>Email: <?= $email ?></p>
            <?php if ($role === 'freelancer'): ?>
                <p>Total Applications: <?= $application_count ?></p>
                <p>Pending: <?= $pending_count ?></p>
                <p>Rejected: <?= $rejected_count ?></p>
            <?php endif; ?>
        </div>

        <div class="content">
            <?php if ($role === 'freelancer'): ?>
                <h3>Jobs You've Applied For</h3>
                <?php
                if ($application_count > 0) {
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()) {
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
                if ($jobs_posted->num_rows > 0) {
                    $jobs_posted->data_seek(0);
                    while ($job = $jobs_posted->fetch_assoc()) {
                        echo "<div class='job-card'>
                            <h4>{$job['title']}</h4>
                            <p>{$job['description']}</p>
                            <p>Status: {$job['status']}</p>
                            <h5>Applicants:</h5>";
                        if (isset($applications[$job['job_id']])) {
                            while ($applicant = $applications[$job['job_id']]->fetch_assoc()) {
                                echo "<div class='job-card'>
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
        </div>
    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>