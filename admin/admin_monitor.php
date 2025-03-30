<?php
// monitor.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function checkServiceStatus($url, $timeout = 5) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_errno($ch);
    curl_close($ch);
    
    if ($error || $httpCode < 200 || $httpCode >= 300) {
        return false;
    }
    return true;
}

$services = [
    'Home Page' => 'https://taskine.myweb.cs.uwindsor.ca/index.php',
    'Search Page' => 'https://taskine.myweb.cs.uwindsor.ca/search.php',
    'Cart' => 'https://taskine.myweb.cs.uwindsor.ca/cart.php',
    'Membership/Login' => 'https://taskine.myweb.cs.uwindsor.ca/membership.php',
];

$statusResults = [];
foreach ($services as $name => $url) {
    $statusResults[$name] = checkServiceStatus($url);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Website Monitoring</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Global styles to ensure the page container fills the viewport */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    *, *:before, *:after {
        box-sizing: inherit;
    }
    .page-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    main {
        flex: 1;
        padding: 20px;
    }
    .container {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    .online {
        color: green;
        font-weight: bold;
    }
    .offline {
        color: red;
        font-weight: bold;
    }
    /* Ensure the footer is always at the bottom */
    footer {
        margin-top: auto;
        background-color: #003366;
        color: #fff;
        padding: 15px;
        text-align: center;
    }
  </style>
</head>
<body>
  <div class="page-container">
    <?php include 'admin_header.php'; ?>
    <main>
      <div class="container">
        <h1>Website Monitoring</h1>
        <table>
          <thead>
            <tr>
              <th>Service</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($statusResults as $service => $status): ?>
              <tr>
                <td><?php echo htmlspecialchars($service); ?></td>
                <td>
                  <?php if ($status): ?>
                    <span class="online">Online</span>
                  <?php else: ?>
                    <span class="offline">Offline</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
    <?php include '../templates/footer.php'; ?>
  </div>
</body>
</html>
