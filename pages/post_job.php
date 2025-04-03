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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
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

        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 100px);
        }

        .form-container {
            background-color: #1a1a60;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            width: 320px;
        }

        .form-container h2 {
            color: white;
            margin-bottom: 20px;
        }

        .form-container label {
            color: white;
            font-size: 14px;
            display: block;
            margin-top: 10px;
            text-align: left;
        }

        .form-container input[type="text"],
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 8px;
            background-color: white;
            color: black;
            font-size: 14px;
        }

        .form-container button {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(to right, #00f2fe, #4facfe);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .form-container button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<?php include "../components/_nav.php"; ?>

<div class="form-wrapper">
  <div class="form-container">
      <h2>Post a Job</h2>
      <form method="post" action="post_job.php">
        <label for="title">Job Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <button type="submit" name="post-job">Post Job</button>
      </form>
  </div>
</div>

</body>
</html>
