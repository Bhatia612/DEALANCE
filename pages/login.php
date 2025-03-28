<?php
include '../database/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];
            echo "Login successful. Welcome " . $row['role'];
            if ($row['role'] === 'employer') {
                echo '<br><a href="post_job.php">Post a Job</a>';
            } elseif ($row['role'] === 'freelancer') {
                echo '<br><a href="apply_job.php">View Available Jobs</a>';
            }
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Job Portal</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="login.php">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
