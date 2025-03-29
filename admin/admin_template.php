<?php
session_start();

// Ensure only admin users can switch templates
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login_form.php");
    exit;
}

// If ?template= is present, set the cookie and redirect back
if (isset($_GET['template'])) {
    $allowedTemplates = ['default', 'blue', 'red', 'orange'];
    $template = in_array($_GET['template'], $allowedTemplates) ? $_GET['template'] : 'default';
    // Set the cookie for 30 days
    setcookie('site_template', $template, time() + (30 * 24 * 60 * 60), "/");
    header("Location: admin_template.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Template Switcher</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    /* Ensure full viewport height and flex layout */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }
    .page-container {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    main {
      flex: 1;
      padding: 30px;
      text-align: center;
    }
    h1 {
      font-family: 'Roboto Condensed', serif;
      font-size: 2rem;
      color: #333;
      margin-bottom: 20px;
    }
    p {
      font-size: 1rem;
      color: #555;
    }
    .template-buttons {
      display: inline-flex;
      gap: 15px;
      margin-top: 20px;
    }
    .template-buttons a {
      padding: 10px 20px;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }
    .template-default {
      background-color: #333;
    }
    .template-blue {
      background-color: #007acc;
    }
    .template-red {
      background-color: #cc0000;
    }
    .template-orange {
      background-color: #ff9800;
    }
    .template-buttons a:hover {
      opacity: 0.9;
    }
    .current-template {
      margin-top: 20px;
      font-size: 1.1rem;
      font-weight: bold;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="page-container">
    <?php include 'admin_header.php'; ?>
    <main>
      <h1>Change Site Template</h1>
      <p>Click one of the buttons below to switch the site's theme</p>
      <div class="template-buttons">
        <a href="admin_template.php?template=default" class="template-default">Default</a>
        <a href="admin_template.php?template=blue" class="template-blue">Winter</a>
        <a href="admin_template.php?template=red" class="template-red">Christmas</a>
        <a href="admin_template.php?template=orange" class="template-orange">Halloween</a>
      </div>
      <p class="current-template">
        Current template is set to <strong><?php echo isset($_COOKIE['site_template']) ? htmlspecialchars($_COOKIE['site_template']) : 'default'; ?></strong>
      </p>
    </main>
    <?php include '../templates/footer.php'; ?>
  </div>
</body>
</html>
