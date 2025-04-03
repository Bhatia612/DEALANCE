<?php
session_start();

include "../database/db.php";

$employerId = $_SESSION['user_id'];

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $jobId = intval($_GET['delete']);
    $conn->query("DELETE FROM jobs WHERE job_id = $jobId AND employer_id = $employerId");
    header("Location: manage_posted_jobs.php");
    exit;
}

$jobs = [];
$query = "SELECT job_id, title, description, created_at FROM jobs WHERE employer_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employerId);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    $jobs = $result->fetch_all(MYSQLI_ASSOC);
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Posted Jobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0a0a40;
            color: white;
            padding: 100px 20px 40px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
        }
        html, body {
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .job-card {
            background-color: #1a1a60;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.4);
        }
        .job-card h3 {
            margin: 0 0 10px;
            font-size: 22px;
        }
        .job-card p {
            margin-bottom: 10px;
        }
        .job-card small {
            color: #ccc;
        }
        .delete-btn {
            background-color: #ff3b3b;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .delete-btn:hover {
            background-color: #e62828;
        }
    </style>
</head>
<body>
<?php include "../components/_nav.php"; ?>
<div class="container">
    <h2>Your Posted Jobs</h2>
    <?php if (count($jobs) > 0): ?>
        <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <h3><?= htmlspecialchars($job['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                <small>Posted on <?= htmlspecialchars($job['created_at']) ?></small><br><br>
                <form method="get" onsubmit="return confirm('Are you sure you want to delete this job?');">
                    <input type="hidden" name="delete" value="<?= $job['job_id'] ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">You haven't posted any jobs yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
