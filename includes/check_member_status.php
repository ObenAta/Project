<?php
// check_member_status.php
if (isset($_SESSION['member_id'])) {
    require_once 'includes/config.php';
    $stmt = $pdo->prepare("SELECT status FROM members WHERE id = ?");
    $stmt->execute([$_SESSION['member_id']]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    // If no member is found or status is not 'enabled', force logout
    if (!$member || strtolower($member['status']) !== 'enabled') {
        $_SESSION['login_error'] = "Your account has been disabled. Please contact support.";
        session_unset();
        session_destroy();
        header("Location: membership.php");
        exit;
    }
}
?>
