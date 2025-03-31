<?php
session_start();
require_once '../includes/config.php'; // $pdo is defined here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if(empty($username) || empty($password)){
        $_SESSION['reg_error'] = "All fields are required.";
        header("Location: admin_register_form.php");
        exit;
    }
    
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    if($stmt->fetch(PDO::FETCH_ASSOC)){
        $_SESSION['reg_error'] = "Username already exists.";
        header("Location: admin_register_form.php");
        exit;
    }
    
    // Generate hashed password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new admin record
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    if($stmt->execute([$username, $hashedPassword])){
        // Instead of header redirect, output JavaScript alert and then redirect
        echo "<script>
                alert('Admin registered successfully.');
                window.location.href = 'admin_login_form.php';
              </script>";
        exit;
    } else {
        $_SESSION['reg_error'] = "Error registering admin. Please try again.";
        header("Location: admin_register_form.php");
        exit;
    }
} else {
    header("Location: admin_register_form.php");
    exit;
}
?>

