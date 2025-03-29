<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login_form.php");
    exit;
}

require_once '../includes/config.php'; // $pdo is defined here

// Handle Enable/Disable requests
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $action = $_GET['action'];
    $userId = (int)$_GET['user_id'];
    
    if ($action === 'disable') {
        $updateQuery = "UPDATE members SET status = 'disabled' WHERE id = ?";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([$userId]);
    } elseif ($action === 'enable') {
        $updateQuery = "UPDATE members SET status = 'enabled' WHERE id = ?";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([$userId]);
    }
    header("Location: admin_account_administration.php");
    exit;
}

// Fetch all members for listing
$membersQuery = "SELECT id, username, email, status FROM members ORDER BY username ASC";
$membersStmt = $pdo->query($membersQuery);
$members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all login history records for members
$historyQuery = "SELECT member_id, event_type, event_time, ip_address FROM login_history ORDER BY event_time DESC";
$stmtHistory = $pdo->query($historyQuery);
$allHistory = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);

// Group history by member_id
$historyByMember = [];
foreach ($allHistory as $log) {
    $memberId = $log['member_id'];
    if (!isset($historyByMember[$memberId])) {
         $historyByMember[$memberId] = [];
    }
    $historyByMember[$memberId][] = $log;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Account Administration</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        /* Wrapper to force footer to the bottom */
        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
            font-family: 'Roboto Condensed', serif;
            color: #333;
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-button {
            padding: 6px 12px;
            font-size: 0.9rem;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        .disable-button {
            background-color: #dc3545;
        }
        .enable-button {
            background-color: #28a745;
        }
        .toggle-history-button {
            background-color: #007bff;
        }
        .toggle-history-button:hover {
            opacity: 0.9;
        }
        /* Footer styling */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
        }
    </style>
    <script>
        // Simple JS function to toggle visibility of a member's history row
        function toggleHistory(memberId) {
            const row = document.getElementById("history-" + memberId);
            const toggleButton = document.getElementById("toggle-" + memberId);
            if (row.style.display === "none" || row.style.display === "") {
                row.style.display = "table-row";
                toggleButton.textContent = "Hide History";
            } else {
                row.style.display = "none";
                toggleButton.textContent = "Show History";
            }
        }
    </script>
</head>
<body>
    <div class="page-container">
        <?php include 'admin_header.php'; ?>
        <main>
            <h1>Account Administration</h1>
            <!-- Members List -->
            <table class="members-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($members)) : ?>
                    <?php foreach ($members as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo ($row['status'] === 'enabled') ? "Enabled" : "Disabled"; ?></td>
                            <td>
                                <?php if ($row['status'] === 'enabled'): ?>
                                    <button type="button" class="action-button disable-button" onclick="if(confirm('Disable this account?')) { window.location.href='?action=disable&user_id=<?php echo $row['id']; ?>'; }">Disable</button>
                                <?php else: ?>
                                    <button type="button" class="action-button enable-button" onclick="if(confirm('Enable this account?')) { window.location.href='?action=enable&user_id=<?php echo $row['id']; ?>'; }">Enable</button>
                                <?php endif; ?>
                                <button type="button" class="action-button toggle-history-button" id="toggle-<?php echo $row['id']; ?>" onclick="toggleHistory(<?php echo $row['id']; ?>)">Show History</button>
                            </td>
                        </tr>
                        <!-- Hidden row for login/logout history for this member -->
                        <tr id="history-<?php echo $row['id']; ?>" style="display:none;">
                            <td colspan="5">
                                <?php if (isset($historyByMember[$row['id']])): ?>
                                    <table class="history-table">
                                        <thead>
                                            <tr>
                                                <th>Event Time</th>
                                                <th>Event Type</th>
                                                <th>IP Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($historyByMember[$row['id']] as $log): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($log['event_time']); ?></td>
                                                    <td><?php echo htmlspecialchars($log['event_type']); ?></td>
                                                    <td><?php echo htmlspecialchars($log['ip_address'] ?? ''); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p>No login history found for this user.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No members found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </main>
        <?php include '../templates/footer.php'; ?>
    </div>
</body>
</html>
