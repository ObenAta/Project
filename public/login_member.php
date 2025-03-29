<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "All fields are required.";
        header("Location: membership.php");
        exit;
    }

    // Look for the member in the database
    $stmt = $pdo->prepare("SELECT * FROM members WHERE username = ?");
    $stmt->execute([$username]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($member && password_verify($password, $member['password'])) {
        // Check if the account is enabled
        if (strtolower($member['status']) !== 'enabled') {
            $_SESSION['login_error'] = "Your account has been disabled. Please contact support.";
            header("Location: membership.php");
            exit;
        }

        // Insert login history record
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $stmt_history = $pdo->prepare("INSERT INTO login_history (member_id, event_type, ip_address) VALUES (?, 'login', ?)");
        $stmt_history->execute([$member['id'], $ip_address]);

        // Login successful: store member info in session
        $_SESSION['member_logged_in'] = true;
        $_SESSION['member_username'] = $member['username'];
        $_SESSION['member_id'] = $member['id'];
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid username or password.";
        header("Location: membership.php");
        exit;
    }
}
?>
