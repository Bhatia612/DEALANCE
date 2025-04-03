<?php
session_start();

$user = [
    'user_id' => $_SESSION['user_id'] ?? '',
    'username' => $_SESSION['username'] ?? '',
    'email' => $_SESSION['email'] ?? '',
    'role' => $_SESSION['role'] ?? '',
];

$applicationMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_admin'])) {
    $reason = trim($_POST['reason']);
    if (!empty($reason)) {
        $line = $user['user_id'] . '|' . $user['username'] . '|' . $user['email'] . '|' . str_replace('|', '-', $reason) . '|pending' . PHP_EOL;
        file_put_contents(__DIR__ . '/../admin_requests.txt', $line, FILE_APPEND);
        $applicationMessage = 'Application submitted!';
    } else {
        $applicationMessage = 'Reason cannot be empty.';
    }
}


if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please <a href='login.php'>login</a> first.";
    exit;
}

include "../database/db.php";

$userId = $_SESSION['user_id'];
$result = $conn->query("SELECT username, email, role, created_at FROM users WHERE user_id = $userId");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile - Job Portal</title>
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
        }
        html, body {
            margin: 0;
            padding: 0;
        }

        .profile-container {
            max-width: 500px;
            margin: auto;
            background-color: #1a1a60;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.4);
            text-align: center;
        }

        .profile-container h2 {
            margin-bottom: 20px;
        }

        .profile-info {
            text-align: left;
            margin-bottom: 20px;
        }

        .profile-info p {
            margin: 8px 0;
        }

        .button-group button {
            background: linear-gradient(to right, #00f2fe, #4facfe);
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px 5px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .button-group button:hover {
            opacity: 0.9;
        }

        textarea {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            border: none;
            resize: vertical;
        }
    </style>
</head>
<body>

<?php include "../components/_nav.php"; ?>

<div class="profile-container">
    <h2>My Profile</h2>

    <div class="profile-info">
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
        <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
    </div>

    <div class="button-group">
        <button onclick="location.href='change_password.php'">Change Password</button>
    </div>

    <?php if ($user['role'] !== 'admin'): ?>
    <form method="post">
        <h3 style="margin-top: 20px;">Apply for Admin</h3>
        <textarea name="reason" rows="4" placeholder="Why do you want to become an admin?" required></textarea>
        <button type="submit" name="apply_admin">Submit Application</button>
    </form>
<?php endif; ?>
<?php if (!empty($applicationMessage)): ?>
    <p style="margin-top:10px; color:lightgreen;"><?= $applicationMessage ?></p>
<?php endif; ?>


</div>

</body>
</html>
