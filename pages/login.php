<?php
session_start();
include '../database/db.php';

$message = "";

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
      $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Successfully logged in to Dealance!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
      echo "<script>
              setTimeout(function() {
                window.location.href = 'homepage.php';
              }, 2000);
            </script>";
    } else {
      $message = "<div class='alert alert-danger' role='alert'>Invalid password.</div>";
    }
  } else {
    $message = "<div class='alert alert-danger' role='alert'>User not found.</div>";
  }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Job Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body,
    html {
      height: 100%;
      font-family: 'Outfit', sans-serif;
      color: white;
      overflow: hidden;
      background: rgb(1, 1, 70);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
      z-index: 1;
    }

    button {
      background: linear-gradient(135deg, #00e5ff, #008ba3);
      border: none;
      transition: background 0.3s ease;
      color: white;
      font-weight: bold;
    }

    button:hover {
      background: linear-gradient(135deg, #008ba3, #00e5ff);
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
  <?php echo $message; ?>
  <div class="glass-card">
    <h2 class="text-center mb-4">Welcome Back!</h2>
    <form method="post" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required />
      </div>
      <button type="submit" name="login" class="btn w-100">Login</button>
    </form>
    <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</body>

</html>