<?php
include '../database/db.php';

$message = "";
$alertType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $role = $_POST['role'];

  $sql = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";

  if ($conn->query($sql) === TRUE) {
    $message = "Registrated successfuly with DEALANCE! Redirecting to login page...";
    $alertType = "success";
    echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 2000);</script>";
  } else {
    $message = "Error: " . $conn->error;
    $alertType = "danger";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Job Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: rgb(1, 1, 70);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px;
      max-width: 450px;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
    }

    button {
      background: linear-gradient(135deg, #00e5ff, #008ba3);
      border: none;
      color: white;
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
  <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $alertType; ?>" role="alert">
      <?php echo $message; ?>
    </div>
  <?php endif; ?>
  <div class="glass-card">
    <h2 class="text-center mb-4">Create Your Account</h2>


    <form method="post" action="register.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" required />
      </div>
      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" id="role" name="role" required>
          <option value="freelancer">Freelancer</option>
          <option value="employer">Employer</option>
        </select>
      </div>
      <button type="submit" name="register" class="btn w-100">Register</button>
    </form>
    <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>

</html>