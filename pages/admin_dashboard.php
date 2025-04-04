<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

include "../database/db.php";

$userCount = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;
$jobCount = $conn->query("SELECT COUNT(*) as total FROM jobs")->fetch_assoc()['total'] ?? 0;
$applicationCount = $conn->query("SELECT COUNT(*) as total FROM applications")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manager Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background-color: #0a0a40;
      color: white;
      margin: 0;
      padding-top: 100px;
    }
    html, 
    body {
        margin: 0;
        padding: 0;
    }
    .container {
      max-width: 1100px;
      margin: auto;
      padding: 20px;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
    }
    .card {
      background: linear-gradient(to bottom, #121253, #1e1e78);
      padding: 20px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
      transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
    }
    .card h2 {
      font-size: 50px;
      margin-bottom: 10px;
      color: #00f2fe;
    }
    .card p {
      font-size: 18px;
      color: #d1d1ff;
    }
    a.button {
      display: inline-block;
      margin-top: 15px;
      padding: 12px 24px;
      background: linear-gradient(to right, #00f2fe, #4facfe);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
    }
    a.button:hover {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      transform: scale(1.05);
    }
  </style>
</head>
<body>
  <?php include "../components/_nav.php"; ?>

  <div class="container">
    <h1>Manager Dashboard</h1>
    <div class="grid">
      <div class="card">
        <h2><?= $userCount ?></h2>
        <p>Registered Users</p>
        <a href="manage_users.php" class="button">Manage Users</a>
      </div>
      <div class="card">
        <h2><?= $jobCount ?></h2>
        <p>Total Jobs</p>
        <a href="manage_posted_jobs.php" class="button">Manage Jobs</a>
      </div>
      <div class="card">
        <h2><?= $applicationCount ?></h2>
        <p>Applications</p>
        <a href="manage_application.php" class="button">Manage Applications</a>
      </div>
    </div>
  </div>

</body>
</html>
