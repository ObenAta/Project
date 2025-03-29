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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
    $question_id = intval($_POST['question_id']);
    $answer = trim($_POST['answer']);

    $stmt = $pdo->prepare("UPDATE questions SET answer = ? WHERE id = ?");
    if ($stmt->execute([$answer, $question_id])) {
        $update_message = "Answer updated successfully for question ID " . $question_id;
    } else {
        $update_message = "Error updating answer for question ID " . $question_id;
    }
}

$stmt = $pdo->query("SELECT * FROM questions ORDER BY created_at DESC");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Answer Customer Questions</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-questions-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px 30px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .admin-questions-container h2 {
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
        .question-item {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .question-item h3 {
            margin: 0 0 10px;
            color: #333;
            font-size: 1.4rem;
        }
        .question-item p {
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
        <div class="admin-questions-container">
            <h2>Admin - Answer Customer Questions</h2>
            <?php if (!empty($update_message)): ?>
                <p class="update-message"><?php echo htmlspecialchars($update_message); ?></p>
            <?php endif; ?>
            <?php if (count($questions) > 0): ?>
                <?php foreach ($questions as $question): ?>
                    <div class="question-item">
                        <h3><?php echo htmlspecialchars($question['subject']); ?></h3>
                        <p><strong>Question:</strong> <?php echo nl2br(htmlspecialchars($question['question'])); ?></p>
                        <p><strong>Asked on:</strong> <?php echo htmlspecialchars($question['created_at']); ?></p>
                        <?php if (!empty($question['answer'])): ?>
                            <p><strong>Answer:</strong> <?php echo nl2br(htmlspecialchars($question['answer'])); ?></p>
                        <?php else: ?>
                            <form class="answer-form" action="admin_questions.php" method="post">
                                <input type="hidden" name="question_id" value="<?php echo intval($question['id']); ?>">
                                <textarea name="answer" placeholder="Enter your answer here..." required></textarea>
                                <button type="submit">Submit Answer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">No customer questions at the moment.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
</body>
</html>
