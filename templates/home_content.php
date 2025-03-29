<?php
require_once 'includes/config.php';

// Determine sort order from GET parameter
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

$orderClause = "ORDER BY id DESC"; // default order
if ($sort == "alphabetical") {
    $orderClause = "ORDER BY name ASC";
} elseif ($sort == "price_asc") {
    $orderClause = "ORDER BY price ASC";
} elseif ($sort == "price_desc") {
    $orderClause = "ORDER BY price DESC";
}

$stmt = $pdo->prepare("SELECT * FROM products " . $orderClause);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h2>Products</h2>
    <div class="product-list">
        <?php foreach($products as $product): ?>
            <div class="product-item">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p>$<?php echo htmlspecialchars($product['price']); ?></p>
                <a href="product_details.php?id=<?php echo $product['id']; ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

