<?php
session_start();
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true) {
    echo '<script>alert("You must be logged in to proceed."); window.location.href="membership.php";</script>';
    exit;
}

require_once 'includes/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid product ID.";
    exit;
}

$productId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit;
}

// Calculate refurbished price (15% off)
$refurbishedPrice = round($product['price'] * 0.85, 2);

$purchaseSuccess = false;
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (!isset($_POST['disclaimer'])) {
        $errorMessage = "You must acknowledge the disclaimer to proceed.";
    } else {
        // Insert the order record into the orders table
        $status = 'refurbished';
        $insertStmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, total, shipping_address, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        if ($insertStmt->execute([$_SESSION['member_id'], $productId, $refurbishedPrice, $address, $status])) {
            
            // Compose the confirmation email message
            $messageBody = "Dear $name,\n\n";
            $messageBody .= "Thank you for purchasing the refurbished version of " . $product['name'] . ".\n";
            $messageBody .= "Here are your purchase details:\n\n";
            $messageBody .= "Product: " . $product['name'] . "\n";
            $messageBody .= "Original Price: $" . number_format($product['price'], 2) . "\n";
            $messageBody .= "Refurbished Price (15% off): $" . number_format($refurbishedPrice, 2) . "\n";
            $messageBody .= "Shipping Address: $address\n\n";
            $messageBody .= "Please note: Refurbished products cannot be added to the cart due to limited stock, cannot be returned, and all sales are final.\n\n";
            $messageBody .= "Thank you for your purchase,\nGPU Paradise";

            // Retrieve the user's email (from session or by querying the database)
            if (!isset($_SESSION['member_email'])) {
                $stmtEmail = $pdo->prepare("SELECT email FROM members WHERE id = ?");
                $stmtEmail->execute([$_SESSION['member_id']]);
                $member = $stmtEmail->fetch(PDO::FETCH_ASSOC);
                if ($member) {
                    $userEmail = $member['email'];
                } else {
                    $errorMessage = "User email not found.";
                }
            } else {
                $userEmail = $_SESSION['member_email'];
            }

            if (empty($errorMessage)) {
                $subject = "Your Refurbished Purchase Confirmation";
                $headers = "From: no-reply@gpuparadise.com\r\n" .
                           "Reply-To: support@gpuparadise.com\r\n" .
                           "X-Mailer: PHP/" . phpversion();
                
                if (mail($userEmail, $subject, $messageBody, $headers)) {
                    $purchaseSuccess = true;
                } else {
                    $errorMessage = "Failed to send confirmation email.";
                }
            }
        } else {
            $errorMessage = "Failed to record your order.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Refurbished: <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .purchase-form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .purchase-form-container h2 {
            margin-bottom: 20px;
            text-align: center;
            font-family: 'Roboto Condensed', serif;
        }
        .purchase-form p {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.1em;
            color: #333;
        }
        .purchase-form input, 
        .purchase-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .purchase-form button {
            display: block;
            background-color: #28a745;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            margin: 20px auto 0;
        }
        .purchase-form button:hover {
            background-color: #218838;
        }
        .confirmation {
            text-align: center;
            color: green;
            font-weight: bold;
        }
        .error {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
        /* Improved Disclaimer styling */
        .disclaimer {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 0.95em;
            color: #555;
        }
        .disclaimer input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
        }
        .disclaimer label {
            margin: 0;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="purchase-form-container">
            <?php if ($purchaseSuccess): ?>
                <p class="confirmation">
                    Thank you for purchasing the refurbished version of <strong><?php echo htmlspecialchars($product['name']); ?></strong>! We’ve sent a confirmation email to you.
                </p>
            <?php else: ?>
                <?php if (!empty($errorMessage)): ?>
                    <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>
                <h2>Buy Refurbished: <?php echo htmlspecialchars($product['name']); ?></h2>
                <p><strong>Price:</strong> $<?php echo number_format($refurbishedPrice, 2); ?> (15% off)</p>
                <form method="POST" class="purchase-form">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <textarea name="address" placeholder="Shipping Address" required></textarea>
                    <div class="disclaimer">
                        <input type="checkbox" name="disclaimer" id="disclaimer" required>
                        <label for="disclaimer">
                            I understand that refurbished products cannot be added to the cart due to limited stock, cannot be returned, and all sales are final.
                        </label>
                    </div>
                    <button type="submit">Complete Purchase</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
