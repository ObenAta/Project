<?php
// index.php
require_once 'includes/config.php';

// Fetch products for display
$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include 'templates/header.php';
?>

<!-- BEGIN: Popup Modal -->
<style>
  /* Full-page overlay */
  .modal {
    display: none;  /* hidden by default */
    position: fixed; 
    z-index: 999;   
    left: 0; 
    top: 0;
    width: 100%; 
    height: 100%;
    overflow: auto;       
    background-color: rgba(0, 0, 0, 0.4); /* semi-transparent background */
  }

  /* Modal content box */
  .modal-content {
    background-color: #fff;
    margin: 10% auto;  /* center vertically/horizontally */
    padding: 20px;
    border-radius: 5px;
    width: 80%;
    max-width: 600px; 
  }

  /* Close button style */
  .close-btn {
    background-color: #007bff; 
    color: #fff; 
    border: none; 
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 4px; 
    font-size: 1em;
  }
  .close-btn:hover {
    background-color: #0056b3;
  }
</style>

<div id="popupModal" class="modal">
  <div class="modal-content">
    <p>
      This project is a comprehensive, full-featured e-commerce application designed to deliver
      a seamless shopping experience for customers while offering a robust, dynamic admin interface
      for efficient store management. The site boasts a range of interactive features including live
      search suggestions, instant AJAX filtering of product listings, and smart “Did you mean…?”
      corrections to enhance user navigation. A standout dynamic element is the GPU Quote Customizer,
      which allows users to personalize their GPU purchase by selecting from various optional add‑ons
      —such as extended warranty, professional installation, thermal paste upgrades, anti-static
      shipping kits, overclocking certification, and priority handling—with real-time pricing updates
      and the ability to receive a detailed quote via email. Additionally, the website incorporates
      dynamic theme switching for both admin and client interfaces, secure member and admin login
      systems, order tracking, and a monitoring dashboard that reports on service status, ensuring a
      modern, responsive, and customizable user experience.
    </p>
    <!-- Center the button -->
    <div style="text-align:center;">
      <button class="close-btn" onclick="closePopup()">I read and understand</button>
    </div>
  </div>
</div>

<script>
  // Show the popup as soon as the window loads
  window.onload = function() {
    document.getElementById("popupModal").style.display = "block";
  };

  // Hide the popup when the button is clicked
  function closePopup() {
    document.getElementById("popupModal").style.display = "none";
  }
</script>
<!-- END: Popup Modal -->

<?php
// Include main content
include 'templates/home_content.php';
?>

<!-- Place the help link -->
<p style="text-align:center; margin: 20px 0;">
  Need help using this page? 
  <a href="indexhelp.html">Click here for instructions.</a>
</p>

<?php
include 'templates/footer.php';
?>
