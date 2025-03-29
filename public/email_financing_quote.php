<?php
session_start();
require_once 'includes/config.php';
header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['member_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

// Validate required POST parameters
if (!isset($_POST['gpu_id']) || !isset($_POST['down_payment']) || !isset($_POST['term']) || !isset($_POST['monthly_payment']) || !isset($_POST['base_price'])) {
    echo json_encode(["success" => false, "message" => "Missing parameters."]);
    exit;
}

$gpu_id = $_POST['gpu_id'];
$down_payment = $_POST['down_payment'];
$term = $_POST['term'];
$monthly_payment = $_POST['monthly_payment'];
$base_price = $_POST['base_price'];

// Retrieve GPU details from the database
$stmt = $pdo->prepare("SELECT name FROM products WHERE id = ?");
$stmt->execute([$gpu_id]);
$gpu = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$gpu) {
    echo json_encode(["success" => false, "message" => "Invalid GPU selected."]);
    exit;
}

$gpuName = $gpu['name'];

$messageBody  = "Dear Customer,\n\n";
$messageBody .= "Here is your financing quote for purchasing the GPU:\n\n";
$messageBody .= "GPU Model: $gpuName\n";
$messageBody .= "Base Price: $$base_price\n";
$messageBody .= "Down Payment: $down_payment%\n";
$messageBody .= "Financing Term: $term months\n";
$messageBody .= "Estimated Monthly Payment: $$monthly_payment\n\n";
$messageBody .= "Thank you for using GPU Paradise financing services.\n";
$messageBody .= "If you have any questions, please contact our support team.";

// Retrieve the user's email from session or via database lookup
if (!isset($_SESSION['member_email'])) {
    $stmtEmail = $pdo->prepare("SELECT email FROM members WHERE id = ?");
    $stmtEmail->execute([$_SESSION['member_id']]);
    $member = $stmtEmail->fetch(PDO::FETCH_ASSOC);
    if ($member) {
        $userEmail = $member['email'];
    } else {
        echo json_encode(["success" => false, "message" => "User email not found."]);
        exit;
    }
} else {
    $userEmail = $_SESSION['member_email'];
}

$subject = "Your Financing Quote from GPU Paradise";
$headers = "From: no-reply@gpuparadise.com\r\n" .
           "Reply-To: support@gpuparadise.com\r\n" .
           "X-Mailer: PHP/" . phpversion();

if (mail($userEmail, $subject, $messageBody, $headers)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to send email."]);
}
exit;
?>
