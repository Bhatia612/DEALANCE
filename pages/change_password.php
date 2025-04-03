<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please <a href='login.php'>login</a> first.";
    exit;
}

include "../database/db.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current, $hashedPassword)) {
        $message = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $message = "New passwords do not match.";
    } else {
        $newHashed = password_hash($new, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $updateStmt->bind_param("si", $newHashed, $userId);
        if ($updateStmt->execute()) {
            $message = "Password updated successfully.";
        } else {
            $message = "Error updating password.";
        }
        $updateStmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0a0a40;
            color: white;
            padding: 100px 20px 40px;
        }
        html, body {
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: #1a1a60;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.4);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 12px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background: linear-gradient(to right, #00f2fe, #4facfe);
            border: none;
            border-radius: 8px;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            color: #00e5ff;
        }
    </style>
</head>
<body>

<?php include "../components/_nav.php"; ?>

<div class="container">
    <h2>Change Password</h2>
    <form method="post">
        <label for="current_password">Current Password</label>
        <input type="password" name="current_password" id="current_password" required>

        <label for="new_password">New Password</label>
        <input type="password" name="new_password" id="new_password" required>

        <label for="confirm_password">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <button type="submit">Update Password</button>
    </form>
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</div>

</body>
</html>