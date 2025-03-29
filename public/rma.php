<?php
session_start();
require_once 'includes/config.php';

// Ensure the member is logged in
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true) {
    echo "<script>
            alert('Please log in to start an RMA ticket.');
            window.location.href = 'membership.php';
          </script>";
    exit;
}

$member_id = intval($_SESSION['member_id']);
$rma_message = "";

// Process form submission if POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items']; // array of order_item_ids
    // Convert the array to a comma-separated string (you could also use JSON_encode)
    $order_item_ids_str = implode(",", $selected_items);

    // Get additional fields from the form
    $issue_type = isset($_POST['issue_type']) ? trim($_POST['issue_type']) : "";
    $issue_description = isset($_POST['issue_description']) ? trim($_POST['issue_description']) : "";
    
    // Process file upload for attached document (if any)
    $attached_document = "";
    if(isset($_FILES['attached_document']) && $_FILES['attached_document']['error'] == UPLOAD_ERR_OK) {
        // Define a directory to save uploads (make sure it exists and is writable)
        $upload_dir = "uploads/";
        // Create a unique file name
        $filename = time() . "_" . basename($_FILES['attached_document']['name']);
        $target_file = $upload_dir . $filename;
        if(move_uploaded_file($_FILES['attached_document']['tmp_name'], $target_file)) {
            $attached_document = $filename;
        } else {
            // Optionally, set an error message here.
            $attached_document = "";
        }
    }

    // Insert the RMA request into rma_requests table with the new fields
    $stmt = $pdo->prepare("INSERT INTO rma_requests (member_id, order_item_ids, issue_type, issue_description, attached_document, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    if ($stmt->execute([$member_id, $order_item_ids_str, $issue_type, $issue_description, $attached_document])) {
        $rma_message = "Your RMA ticket has been submitted successfully.";
    } else {
        $rma_message = "There was an error submitting your RMA ticket. Please try again.";
    }
}

// Retrieve all order items (purchases) for this member
$stmt = $pdo->prepare("
    SELECT oi.id AS order_item_id, o.id AS order_id, o.created_at AS order_date, p.name AS product_name
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$member_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Start RMA Ticket</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Tick icon for checkbox -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=check" />
    <style>
        .rma-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .rma-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
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
        /* Custom checkbox styling */
        .custom-checkbox {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }
        .custom-checkbox input {
            display: none;
        }
        .custom-checkbox span {
            font-variation-settings:
              'FILL' 0,
              'wght' 400,
              'GRAD' 0,
              'opsz' 24;
            color: #ccc;
            transition: color 0.2s;
        }
        .custom-checkbox input:checked + span {
            color: #28a745;
        }
        .submit-btn {
            display: block;
            margin: 20px auto; /* centers the button */
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1em;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .rma-message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: green;
        }
        /* Extra fields section initially hidden */
        #extra-fields {
            display: none;
            margin-top: 20px;
        }
        #extra-fields select, 
        #extra-fields textarea, 
        #extra-fields input[type="file"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            box-sizing: border-box;
        }
    </style>
    <script>
        // When any checkbox is clicked, show the extra fields
        document.addEventListener("DOMContentLoaded", function(){
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_items[]"]');
            const extraFieldsDiv = document.getElementById("extra-fields");
            checkboxes.forEach(chk => {
                chk.addEventListener("change", function(){
                    // If at least one checkbox is checked, show the extra fields; else, hide them
                    let anyChecked = Array.from(checkboxes).some(c => c.checked);
                    extraFieldsDiv.style.display = anyChecked ? "block" : "none";
                });
            });
        });
    </script>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="rma-container">
            <h2>Start RMA Ticket</h2>
            <?php if ($rma_message): ?>
                <p class="rma-message"><?php echo htmlspecialchars($rma_message); ?></p>
            <?php endif; ?>
            <?php if (count($order_items) > 0): ?>
            <form action="rma.php" method="post" enctype="multipart/form-data">
                <table>
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Order ID</th>
                            <th>Product Name</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td>
                                <label class="custom-checkbox">
                                    <input type="checkbox" name="selected_items[]" value="<?php echo intval($item['order_item_id']); ?>">
                                    <span class="material-symbols-outlined">check</span>
                                </label>
                            </td>
                            <td><?php echo htmlspecialchars($item['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['order_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Extra fields for RMA details -->
                <div id="extra-fields">
                    <label for="issue_type">Issue Type:</label>
                    <select name="issue_type" id="issue_type" required>
                        <option value="">-- Select Issue Type --</option>
                        <option value="Defective Product">Defective Product</option>
                        <option value="Wrong Item Received">Wrong Item Received</option>
                        <option value="Other">Other</option>
                    </select>
                    <label for="attached_document">Attach Document (optional):</label>
                    <input type="file" name="attached_document" id="attached_document">
                    <label for="issue_description">Issue Description:</label>
                    <textarea name="issue_description" id="issue_description" placeholder="Describe the issue in detail..." required></textarea>
                </div>
                <button type="submit" class="submit-btn">Submit RMA Ticket</button>
            </form>
            <?php else: ?>
                <p style="text-align: center;">No eligible purchases found for RMA request.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
