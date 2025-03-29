<?php
session_start();
require_once 'includes/config.php';

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$productAdded = false;

// Handle adding a product via GET parameter "add"
if (isset($_GET['add'])) {
    $productId = intval($_GET['add']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        // If the product already exists in the cart, increment quantity
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } else {
            $product['quantity'] = 1;
            $_SESSION['cart'][$productId] = $product;
        }
        $productAdded = true;
    }
}

// Handle removal via GET parameter "remove"
if (isset($_GET['remove'])) {
    $removeId = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$removeId])) {
        unset($_SESSION['cart'][$removeId]);
    }
}

// Handle updating quantity via POST
if (isset($_POST['update'])) {
    $productId = intval($_POST['product_id']);
    $newQuantity = intval($_POST['quantity']);
    if ($newQuantity > 0 && isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Simple table styling for the cart */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        /* Styling for update form elements */
        .update-form input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-right: 5px;
        }
        .update-form button {
            padding: 5px 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        .update-form button:hover {
            background-color: #0056b3;
        }
        /* Styling for removal link */
        .remove-link {
            color: #ff0000;
            text-decoration: none;
        }
        .remove-link:hover {
            text-decoration: underline;
        }
        /* Styling for Buy button */
        .buy-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #28a745;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1em;
            margin-top: 20px;
        }
        .buy-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <h2>Your Cart</h2>
        <?php if ($productAdded): ?>
            <p style="text-align: center; color: green;">Product added to cart successfully!</p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['cart'])): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $productId => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <!-- Update quantity form -->
                                <form method="post" class="update-form">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" name="update" value="update">Update</button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <!-- Removal link -->
                                <a href="cart.php?remove=<?php echo $productId; ?>" class="remove-link">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Buy Button: Only display if cart is not empty -->
            <div style="text-align: center;">
                <a href="order_items.php" class="buy-button">Buy</a>
            </div>
        <?php else: ?>
            <p style="text-align: center;">Your cart is empty.</p>
        <?php endif; ?>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
