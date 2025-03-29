<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      font-family: 'Roboto Condensed', serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .login-container {
      max-width: 400px;
      margin: 80px auto;
      background: #fff;
      padding: 30px;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
      font-family: 'Roboto Condensed', serif;
    }
    .login-container form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .login-container form label {
      font-weight: bold;
      color: #333;
    }
    .login-container form input[type="text"],
    .login-container form input[type="password"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1rem;
    }
    .login-container form button {
      padding: 10px;
      background-color: #007bff;
      border: none;
      color: #fff;
      border-radius: 4px;
      font-size: 1rem;
      cursor: pointer;
    }
    .login-container form button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <?php
  if (isset($_SESSION['login_error'])) {
      echo '<script>alert("' . $_SESSION['login_error'] . '");</script>';
      unset($_SESSION['login_error']);
  }
  ?>
  <div class="login-container">
    <h2>Admin Login</h2>
    <form action="../admin/login.php" method="post">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" required>
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>

