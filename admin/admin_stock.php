<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once '../includes/config.php';

$update_message = "";
// Process form submission if POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['stock']) && is_array($_POST['stock'])) {
    foreach ($_POST['stock'] as $product_id => $quantity) {
        $product_id = intval($product_id);
        $quantity = intval($quantity);
        $stmt = $pdo->prepare("INSERT INTO stock (product_id, quantity) VALUES (?, ?)
                               ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)");
        $stmt->execute([$product_id, $quantity]);
    }
    $update_message = "Stock levels updated successfully.";
}

// Retrieve products with their stock levels
$stmt = $pdo->query("
    SELECT p.id, p.name, IFNULL(s.quantity, 0) AS quantity 
    FROM products p
    LEFT JOIN stock s ON p.id = s.product_id
    ORDER BY p.name ASC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Stock</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-stock-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px 30px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .admin-stock-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Roboto Condensed', serif;
            color: #333;
        }
        .update-message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: green;
            font-size: 1.1rem;
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
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input.stock-input {
            width: 80px;
            padding: 5px;
            text-align: center;
        }
        .submit-stock-btn {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
        }
        .submit-stock-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <main>
        <div class="admin-stock-container">
            <h2>Manage Product Stock</h2>
            <?php if ($update_message): ?>
                <p class="update-message"><?php echo htmlspecialchars($update_message); ?></p>
            <?php endif; ?>
            <form action="admin_stock.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Current Stock</th>
                            <th>New Stock Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td>
                                <input type="number" name="stock[<?php echo intval($product['id']); ?>]" class="stock-input" value="<?php echo htmlspecialchars($product['quantity']); ?>" min="0">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" class="submit-stock-btn">Update Stock Levels</button>
            </form>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
</body>
</html>
