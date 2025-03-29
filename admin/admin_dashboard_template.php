<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - GPU Paradise Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <main>
        <h2>Welcome, Admin!</h2>
        <p>Total Products: <?php echo htmlspecialchars($productCount); ?></p>
        <p>Total Orders: <?php echo htmlspecialchars($orderCount); ?></p>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> GPU PARADISE by Oben</p>
    </footer>
</body>
</html>
