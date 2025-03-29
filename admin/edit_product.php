<?php
session_start();
require_once '../includes/config.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Check for a valid product ID in GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}
$productId = intval($_GET['id']);

// Fetch the current product data
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    die("Product not found.");
}

$message = "";

// Process form submission if POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    
    // Check if a new image is uploaded; if not, keep the old image.
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = "../images/";
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $filename;
        } else {
            $image = $product['image'];
        }
    } else {
        $image = $product['image'];
    }
    
    // Update product in the database
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    if ($stmt->execute([$name, $description, $price, $image, $productId])) {
        $message = "Product updated successfully.";
        // Refresh product data
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Error updating product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px 30px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .admin-form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Roboto Condensed', serif;
            color: #333;
        }
        .admin-form-container .message {
            text-align: center;
            font-weight: bold;
            color: green;
            margin-bottom: 15px;
        }
        .admin-form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .admin-form-container form label {
            font-weight: bold;
            color: #333;
        }
        .admin-form-container form input[type="text"],
        .admin-form-container form input[type="number"],
        .admin-form-container form textarea,
        .admin-form-container form input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            width: 100%;
            box-sizing: border-box;
        }
        .admin-form-container form textarea {
            resize: vertical;
            min-height: 100px;
        }
        .admin-form-container form button {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            align-self: center;
        }
        .admin-form-container form button:hover {
            background-color: #218838;
        }
        .current-image {
            text-align: center;
            margin-top: 10px;
        }
        .current-image img {
            max-height: 100px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <main>
        <div class="admin-form-container">
            <h2>Edit Product</h2>
            <?php if (!empty($message)): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form action="edit_product.php?id=<?php echo intval($product['id']); ?>" method="post" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                
                <label for="description">Description:</label>
                <textarea name="description" id="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                
                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                
                <label for="image">Image (leave blank to keep existing):</label>
                <input type="file" name="image" id="image">
                <?php if (!empty($product['image'])): ?>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                <?php endif; ?>
                
                <button type="submit">Update Product</button>
            </form>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
</body>
</html>
