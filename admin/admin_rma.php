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

// Process admin response submission for an RMA request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rma_id'])) {
    $rma_id = intval($_POST['rma_id']);
    $admin_response = trim($_POST['admin_response']);
    
    $stmt = $pdo->prepare("UPDATE rma_requests SET admin_response = ? WHERE id = ?");
    if ($stmt->execute([$admin_response, $rma_id])) {
        $update_message = "Response updated successfully for RMA Request ID " . $rma_id;
    } else {
        $update_message = "Error updating response for RMA Request ID " . $rma_id;
    }
}

// Retrieve all RMA requests
$stmt = $pdo->query("SELECT * FROM rma_requests ORDER BY created_at DESC");
$rma_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - RMA Requests</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-rma-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px 30px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .admin-rma-container h2 {
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
        .rma-item {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .rma-item:last-child {
            border-bottom: none;
        }
        .rma-item p {
            margin: 5px 0;
            line-height: 1.4;
            color: #555;
            font-size: 1rem;
        }
        .answer-form textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        .answer-form button {
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
        }
        .answer-form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <main>
        <div class="admin-rma-container">
            <h2>Admin - RMA Requests</h2>
            <?php if (!empty($update_message)): ?>
                <p class="update-message"><?php echo htmlspecialchars($update_message); ?></p>
            <?php endif; ?>
            <?php if (count($rma_requests) > 0): ?>
                <?php foreach ($rma_requests as $rma): ?>
                    <div class="rma-item">
                        <p><strong>RMA Request ID:</strong> <?php echo intval($rma['id']); ?></p>
                        <p><strong>Member ID:</strong> <?php echo intval($rma['member_id']); ?></p>
                        <p><strong>Order Item IDs:</strong> <?php echo htmlspecialchars($rma['order_item_ids']); ?></p>
                        <p><strong>Issue Type:</strong> <?php echo htmlspecialchars($rma['issue_type']); ?></p>
                        <p><strong>Issue Description:</strong> <?php echo nl2br(htmlspecialchars($rma['issue_description'])); ?></p>
                        <?php if (!empty($rma['attached_document'])): ?>
                            <p><strong>Attached Document:</strong> <a href="../uploads/<?php echo htmlspecialchars($rma['attached_document']); ?>" target="_blank">View Document</a></p>
                        <?php endif; ?>
                        <p><strong>Submitted on:</strong> <?php echo htmlspecialchars($rma['created_at']); ?></p>
                        <?php if (empty($rma['admin_response'])): ?>
                            <form class="answer-form" action="admin_rma.php" method="post">
                                <input type="hidden" name="rma_id" value="<?php echo intval($rma['id']); ?>">
                                <textarea name="admin_response" placeholder="Enter your response here..." required></textarea>
                                <button type="submit">Submit Response</button>
                            </form>
                        <?php else: ?>
                            <p><strong>Admin Response:</strong> <?php echo nl2br(htmlspecialchars($rma['admin_response'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">No RMA requests at the moment.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
</body>
</html>
