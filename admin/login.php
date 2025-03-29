<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/admin_login_form.php");
    exit;
}

require_once '../includes/config.php'; // $pdo is defined here

// Sanitize and retrieve POST variables
$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = "All fields are required.";
    header("Location: ../admin/admin_login_form.php");
    exit;
}

// Retrieve the admin from the database
$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $admin['username'];
    header("Location: dashboard.php");
    exit;
} else {
    $_SESSION['login_error'] = "Invalid credentials!";
    header("Location: ../admin/admin_login_form.php");
    exit;
}
?>

