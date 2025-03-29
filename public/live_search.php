<?php
require_once 'includes/config.php';

if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
    if ($q !== "") {
        $stmt = $pdo->prepare("SELECT name FROM products WHERE name LIKE ? LIMIT 10");
        $stmt->execute(["%$q%"]);
        $suggestions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($suggestions);
        exit;
    }
}
echo json_encode([]);
?>
