<?php

include '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Job Portal</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="register.php">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Email: <input type="email" name="email" required><br>
        Role: 
        <select name="role" required>
            <option value="freelancer">Freelancer</option>
            <option value="employer">Employer</option>
        </select><br>
        <button type="submit" name="register">Register</button>
    </form>
</body>
</html>
