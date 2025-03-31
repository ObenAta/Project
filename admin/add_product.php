<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/config.php';

$message = ""; // to store success/error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Default to 'default.png' if no image is uploaded
    $imageName = 'default.png';

    // Check if a file was uploaded without errors
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        
        // The directory where we want to store the uploaded file
        // __DIR__ gives the directory of the current file (admin folder),
        // so ../images/ refers to public_html/images
        $targetDir = __DIR__ . '/../images/';
        
        // Create the images folder if it doesn't exist (optional safeguard)
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Get the original file name
        $originalFileName = basename($_FILES['image']['name']);
        
        // Create a unique prefix to avoid overwriting files
        // e.g., 1677601113_filename.jpg
        $uniquePrefix = time() . '_';
        $imageName = $uniquePrefix . $originalFileName;

        // Build the full path to where we want to move the file
        $targetFile = $targetDir . $imageName;

        // Move the uploaded file from the temp directory to our images folder
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // If something went wrong, revert to default.png
            $imageName = 'default.png';
        }
    }

    // Insert product info into the database, including the final $imageName
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $description, $price, $imageName])) {
        $message = "Product added successfully!";
    } else {
        $message = "Error adding product.";
    }
}

// Include the HTML form
include '../admin/admin_add_product_form.php';
?>
