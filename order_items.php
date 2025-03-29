<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/check_member_status.php';

// Prompt for shipping location if not set in GET parameter
if (!isset($_GET['ontario'])) {
    echo "<script>
            var isOntario = confirm('Are you in Ontario, Canada? Click OK for Yes, Cancel for No.');
            if (isOntario) {
                window.location.href = 'order_items.php?ontario=yes';
            } else {
                window.location.href = 'order_items.php?ontario=no';
            }
          </script>";
    exit;
}

$delivery_days = ($_GET['ontario'] === 'yes') ? 1 : 3;

// Ensure only logged-in members can place orders
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true || !isset($_SESSION['member_id'])) {
    echo "<script>
            alert('You must log in to place an order.');
            window.location.href='membership.php';
          </script>";
    exit;
}

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty. <a href='index.php'>Continue Shopping</a>";
    exit;
}

// Calculate total cost
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Get the member's numeric ID
$user_id = intval($_SESSION['member_id']);
$status = "pending";

// Insert new order into the orders table (including delivery_days)
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, delivery_days) VALUES (?, ?, ?, ?)");
if (!$stmt->execute([$user_id, $total, $status, $delivery_days])) {
    die("Error inserting order.");
}

// Get the newly inserted order ID
$order_id = $pdo->lastInsertId();
if (!$order_id) {
    die("Error retrieving order ID.");
}

// Insert each item from the cart into the order_items table
foreach ($_SESSION['cart'] as $product_id => $item) {
    $quantity = $item['quantity'];
    $price = $item['price']; // unit price
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    if (!$stmt->execute([$order_id, $product_id, $quantity, $price])) {
        die("Error inserting order item for product ID: $product_id");
    }
}

// Clear the cart
unset($_SESSION['cart']);

// Set order date (assume current time as order placement time)
$order_date = date("Y-m-d H:i:s");
$expected_delivery = date("Y-m-d H:i:s", strtotime($order_date . " + $delivery_days day"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        main {
            padding-bottom: 100px;
        }
        .order-confirmation {
            max-width: 600px;
            margin: 40px auto 100px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .order-confirmation a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .order-confirmation a:hover {
            background-color: #0056b3;
        }
        /* Review button styling */
        .review-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .review-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="order-confirmation">
            <h2>Thank You for Your Order!</h2>
            <p>Your order has been placed successfully.</p>
            <p>Order ID: <?php echo htmlspecialchars($order_id); ?></p>
            <p>Total: $<?php echo number_format($total, 2); ?></p>
            <p>Expected Delivery: <?php echo htmlspecialchars($expected_delivery); ?></p>
            <a href="index.php">Continue Shopping</a>
            <br>
            <a href="reviews.php" class="review-button">Leave a Review</a>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
