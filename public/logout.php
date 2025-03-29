<?php
session_start();
// If member is logged in, record a logout event
if (isset($_SESSION['member_id'])) {
    require_once 'includes/config.php';
    $memberId = $_SESSION['member_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $stmt_history = $pdo->prepare("INSERT INTO login_history (member_id, event_type, ip_address) VALUES (?, 'logout', ?)");
    $stmt_history->execute([$memberId, $ip_address]);
}

session_unset();
session_destroy();
header("Location: membership.php");
exit;
?>
