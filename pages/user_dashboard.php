<?php
include '../database/db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    die("Access denied. You need to log in first.");
}

$user_id = $_SESSION['user_id'];

// Get user information
$query = "SELECT username, email, role FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$username = $user['username'];
$email = $user['email'];
$role = $user['role'];

$application_count = 0;
$pending_count = 0;
$rejected_count = 0;

// For Freelancer role
if ($role === 'freelancer') {
    $sql = "SELECT j.job_id, j.title, j.description, a.status, u.username AS employer_name
            FROM jobs j 
            JOIN applications a ON j.job_id = a.job_id 
            JOIN users u ON j.employer_id = u.user_id
            WHERE a.freelancer_id = $user_id";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $application_count++;
        if ($row['status'] === 'pending') {
            $pending_count++;
        } elseif ($row['status'] === 'rejected') {
            $rejected_count++;
        }
    }
}

// For Employer role
if ($role === 'employer') {
    $sql = "SELECT job_id, title, description, status FROM jobs WHERE employer_id = $user_id";
    $jobs_posted = mysqli_query($conn, $sql);
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
            display: inline-block;
            margin-left: 10px;
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
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='job-card'>
                            <h4>{$row['title']}</h4>
                            <p>{$row['description']}</p>
                            <p><strong>Employer:</strong> {$row['employer_name']}</p>
                            <p><strong>Status:</strong> {$row['status']}</p>
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
                        </div>";
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
mysqli_close($conn);
?>
