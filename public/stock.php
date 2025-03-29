<?php
session_start();
require_once 'includes/config.php';

// Retrieve stock levels for all products (if a product doesn't have stock recorded, default to 0)
$stmt = $pdo->query("
    SELECT p.name AS product_name, IFNULL(s.quantity, 0) AS quantity
    FROM products p
    LEFT JOIN stock s ON p.id = s.product_id
    ORDER BY p.name ASC
");
$stock_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare arrays for chart labels and data, removing "video card" from names
$labels = [];
$data = [];
$unwanted = ["video card", "16GB", "32GB", "GDDR7", "GeForce", "Gaming"];
foreach ($stock_data as $row) {
    // Remove unwanted substrings from the product name
    $cleanName = str_ireplace($unwanted, "", $row['product_name']);
    $labels[] = trim($cleanName);
    $data[] = (int)$row['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Stock Levels</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            height: 500px;
        }
        @media (max-width: 768px) {
            .chart-container {
                height: 300px;
            }
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <h2>Product Stock Levels</h2>
        <div class="chart-container">
            <canvas id="stockChart"></canvas>
        </div>
        <p style="text-align:center;">
            Need help using this page? 
            <a href="stockhelp.html">Click here for instructions.</a>
        </p>
    </main>
    <?php include 'templates/footer.php'; ?>
    <script>
        // Pass PHP arrays to JavaScript as JSON
        const labels = <?php echo json_encode($labels); ?>;
        const data = <?php echo json_encode($data); ?>;
        
        const ctx = document.getElementById('stockChart').getContext('2d');
        const isMobile = window.innerWidth < 768;
        
        const stockChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Stock Level',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: isMobile ? 'y' : 'x',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            autoSkip: false,
                            maxRotation: isMobile ? 0 : 45,
                            minRotation: isMobile ? 0 : 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
