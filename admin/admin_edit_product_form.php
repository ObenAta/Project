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

// If a form is submitted for a specific product (via ?id=...), process the update.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = intval($_GET['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    
    // Retrieve the old image filename
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $oldImage = $stmt->fetchColumn();

    // Check if a new image is uploaded; if not, keep the old image.
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = "../images/";
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $filename;
        } else {
            $image = $oldImage;
        }
    } else {
        $image = $oldImage;
    }
    
    // Update product in the database
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    if ($stmt->execute([$name, $description, $price, $image, $productId])) {
        $update_message = "Product (ID $productId) updated successfully.";
    } else {
        $update_message = "Error updating product (ID $productId).";
    }
}

// Retrieve all products with their stock levels
$stmt = $pdo->query("
    SELECT p.id, p.name, p.description, p.price, p.image, IFNULL(s.quantity, 0) AS quantity 
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
    <title>Admin - Edit Products</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-edit-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px 30px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .admin-edit-container h2 {
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
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
        }
        .product-img {
            max-height: 80px;
        }
        .edit-input {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }
        /* Updated buttons styling */
        .action-btn {
            display: inline-block;
            vertical-align: middle;
            padding: 5px 10px;
            font-size: 1rem;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px; /* Equal margin for spacing */
        }
        .update-btn {
            background-color: #007bff;
        }
        .remove-btn {
            background-color: #dc3545;
        }
        .action-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <main>
        <div class="admin-edit-container">
            <h2>Edit Products</h2>
            <?php if (!empty($update_message)): ?>
                <p class="update-message"><?php echo htmlspecialchars($update_message); ?></p>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Photo</th>
                        <th>Current Name</th>
                        <th>Current Description</th>
                        <th>Current Price</th>
                        <th>Stock</th>
                        <th>New Name</th>
                        <th>New Description</th>
                        <th>New Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($product['description'])); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                        <!-- Each row's form submits to the same file with the product id in the query string -->
                        <form action="admin_edit_product_form.php?id=<?php echo intval($product['id']); ?>" method="post" enctype="multipart/form-data">
                        <td>
                            <input type="text" name="name" class="edit-input" value="<?php echo htmlspecialchars($product['name']); ?>">
                        </td>
                        <td>
                            <textarea name="description" class="edit-input"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="price" class="edit-input" value="<?php echo htmlspecialchars($product['price']); ?>">
                        </td>
                        <td>
                            <button type="submit" class="action-btn update-btn">Update</button>
                            <a href="remove_product.php?id=<?php echo intval($product['id']); ?>" class="action-btn remove-btn" onclick="return confirm('Are you sure you want to remove this product?');">Remove</a>
                        </td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
</body>
</html>
