<?php
include '../database/db.php';
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admins only.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_users'])) {
    
    $sql = "DELETE FROM users WHERE role != 'admin'";
    if ($conn->query($sql) === TRUE) {
        echo "<p>All non-admin users have been deleted from the users table.</p>";
    } else {
        echo "<p>Error deleting users: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin. You can remove all users except for the admin from the users table.</p>

    <form method="post" action="">
        <button type="submit" name="reset_users">Remove All Users Except Admin</button>
    </form>
</body>
</html>
