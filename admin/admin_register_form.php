<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .registration-container {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Roboto Condensed', serif;
            color: #333;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            width: 100%;
            padding: 10px;
            background-color: #007acc;
            border: none;
            color: #fff;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }
        form button:hover {
            background-color: #005f99;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
        .success {
            color: green;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h1>Admin Registration</h1>
        <?php
        if(isset($_SESSION['reg_error'])) {
            echo '<div class="error">'.htmlspecialchars($_SESSION['reg_error']).'</div>';
            unset($_SESSION['reg_error']);
        }
        if(isset($_SESSION['reg_success'])) {
            echo '<div class="success">'.htmlspecialchars($_SESSION['reg_success']).'</div>';
            unset($_SESSION['reg_success']);
        }
        ?>
        <form action="admin_register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Register Admin</button>
        </form>
    </div>
</body>
</html>
