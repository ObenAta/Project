<?php
session_start();
require_once 'includes/config.php';

// Ensure the member is logged in
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true) {
    echo "<script>
            alert('Please log in to view your RMA tickets and Q&A.');
            window.location.href = 'membership.php';
          </script>";
    exit;
}

$member_id = intval($_SESSION['member_id']);

// Retrieve RMA requests for this member
$stmt = $pdo->prepare("SELECT * FROM rma_requests WHERE member_id = ? ORDER BY created_at DESC");
$stmt->execute([$member_id]);
$rma_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve Q&A from the questions table
$stmt = $pdo->prepare("SELECT * FROM questions WHERE member_id = ? ORDER BY created_at DESC");
$stmt->execute([$member_id]);
$qa_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My RMA Tickets &amp; Q&amp;A - My Online Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        .section-container {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            padding: 20px;
        }
        .section-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Styles for table (for RMA tickets) */
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
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Q&A styles */
        .qa-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .qa-item:last-child {
            border-bottom: none;
        }
        .qa-item h3 {
            margin: 0 0 10px;
            font-size: 1.2em;
            color: #333;
        }
        .qa-item p {
            margin: 5px 0;
            line-height: 1.5;
            color: #555;
        }
        .qa-item .qa-status {
            font-style: italic;
            color: #888;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="container">
            <!-- RMA Tickets Section -->
            <div class="section-container">
                <h2>My RMA Tickets</h2>
                <?php if (count($rma_requests) == 0): ?>
                    <p style="text-align: center;">You have no RMA tickets.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Order Item IDs</th>
                                <th>Issue Type</th>
                                <th>Issue Description</th>
                                <th>Attached Document</th>
                                <th>Submitted On</th>
                                <th>Admin Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rma_requests as $rma): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rma['id']); ?></td>
                                <td><?php echo htmlspecialchars($rma['order_item_ids']); ?></td>
                                <td><?php echo htmlspecialchars($rma['issue_type']); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($rma['issue_description'])); ?></td>
                                <td>
                                    <?php if (!empty($rma['attached_document'])): ?>
                                        <a href="uploads/<?php echo htmlspecialchars($rma['attached_document']); ?>" target="_blank">View Document</a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($rma['created_at']); ?></td>
                                <td>
                                    <?php 
                                        if(empty($rma['admin_response'])) {
                                            echo "<span class='qa-status'>Awaiting admin response.</span>";
                                        } else {
                                            echo nl2br(htmlspecialchars($rma['admin_response']));
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <!-- Q&A Section -->
            <div class="section-container">
                <h2>My Questions &amp; Answers</h2>
                <?php if (count($qa_records) == 0): ?>
                    <p style="text-align: center;">You have not asked any questions.</p>
                <?php else: ?>
                    <?php foreach ($qa_records as $qa): ?>
                    <div class="qa-item">
                        <h3><?php echo htmlspecialchars($qa['subject']); ?></h3>
                        <p><strong>Question:</strong> <?php echo nl2br(htmlspecialchars($qa['question'])); ?></p>
                        <p><small>Asked on: <?php echo htmlspecialchars($qa['created_at']); ?></small></p>
                        <?php if (empty($qa['answer'])): ?>
                            <p class="qa-status">Your question is being reviewed by the Admin.</p>
                        <?php else: ?>
                            <p><strong>Answer:</strong> <?php echo nl2br(htmlspecialchars($qa['answer'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <p style="text-align:center;">
             Need help using this page? 
            <a href="myanswershelp.html">Click here for instructions.</a>
            </p>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
