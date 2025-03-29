<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login_form.php");
    exit;
}

require_once '../includes/config.php'; // $pdo is defined here

// Check for a valid product ID in GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_edit_product_form.php");
    exit;
}

$productId = (int)$_GET['id'];

// Delete product from products table
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$productId]);

$stmt2 = $pdo->prepare("DELETE FROM stock WHERE product_id = ?");
$stmt2->execute([$productId]);

// Redirect back to the edit products page
header("Location: admin_edit_product_form.php");
exit;
?>
