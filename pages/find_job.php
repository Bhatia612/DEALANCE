<?php
include '../database/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    die("Access denied. Only freelancers can view and apply for jobs.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, email FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
$username = $user['username'];
$email = $user['email'];

$sql = "SELECT j.job_id, j.title, j.description, j.created_at, u.username AS employer_name, u.email AS employer_email 
        FROM jobs j 
        JOIN users u ON j.employer_id = u.user_id 
        WHERE j.status='open'";
$result = mysqli_query($conn, $sql);

$application_message = '';

if (isset($_POST['apply_job'])) {
    $job_id = $_POST['job_id'];

    $check_query = "SELECT * FROM applications WHERE job_id='$job_id' AND freelancer_id='$user_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $application_message = "You have already applied for this job.";
    } else {
        $cover_letter = "I am interested in this job and would like to apply.";
        $apply_query = "INSERT INTO applications (job_id, freelancer_id, cover_letter, status) 
                        VALUES ('$job_id', '$user_id', '$cover_letter', 'pending')";

        if (mysqli_query($conn, $apply_query)) {
            $application_message = "Application sent successfully to the employer!";
        } else {
            $application_message = "Error applying for job.";
        }
    }
}
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
            display: inline-block;
            margin-left: 10px;            
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

        div.alert {
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
        }
    </style>
</head>

<body>
    <?php include "../components/_nav.php"; ?>

    <?php if ($application_message): ?>
        <div class="container mt-3">
            <div class="alert alert-info" role="alert" id="applicationAlert">
                <?= $application_message ?>
            </div>
        </div>
        <script>
            setTimeout(function () {
                var alertElement = document.getElementById('applicationAlert');
                alertElement.style.display = 'none';
            }, 2000);
        </script>
    <?php endif; ?>

    <div class="dashboard-container">
        <div class="sidebar">
            <h4><?= $username ?></h4>
            <p>Email: <?= $email ?></p>
        </div>
        <div class="content">
            <h3>Available Jobs</h3>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class='job-card'>
                        <h4><?= $row['title'] ?></h4>
                        <p><?= $row['description'] ?></p>
                        <p><strong>Employer:</strong> <?= $row['employer_name'] ?> (<?= $row['employer_email'] ?>)</p>
                        <p><strong>Posted on:</strong> <?= $row['created_at'] ?></p>
                        <form method='post'>
                            <input type='hidden' name='job_id' value='<?= $row['job_id'] ?>'>
                            <button class="rounded" type='submit' name='apply_job'>Apply</button>
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

<?php mysqli_close($conn); ?>