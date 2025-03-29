<?php
session_start();
require_once 'includes/config.php';

// Ensure the member is logged in
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true) {
    echo "<script>
            alert('Please log in to view your order history.');
            window.location.href = 'membership.php';
          </script>";
    exit;
}

// Get the logged-in member's numeric ID
$member_id = intval($_SESSION['member_id']);

// Query the orders for this member.
// Regular orders join with order_items (p1) and refurbished orders are recorded directly with a product_id (p2).
$stmt = $pdo->prepare("
    SELECT 
        o.id AS order_id, 
        o.created_at AS order_date, 
        o.status AS order_status, 
        o.delivery_days,
        COALESCE(p1.name, p2.name) AS product_name
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p1 ON oi.product_id = p1.id
    LEFT JOIN products p2 ON o.product_id = p2.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$member_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .order-history-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .product-name {
            font-weight: bold;
        }
        .status-pending {
            font-weight: bold;
        }
        /* Review button styling */
        .review-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .review-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="order-history-container">
            <h2 style="text-align: center;">Your Order History</h2>
            <?php if (count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Order Date &amp; Time</th>
                        <th>Order Status</th>
                        <th>Expected Delivery Date</th>
                        <th>Review</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): 
                        $order_date = $order['order_date'];
                        $delivery_days = $order['delivery_days'];
                        $expected_delivery = date("Y-m-d H:i:s", strtotime($order_date . " + $delivery_days day"));
                        // Determine order status display: Delivered if current time is past expected delivery, else Pending.
                        if (time() >= strtotime($expected_delivery)) {
                            $display_status = "Delivered";
                        } else {
                            $display_status = "<span class='status-pending'>Pending</span>";
                        }
                    ?>
                    <tr>
                        <td class="product-name"><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($order_date); ?></td>
                        <td><?php echo $display_status; ?></td>
                        <td><?php echo htmlspecialchars($expected_delivery); ?></td>
                        <td>
                            <a class="review-btn" href="reviews.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>">Leave Review</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center;">You have no orders yet.</p>
            <?php endif; ?>
        </div>
        <p style="text-align:center;">
           Need help using this page? 
           <a href="orderhelp.html">Click here for instructions.</a>
        </p>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
