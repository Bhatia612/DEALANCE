<?php

$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

$navLinks = [
  'admin' => [
    'Home' => '../pages/homepage.php',
    'Dashboard' => '../pages/admin_dashboard.php',
    'Manage Users' => '../manage_users.php',
    'Manage Applications' => '../pages/manage_application.php',
    'Logout' => '../pages/logout.php',
  ],
  'employer' => [
    'Home' => '../pages/homepage.php',
    'Dashboard' => '../pages/user_dashboard.php',
    'Post Job' => '../pages/post_job.php',
    'Logout' => '../pages/logout.php',
  ],
  'freelancer' => [
    'Home' => '../pages/homepage.php',
    'Dashboard' => '../pages/user_dashboard.php',
    'Find Jobs' => '../pages/browse_jobs.php',
    'Logout' => '../pages/logout.php',
  ]
];

$currentLinks = isset($navLinks[$userRole]) ? $navLinks[$userRole] : [];
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
<title>Header Component</title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Outfit', sans-serif;
    background: rgb(10, 10, 80);
    color: white;
  }

  .header {
    /* position: fixed; 
    border-radius: 0 0 16px 16px;
    z-index: 999; */
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    padding: 15px 40px;
    border-radius: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
  }

  .logo {
    font-size: 1.8rem;
    font-weight: bold;
    color: #00bcd4;
  }

  nav a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
    transition: color 0.3s ease;
  }

  nav a:hover {
    color: #00e5ff;
  }
</style>

<header class="header">
  <div class="logo">Dealance</div>
  <nav>
    <?php if (!empty($currentLinks)): ?>
      <?php foreach ($currentLinks as $linkText => $linkUrl): ?>
        <a href="<?= $linkUrl ?>"><?= $linkText ?></a>
      <?php endforeach; ?>
    <?php endif; ?>
  </nav>
</header>