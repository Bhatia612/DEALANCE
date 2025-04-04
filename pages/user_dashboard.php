<?php
include '../database/db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    die("Access denied. You need to log in first.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, email, role FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$username = $user['username'];
$email = $user['email'];
$role = $user['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['withdraw'])) {
        $job_id = intval($_POST['job_id']);
        $delete_sql = "DELETE FROM applications WHERE job_id = $job_id AND freelancer_id = $user_id";
        mysqli_query($conn, $delete_sql);
        header("Location: user_dashboard.php");
        exit();
    }

    if (isset($_POST['close_job'])) {
        $job_id = intval($_POST['job_id']);
        $delete_applications_sql = "DELETE FROM applications WHERE job_id = $job_id";
        mysqli_query($conn, $delete_applications_sql);

        $delete_job_sql = "DELETE FROM jobs WHERE job_id = $job_id AND employer_id = $user_id";
        mysqli_query($conn, $delete_job_sql);

        header("Location: user_dashboard.php");
        exit();
    }
}

if ($role === 'freelancer') {
    $sql = "SELECT j.job_id, j.title, j.description, a.status, u.username AS employer_name, u.email AS employer_email
            FROM jobs j 
            JOIN applications a ON j.job_id = a.job_id 
            JOIN users u ON j.employer_id = u.user_id
            WHERE a.freelancer_id = $user_id";
    $applications = mysqli_query($conn, $sql);

    $status_counts = ['pending' => 0, 'accepted' => 0, 'rejected' => 0];
    while ($row = mysqli_fetch_assoc($applications)) {
        if ($row['status'] == 'pending') {
            $status_counts['pending']++;
        } elseif ($row['status'] == 'accepted') {
            $status_counts['accepted']++;
        } elseif ($row['status'] == 'rejected') {
            $status_counts['rejected']++;
        }
    }

    mysqli_data_seek($applications, 0);
}

if ($role === 'employer') {
    $sql = "SELECT j.job_id, j.title, j.description, j.status, 
                   u.username AS freelancer_name, u.email AS freelancer_email
            FROM jobs j
            LEFT JOIN applications a ON j.job_id = a.job_id
            LEFT JOIN users u ON a.freelancer_id = u.user_id
            WHERE j.employer_id = $user_id
            ORDER BY j.job_id";
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
            display: block;
            margin-left: 10px;
        }

        .freelancer-list {
            margin-top: 10px;
            padding: 10px;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 6px;
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
                <h5>Job Application Status</h5>
                <ul>
                    <li>Applied: <?= $status_counts['pending'] ?></li>
                    <li>Accepted: <?= $status_counts['accepted'] ?></li>
                    <li>Rejected: <?= $status_counts['rejected'] ?></li>
                </ul>
            <?php endif; ?>
        </div>

        <div class="content">
            <?php if ($role === 'freelancer'): ?>
                <h3>Jobs You've Applied For</h3>
                <?php if (mysqli_num_rows($applications) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($applications)): ?>
                        <div class="job-card">
                            <h4><?= $row['title'] ?></h4>
                            <p><?= $row['description'] ?></p>
                            <p><strong>Employer:</strong> <?= $row['employer_name'] ?> (Email: <?= $row['employer_email'] ?>)</p>
                            <p><strong>Status:</strong> <?= $row['status'] ?></p>
                            <form method="POST">
                                <input type="hidden" name="job_id" value="<?= $row['job_id'] ?>" />
                                <button type="submit" name="withdraw" class="btn btn-danger btn-sm">Withdraw Application</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>You have not applied for any jobs yet.</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($role === 'employer'): ?>
                <h3>Your Job Postings</h3>
                <?php if (mysqli_num_rows($jobs_posted) > 0): ?>
                    <?php 
                    $jobs = [];
                    while ($row = mysqli_fetch_assoc($jobs_posted)) {
                        $job_id = $row['job_id'];
                        if (!isset($jobs[$job_id])) {
                            $jobs[$job_id] = [
                                'title' => $row['title'],
                                'description' => $row['description'],
                                'status' => $row['status'],
                                'freelancers' => []
                            ];
                        }
                        if ($row['freelancer_name']) {
                            $jobs[$job_id]['freelancers'][] = [
                                'name' => $row['freelancer_name'],
                                'email' => $row['freelancer_email']
                            ];
                        }
                    }
                    ?>

                    <?php foreach ($jobs as $job_id => $job): ?>
                        <div class="job-card">
                            <h4><?= $job['title'] ?></h4>
                            <p><?= $job['description'] ?></p>
                            <p><strong>Status:</strong> <?= $job['status'] ?></p>
                            <form method="POST">
                                <input type="hidden" name="job_id" value="<?= $job_id ?>" />
                                <button type="submit" name="close_job" class="btn btn-danger btn-sm">Close Job Posting</button>
                            </form>
                            
                            <?php if (!empty($job['freelancers'])): ?>
                                <div class="freelancer-list">
                                    <h5>Freelancers Applied:</h5>
                                    <ul>
                                        <?php foreach ($job['freelancers'] as $freelancer): ?>
                                            <li><strong><?= $freelancer['name'] ?></strong> - <?= $freelancer['email'] ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <p>No freelancers have applied yet.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>You have not posted any jobs yet.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
