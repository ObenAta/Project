<?php
session_start();
require_once 'includes/config.php';

// Query available GPU products from your products table.
$stmt = $pdo->prepare("SELECT id, name, price FROM products ORDER BY name ASC");
$stmt->execute();
$gpuProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Financing Quote Calculator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
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
    .financing-form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .financing-form label {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .financing-form select,
    .financing-form input {
      padding: 10px;
      font-size: 1em;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 100%;
      box-sizing: border-box;
    }
    .quote-summary {
      margin-top: 20px;
      font-size: 1.2em;
      font-weight: bold;
      text-align: center;
      padding: 10px;
      background: #f9f9f9;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .quote-buttons {
      text-align: center;
      margin-top: 20px;
    }
    .quote-buttons button {
      padding: 10px 20px;
      font-size: 1em;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin: 0 10px;
    }
    .email-quote-btn {
      background-color: #28a745;
      color: #fff;
    }
    .email-quote-btn:hover {
      background-color: #218838;
    }
  </style>
  <!-- External JavaScript for dynamic calculations -->
  <script src="js/financing_quote.js" defer></script>
</head>
<body>
  <?php include 'templates/header.php'; ?>
  <main>
    <div class="quote-container">
      <h1>Financing Quote Calculator</h1>
      <form class="financing-form" id="financing-form">
        <label for="gpu-model">Select GPU Model:</label>
        <select name="gpu-model" id="gpu-model" required>
          <option value="" data-price="0">-- Select a GPU --</option>
          <?php foreach ($gpuProducts as $product): ?>
            <option value="<?php echo htmlspecialchars($product['id']); ?>" data-price="<?php echo htmlspecialchars($product['price']); ?>">
              <?php echo htmlspecialchars($product['name']); ?> - $<?php echo number_format($product['price'], 2); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label for="down-payment">Down Payment (%):</label>
        <input type="number" id="down-payment" name="down-payment" value="10" min="0" max="100" required>

        <label for="term">Financing Term (months):</label>
        <select name="term" id="term" required>
          <option value="">-- Select Term --</option>
          <option value="12">12 Months</option>
          <option value="24">24 Months</option>
          <option value="36">36 Months</option>
        </select>
      </form>
      <div class="quote-summary" id="quote-summary">
        Your Estimated Monthly Payment: $0.00
      </div>
      <div class="quote-buttons">
        <button type="button" class="email-quote-btn" id="email-quote-btn">Email Me My Quote</button>
      </div>
    </div>
  </main>
  <?php include 'templates/footer.php'; ?>
</body>
</html>
