<?php
session_start();
require_once 'includes/config.php';
$message = '';

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and trim form input values
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address.";
    } else {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT id FROM members WHERE username = ?");
        $stmt->execute([$username]);
        $username_exists = ($stmt->rowCount() > 0);
        
        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT id FROM members WHERE email = ?");
        $stmt->execute([$email]);
        $email_exists = ($stmt->rowCount() > 0);
        
        if ($username_exists || $email_exists) {
            if ($username_exists && $email_exists) {
                $message = "Both username and email already exist. Please choose different ones.";
            } elseif ($username_exists) {
                $message = "Username already exists. Please choose another.";
            } else {
                $message = "Email already exists. Please choose another.";
            }
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Insert the new member into the database
            $stmt = $pdo->prepare("INSERT INTO members (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
            if ($stmt->execute([$username, $email, $hashedPassword])) {
                $message = "Account created successfully. Please sign in.";
            } else {
                $message = "Error creating account. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Registration container styling */
        .registration-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .registration-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .registration-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .registration-container input[type="text"],
        .registration-container input[type="email"],
        .registration-container input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        .registration-container button {
            padding: 12px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
        }
        .registration-container button:hover {
            background-color: #218838;
        }
        .registration-message {
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="registration-container">
            <h2>Create an Account</h2>
            <?php if ($message): ?>
                <?php if ($message === "Account created successfully. Please sign in."): ?>
                    <script>
                        alert("Account created successfully. Please sign in.");
                    </script>
                <?php else: ?>
                    <p class="registration-message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
            <?php endif; ?>
            <form action="create_account.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Create Account</button>
            </form>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
