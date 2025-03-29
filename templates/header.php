<?php
session_start();
// Set default SEO meta tags
if (!isset($pageTitle)) {
    $pageTitle = "Online Store";
}
if (!isset($pageDescription)) {
    $pageDescription = "Welcome to GPU Paradise, your destination for the latest GPUs, custom quotes, and cutting-edge technology.";
}
if (!isset($pageKeywords)) {
    $pageKeywords = "GPU, online store, custom quotes, electronics, GPU shopping";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
  <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
  <meta name="author" content="GPU PARADISE">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.ico">
  <!-- Google Fonts Integration -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Material Symbols Icon Font (loaded once) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <?php
    // Load the proper CSS based on the cookie (default template)
    $template = isset($_COOKIE['site_template']) ? $_COOKIE['site_template'] : 'default';
    switch ($template) {
        case 'blue':
            echo '<link rel="stylesheet" href="css/blue.css">';
            break;
        case 'red':
            echo '<link rel="stylesheet" href="css/red.css">';
            break;
        default:
            echo '<link rel="stylesheet" href="css/style.css">';
            break;
        case 'orange':
            echo '<link rel="stylesheet" href="css/orange.css">';
            break;
    }
  ?>
  <style>
    .header-grid {
      display: grid;
      grid-template-columns: auto 1fr auto;
      align-items: center;
      width: 100%;
    }
    .logo {
      font-family: 'Roboto Condensed', serif;
      font-size: 2rem;
      color: #fff;
      font-weight: bold;
      margin: 0;
    }
    nav {
      text-align: center;
    }
    .nav-list {
      display: inline-flex;
      list-style: none;
      margin: 0;
      padding: 0;
      align-items: center;
    }
    .nav-list li {
      margin: 0 10px;
    }
    .nav-list li a {
      color: #fff;
      text-decoration: none;
      font-size: 1rem;
      display: flex;
      align-items: center;
      transition: color 0.3s;
    }
    .nav-list li a span.material-symbols-outlined {
      margin-right: 5px;
      font-size: 1.2rem;
    }
    .nav-list li a:hover {
      color: #ffd700;
    }
    .menu-controls {
      position: relative;
    }
    /* New sort dropdown styling */
    #sort-menu-btn {
      background: transparent;
      border: none;
      color: #fff;
      cursor: pointer;
      font-size: 1.5rem;
    }
    #sort-dropdown {
      display: none;
      position: absolute;
      top: 40px;
      right: 0;
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 4px;
      z-index: 1000;
      min-width: 200px;
      color: #333;
    }
    #sort-dropdown div {
      padding: 10px;
      cursor: pointer;
      border-bottom: 1px solid #eee;
    }
    #sort-dropdown div:last-child {
      border-bottom: none;
    }
    #sort-dropdown div:hover {
      background-color: #f2f2f2;
    }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", function(){
      const sortMenuBtn = document.getElementById("sort-menu-btn");
      const sortDropdown = document.getElementById("sort-dropdown");
      
      sortMenuBtn.addEventListener("click", function(e){
          // Toggle dropdown visibility
          if(sortDropdown.style.display === "none" || sortDropdown.style.display === ""){
              sortDropdown.style.display = "block";
          } else {
              sortDropdown.style.display = "none";
          }
          e.stopPropagation();
      });
      
      // Close dropdown when clicking anywhere else
      document.addEventListener("click", function(){
          sortDropdown.style.display = "none";
      });
      
      // Attach click event to each sort option
      const sortOptions = document.querySelectorAll("#sort-dropdown .sort-option");
      sortOptions.forEach(function(option) {
          option.addEventListener("click", function(){
              const sortValue = this.getAttribute("data-sort");
              window.location.href = "index.php?sort=" + sortValue;
          });
      });
    });
  </script>
</head>
<body>
  <header>
    <div class="header-grid">
      <div class="logo">GPU PARADISE</div>
      <nav>
        <ul class="nav-list">
          <li><a href="index.php"><span class="material-symbols-outlined">home</span><span class="nav-text">Home</span></a></li>
          <li><a href="search.php"><span class="material-symbols-outlined">search</span><span class="nav-text">Search</span></a></li>
          <li><a href="cart.php"><span class="material-symbols-outlined">shopping_cart</span><span class="nav-text">Cart</span></a></li>
          <li><a href="order_history.php"><span class="material-symbols-outlined">receipt_long</span><span class="nav-text">Order History</span></a></li>
          <li><a href="membership.php"><span class="material-symbols-outlined">person</span><span class="nav-text">My Account</span></a></li>
          <li><a href="faq.php"><span class="material-symbols-outlined">help_outline</span><span class="nav-text">FAQ</span></a></li>
          <li><a href="contact.php"><span class="material-symbols-outlined">contact_support</span><span class="nav-text">Contact</span></a></li>
          <?php if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true): ?>
            <li><a href="myanswers.php"><span class="material-symbols-outlined">question_answer</span><span class="nav-text">My Answers</span></a></li>
            <li><a href="logout.php"><span class="material-symbols-outlined">logout</span><span class="nav-text">Logout</span></a></li>
          <?php endif; ?>
          <li><a href="stock.php"><span class="material-symbols-outlined">inventory_2</span><span class="nav-text">Stock Levels</span></a></li>
          <li><a href="map.php"><span class="material-symbols-outlined">map</span><span class="nav-text">Map</span></a></li>
        </ul>
      </nav>
      <div class="menu-controls">
        <button id="sort-menu-btn" title="Sort Products"><span class="material-symbols-outlined">sort</span></button>
        <div id="sort-dropdown">
          <div class="sort-option" data-sort="alphabetical">Sort by Alphabetical Order</div>
          <div class="sort-option" data-sort="price_asc">Sort by Price (Low to High)</div>
          <div class="sort-option" data-sort="price_desc">Sort by Price (High to Low)</div>
        </div>
      </div>
    </div>
  </header>
