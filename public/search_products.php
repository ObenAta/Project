<?php
require_once 'includes/config.php';

if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $stmt->execute(["%$q%", "%$q%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
    exit;
}
echo json_encode([]);
?>
