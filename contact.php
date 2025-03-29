<?php
session_start();
require_once 'includes/config.php';

// Only allow logged-in clients to ask a question.
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true) {
    echo "<script>
            alert('Please log in to ask a question.');
            window.location.href = 'membership.php';
          </script>";
    exit;
}

$message = "";
$member_id = intval($_SESSION['member_id']); // Get the logged-in member's ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and trim form inputs
    $subject = trim($_POST['subject']);
    $question = trim($_POST['question']);
    
    // Validate inputs
    if (empty($subject) || empty($question)) {
        $message = "Please fill in both the subject and your question.";
    } else {
        // Insert the new question into the questions table, including member_id
        $stmt = $pdo->prepare("INSERT INTO questions (member_id, subject, question, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$member_id, $subject, $question])) {
            $message = "Your question has been submitted. We will get back to you soon.";
        } else {
            $message = "There was an error submitting your question. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - My Online Store</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .contact-container {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .contact-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .contact-container form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .contact-container input[type="text"],
    .contact-container textarea {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1em;
      width: 100%;
      box-sizing: border-box;
    }
    .contact-container textarea {
      resize: vertical;
      height: 150px;
    }
    .contact-container button {
      padding: 12px;
      background-color: #007bff;
      border: none;
      border-radius: 4px;
      color: #fff;
      font-size: 1em;
      cursor: pointer;
      max-width: 150px; /* Smaller width */
      margin: 0 auto;   /* Center the button */
    }
    .contact-container button:hover {
      background-color: #0056b3;
    }
    .contact-message {
      text-align: center;
      margin-top: 15px;
      font-weight: bold;
      color: green;
    }
  </style>
</head>
<body>
  <?php include 'templates/header.php'; ?>
  <main>
    <div class="contact-container">
      <h2>Contact Us</h2>
      <?php if ($message): ?>
        <p class="contact-message"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>
      <form action="contact.php" method="post">
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="question" placeholder="Your question here..." required></textarea>
        <button type="submit">Submit Question</button>
      </form>
    </div>
          <p style="text-align:center;">
           Need help using this page? 
          <a href="contacthelp.html">Click here for instructions.</a>
          </p>
  </main>
  <?php include 'templates/footer.php'; ?>
</body>
</html>
