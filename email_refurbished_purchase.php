<?php
session_start();
require_once 'includes/config.php';
header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['member_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

// Validate required POST parameters
if (!isset($_POST['product_id']) || !isset($_POST['name']) || !isset($_POST['address'])) {
    echo json_encode(["success" => false, "message" => "Missing parameters."]);
    exit;
}

$product_id = $_POST['product_id'];
$name = trim($_POST['name']);
$address = trim($_POST['address']);

// Retrieve product details from the database
$stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo json_encode(["success" => false, "message" => "Invalid product selected."]);
    exit;
}

$productName = $product['name'];
$basePrice = $product['price'];
$refurbishedPrice = round($basePrice * 0.85, 2);

// Compose the email message for the refurbished purchase
$messageBody = "Dear $name,\n\n";
$messageBody .= "Thank you for purchasing the refurbished version of $productName.\n";
$messageBody .= "Here are your purchase details:\n\n";
$messageBody .= "Product: $productName\n";
$messageBody .= "Original Price: \$$basePrice\n";
$messageBody .= "Refurbished Price (15% off): \$$refurbishedPrice\n";
$messageBody .= "Shipping Address: $address\n\n";
$messageBody .= "Please note that refurbished products cannot be returned and all sales are final.\n\n";
$messageBody .= "Thank you for your purchase,\n";
$messageBody .= "GPU Paradise";

// Retrieve the user's email (using session variable or a DB lookup)
if (!isset($_SESSION['member_email'])) {
    $stmt = $pdo->prepare("SELECT email FROM members WHERE id = ?");
    $stmt->execute([$_SESSION['member_id']]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($member) {
        $userEmail = $member['email'];
    } else {
        echo json_encode(["success" => false, "message" => "User email not found."]);
        exit;
    }
} else {
    $userEmail = $_SESSION['member_email'];
}

// Set email subject and headers
$subject = "Your Refurbished Purchase Confirmation";
$headers = "From: no-reply@gpuparadise.com\r\n" .
           "Reply-To: support@gpuparadise.com\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Send the email using PHP's mail() function
if (mail($userEmail, $subject, $messageBody, $headers)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to send email."]);
}
exit;
?>
