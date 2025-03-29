<?php
session_start();
require_once 'includes/config.php';
header('Content-Type: application/json');

// Check if user is logged in and email is available
if (!isset($_SESSION['member_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

// Validate POST parameters
if (!isset($_POST['gpu_id']) || !isset($_POST['total']) || !isset($_POST['addons'])) {
    echo json_encode(["success" => false, "message" => "Missing parameters."]);
    exit;
}

$gpu_id = $_POST['gpu_id'];
$total = $_POST['total'];
$addons = $_POST['addons']; // JSON string of add-ons

// Retrieve GPU details from the database
$stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
$stmt->execute([$gpu_id]);
$gpu = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$gpu) {
    echo json_encode(["success" => false, "message" => "Invalid GPU selected."]);
    exit;
}

// Prepare the quote message
$gpuName = $gpu['name'];
$basePrice = number_format($gpu['price'], 2);
$addonsArray = json_decode($addons, true);
$addonsText = "None";
if (!empty($addonsArray)) {
    $addonsText = implode(", ", $addonsArray);
}

$messageBody = "Thank you for requesting a quote for your GPU purchase.\n\n";
$messageBody .= "GPU Model: $gpuName\n";
$messageBody .= "Base Price: \$$basePrice\n";
$messageBody .= "Selected Add-Ons: $addonsText\n";
$messageBody .= "Total Estimated Price: \$" . number_format($total, 2) . "\n\n";
$messageBody .= "If you have any questions, please reply to this email.";

// Retrieve the user's email.
// Alternatively, query the database for the member using $_SESSION['member_id'].
if (!isset($_SESSION['member_email'])) {
    // If not stored in session, query the database
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
$subject = "Your GPU Quote";
$headers = "From: no-reply@gpuparadise.com\r\n" .
           "Reply-To: support@gpuparadise.com\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Use PHP's mail function to send the email
if (mail($userEmail, $subject, $messageBody, $headers)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to send email."]);
}
exit;
?>
