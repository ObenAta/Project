<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GPU Paradise Admin Panel</title>
  <!-- Google Fonts Integration -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Material Symbols Icon Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="../css/style.css">
  <style>
    header {
      background: linear-gradient(135deg, #222, #111);
      padding: 10px 20px;
    }
    .admin-header {
      display: grid;
      grid-template-columns: auto 1fr auto;
      align-items: center;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
    }
    .admin-logo {
      font-family: 'Roboto Condensed', serif;
      font-size: 2rem;
      color: #fff;
      font-weight: bold;
      margin: 0;
    }
    nav {
      text-align: center;
    }
    .admin-nav-list {
      display: inline-flex;
      list-style: none;
      margin: 0;
      padding: 0;
      align-items: center;
    }
    .admin-nav-list li {
      margin: 0 10px;
    }
    .admin-nav-list li a {
      color: #fff;
      text-decoration: none;
      font-size: 1rem;
      display: flex;
      align-items: center;
      transition: color 0.3s;
    }
    .admin-nav-list li a span.material-symbols-outlined {
      margin-right: 5px;
      font-size: 1.2rem;
    }
    .admin-nav-list li a:hover {
      color: #ffd700;
    }
    .admin-menu-controls a {
      color: #fff;
      text-decoration: none;
      font-size: 1rem;
      margin-left: 20px;
    }
    /* Responsive adjustments for mobile */
    @media (max-width: 768px) {
      .admin-header {
        grid-template-columns: auto auto;
        gap: 10px;
      }
      nav {
        grid-column: 2 / -1;
      }
      .admin-nav-list {
        flex-direction: column;
      }
      .admin-nav-list li {
        margin: 5px 0;
      }
      .admin-menu-controls {
        display: none;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="admin-header">
      <div class="admin-logo">GPU Paradise Admin</div>
      <nav>
        <ul class="admin-nav-list">
          <li>
            <a href="dashboard.php">
              <span class="material-symbols-outlined">dashboard</span>
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="admin_add_product_form.php">
              <span class="material-symbols-outlined">add_box</span>
              <span>Add Product</span>
            </a>
          </li>
          <li>
            <a href="admin_edit_product_form.php">
              <span class="material-symbols-outlined">edit</span>
              <span>Edit Products</span>
            </a>
          </li>
          <li>
            <a href="admin_questions.php">
              <span class="material-symbols-outlined">question_answer</span>
              <span>Questions</span>
            </a>
          </li>
          <li>
            <a href="admin_rma.php">
              <span class="material-symbols-outlined">assignment_return</span>
              <span>RMA</span>
            </a>
          </li>
          <li>
            <a href="admin_stock.php">
              <span class="material-symbols-outlined">inventory_2</span>
              <span>Stock</span>
            </a>
          </li>
          <li>
            <a href="admin_account_administration.php">
              <span class="material-symbols-outlined">manage_accounts</span>
              <span>Account Administration</span>
            </a>
          </li>
          <li>
            <a href="admin_monitor.php">
              <span class="material-symbols-outlined">monitor</span>
              <span>Monitor</span>
            </a>
          </li>
          <li>
            <a href="admin_template.php">
              <span class="material-symbols-outlined">palette</span>
              <span>Template</span>
            </a>
          </li>
        </ul>
      </nav>
      <div class="admin-menu-controls">
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </header>
</body>
</html>
