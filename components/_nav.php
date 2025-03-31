
<?php
session_start();
$username = $_SESSION['role'] ?? 'Guest';
?>

<style>
  .navbar {
  position: fixed;       
  top: 0;
  left: 0;
  width: 100%;    
  background-color: #1e1e2f;
  padding: 15px 30px;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-family: 'Segoe UI', sans-serif;
  z-index: 1000;     
}


  .navbar a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: 500;
  }

  .navbar a:hover {
    text-decoration: underline;
  }

  .navbar .left {
    font-size: 20px;
    font-weight: bold;
  }

  .navbar .dropdown {
    position: relative;
    display: inline-block;
    cursor: pointer;
  }

  .navbar .dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #2a2a3f;
    min-width: 160px;
    z-index: 1;
    border-radius: 6px;
    overflow: hidden;
  }

  .navbar .dropdown-content a {
    display: block;
    padding: 12px 16px;
    text-decoration: none;
    color: white;
    font-weight: normal;
  }

  .navbar .dropdown-content a:hover {
    background-color: #383851;
  }

  .navbar .dropdown:hover .dropdown-content {
    display: block;
  }
</style>

<div class="navbar">
  <div class="left">DEALANCE</div>
  <div class="dropdown">
    <span><?php echo ucfirst($username); ?> ▼</span>
    <div class="dropdown-content">
      <a href="_job-card.php">Profile</a>
      <a href="pages/login.php">Logout</a>
    </div>
  </div>
</div>
<!-- <?php include 'components/_nav.php'; ?> -->