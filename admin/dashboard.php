<?php
// admin/dashboard.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Verify that the admin is logged in; if not, redirect to the login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection from your config file
require_once '../includes/config.php';

// Example queries to fetch data for the dashboard
try {
    // Count total products
    $stmtProducts = $pdo->query("SELECT COUNT(*) AS count FROM products");
    $productData = $stmtProducts->fetch(PDO::FETCH_ASSOC);
    $productCount = $productData ? $productData['count'] : 0;

    // Count total orders
    $stmtOrders = $pdo->query("SELECT COUNT(*) AS count FROM orders");
    $orderData = $stmtOrders->fetch(PDO::FETCH_ASSOC);
    $orderCount = $orderData ? $orderData['count'] : 0;
} catch (PDOException $e) {
    // In case of an error, set counts to zero (you may want to handle this differently)
    $productCount = 0;
    $orderCount = 0;
}

// Include the HTML template for the dashboard
include '../admin/admin_dashboard_template.php';
?>
