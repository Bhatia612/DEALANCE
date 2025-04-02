<?php
include '../database/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    die("Access denied. Only freelancers can view and apply for jobs.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_job'])) {
    $job_id = $_POST['job_id'];
    $freelancer_id = $_SESSION['user_id'];

    $check_sql = "SELECT * FROM applications WHERE job_id = $job_id AND freelancer_id = $freelancer_id";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<p>You have already applied for this job.</p>";
    } else {
        
        $insert_sql = "INSERT INTO applications (job_id, freelancer_id, status) VALUES ($job_id, $freelancer_id, 'pending')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "<p>Application submitted successfully!</p>";
        } else {
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
        }
    }
}

$sql = "SELECT job_id, title, description FROM jobs WHERE status='open'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Jobs - Job Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: rgb(1, 1, 70);
            color: white;
            padding-top: 100px;
            height: 100vh;
        }
        html, body {
            margin: 0;
            padding: 0;
        }
        .job-card {
            border: 0px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            background-color:rgb(6, 1, 102);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
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
    <h2>Available Jobs</h2>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='job-card'>
                    <h3>{$row['title']}</h3>
                    <p>{$row['description']}</p>
                    <form method='post' action=''>
                        <input type='hidden' name='job_id' value='{$row['job_id']}'>
                        <button type='submit' name='apply_job'>Apply</button>
                    </form>
                  </div>";
        }
    } else {
        echo "<p>No jobs available</p>";
    }
    ?>
</body>
</html>

<?php
mysqli_close($conn);
?>
