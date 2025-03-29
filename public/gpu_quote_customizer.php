<?php
session_start();
require_once 'includes/config.php';

// Query available GPU products from your products table.
$stmt = $pdo->prepare("SELECT id, name, price, image FROM products");
$stmt->execute();
$gpuProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GPU Quote Customizer</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- Material Symbols Outlined for Search Icon -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
  <style>
    .quote-container {
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .quote-container h1 {
      text-align: center;
      font-family: 'Roboto Condensed', serif;
      font-size: 2rem;
      color: #333;
      margin-bottom: 20px;
    }
    .quote-form fieldset {
      border: 1px solid #ccc;
      border-radius: 4px;
      margin: 20px 0;
      padding: 10px;
    }
    .quote-form legend {
      font-weight: bold;
      margin-bottom: 10px;
    }
    .quote-form label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .quote-form select,
    .quote-form input[type="checkbox"] {
      margin-right: 10px;
    }
    .quote-summary {
      margin-top: 20px;
      font-size: 1.2rem;
      font-weight: bold;
      text-align: center;
      color: #333;
    }
    .quote-buttons {
      margin-top: 20px;
      text-align: center;
    }
    .quote-buttons button {
      padding: 10px 20px;
      margin: 0 10px;
      font-size: 1rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .email-quote {
      background-color: #28a745;
      color: #fff;
    }
  </style>
  <!-- External JavaScript for GPU Quote Customizer -->
  <script src="js/gpu_quote.js" defer></script>
</head>
<body>
  <?php include 'templates/header.php'; ?>
  <main>
    <div class="quote-container">
      <h1>GPU Quote Customizer</h1>
      <form class="quote-form" id="quote-form">
        <label for="gpu-model">Select GPU Model:</label>
        <select name="gpu-model" id="gpu-model">
          <option value="" data-price="0">-- Select a GPU --</option>
          <?php foreach ($gpuProducts as $product): ?>
            <option value="<?php echo htmlspecialchars($product['id']); ?>" data-price="<?php echo htmlspecialchars($product['price']); ?>">
              <?php echo htmlspecialchars($product['name']); ?> - $<?php echo number_format($product['price'], 2); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <fieldset>
          <legend>Optional Add-Ons</legend>
          <label>
            <input type="checkbox" name="addon" value="Extended Warranty (+1 year)" data-price="49">
            Extended Warranty (+1 year) - $49
          </label>
          <label>
            <input type="checkbox" name="addon" value="Extended Warranty (+2 years)" data-price="89">
            Extended Warranty (+2 years) - $89
          </label>
          <label>
            <input type="checkbox" name="addon" value="Professional Installation Help" data-price="59">
            Professional Installation Help - $59
          </label>
          <label>
            <input type="checkbox" name="addon" value="Thermal Paste Upgrade" data-price="19">
            Thermal Paste Upgrade - $19
          </label>
          <label>
            <input type="checkbox" name="addon" value="Anti-Static Shipping Kit" data-price="9">
            Anti-Static Shipping Kit - $9
          </label>
          <label>
            <input type="checkbox" name="addon" value="Overclocking Certification" data-price="29">
            Overclocking Certification - $29
          </label>
          <label>
            <input type="checkbox" name="addon" value="Priority Handling & Shipping" data-price="39">
            Priority Handling & Shipping - $39
          </label>
        </fieldset>
      </form>
      <div class="quote-summary" id="quote-summary">
        Your Estimated Total: $0.00
      </div>
      <div class="quote-buttons">
        <button type="button" class="email-quote">Email Me My Quote</button>
      </div>
    </div>
  </main>
  <?php include 'templates/footer.php'; ?>
</body>
</html>
