<?php
session_start();
require_once '../includes/config.php';

$message = isset($message) ? $message : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin Panel</title>
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
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <main>
        <div class="admin-form-container">
            <h2>Add New Product</h2>
            <?php if (!empty($message)): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form action="add_product.php" method="post" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" required>
                
                <label for="description">Description:</label>
                <textarea name="description" id="description"></textarea>
                
                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" id="price" required>
                
                <label for="image">Image:</label>
                <input type="file" name="image" id="image">
                
                <button type="submit">Add Product</button>
            </form>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
</body>
</html>

