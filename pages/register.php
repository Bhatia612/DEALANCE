<?php
include '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Job Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Outfit', sans-serif;
      color: white;
      overflow: hidden;
      background: rgb(1, 1, 70);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    #background-canvas {
      position: absolute;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: 0;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
      animation: fadeIn 0.8s ease-in-out;
      z-index: 1;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    input, select, button {
      margin-bottom: 15px;
    }

    a {
      color: #00e5ff;
      text-decoration: none;
    }

    a:hover {
      color: #00bcd4;
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

    h2 {
      font-size: 2.2rem;
    }
  </style>
</head>
<body>
  <canvas id="background-canvas"></canvas>

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

  <script>
    const canvas = document.getElementById('background-canvas');
    const ctx = canvas.getContext('2d');

    function resizeCanvas() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    const stars = [];

    for (let i = 0; i < 100; i++) {
      stars.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        radius: Math.random() * 1.5,
        velocity: Math.random() * 0.5 + 0.2
      });
    }

    function animateStars() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.fillStyle = 'white';

      for (let star of stars) {
        ctx.beginPath();
        ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
        ctx.fill();

        star.y += star.velocity;
        if (star.y > canvas.height) {
          star.y = 0;
          star.x = Math.random() * canvas.width;
        }
      }

      requestAnimationFrame(animateStars);
    }

    animateStars();
  </script>
</body>
</html>
