<?php
include '../database/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    die("Access denied. Only freelancers can view and apply for jobs.");
}

$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$username = $user['username'];
$email = $user['email'];

$sql = "SELECT j.job_id, j.title, j.description, j.created_at, u.username AS employer_name, u.email AS employer_email 
        FROM jobs j 
        JOIN users u ON j.employer_id = u.user_id 
        WHERE j.status='open'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Find Jobs - Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: rgb(1, 1, 70);
            color: white;
        }
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
        }
        .job-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
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
    <?php include "../components/_nav.php"; ?>
    <div class="dashboard-container">
        <div class="sidebar">
            <h4><?= $username ?></h4>
            <p>Email: <?= $email ?></p>
        </div>
        <div class="content">
            <h3>Available Jobs</h3>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class='job-card'>
                        <h3><?= $row['title'] ?></h3>
                        <p> - <?= $row['description'] ?></p>
                        <p><strong>Employer:</strong> <?= $row['employer_name'] ?> (<?= $row['employer_email'] ?>)</p>
                        <p><strong>Posted on:</strong> <?= $row['created_at'] ?></p>
                        <form method='post'>
                            <input type='hidden' name='job_id' value='<?= $row['job_id'] ?>'>
                            <button class = "rounded" type='submit' name='apply_job'>Apply</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No jobs available</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>