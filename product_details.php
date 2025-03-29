<?php
// product_details.php
require_once 'includes/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid product ID.";
    exit;
}

$productId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Material Symbols for Shopping Cart Checkout Icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=shopping_cart_checkout" />
    <style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24;
        }
        /* Container for product details layout */
        .product-details-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px auto;
            max-width: 900px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        /* Left section for the product image */
        .product-image {
            flex: 1 1 300px;
            text-align: center;
        }
        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s;
        }
        .product-image img:hover {
            transform: scale(1.05);
        }
        /* Right section for product info */
        .product-info {
            flex: 1 1 400px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-info h2 {
            font-size: 2em;
            font-weight: bold;
            margin: 0 0 10px;
        }
        .product-info p.description {
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .product-info p.price {
            font-size: 1.5em;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        /* Dropdown styling for purchase option selection */
        .purchase-option-select {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        /* Container for purchase buttons, hidden initially */
        .purchase-buttons {
            display: none;
            margin-top: 20px;
        }
        .purchase-buttons a {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1em;
            margin: 5px;
        }
        .new-btn {
            background-color: #007bff;
            color: #fff;
        }
        .openbox-btn {
            background-color: #17a2b8;
            color: #fff;
        }
        .refurbished-btn {
            background-color: #28a745;
            color: #fff;
        }
        .new-btn:hover {
            background-color: #0056b3;
        }
        .openbox-btn:hover {
            background-color: #138496;
        }
        .refurbished-btn:hover {
            background-color: #218838;
        }
        /* Trust badges styling */
        .trust-badges {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .trust-badge {
            background-color: #f1f1f1;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9em;
            color: #555;
        }
        /* Reviews container styling */
        .reviews-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .reviews-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .review {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .review p {
            margin: 5px 0;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            const purchaseOption = document.getElementById("purchase-option");
            const purchaseButtonsDiv = document.getElementById("purchase-buttons");
            const newBtn = document.getElementById("new-btn");
            const openBoxBtn = document.getElementById("openbox-btn");
            const refurbishedBtn = document.getElementById("refurbished-btn");
            
            purchaseOption.addEventListener("change", function(){
                const selection = this.value;
                // Hide all buttons initially
                newBtn.style.display = "none";
                openBoxBtn.style.display = "none";
                refurbishedBtn.style.display = "none";
                
                if (selection === "new") {
                    newBtn.style.display = "inline-block";
                } else if (selection === "openbox") {
                    openBoxBtn.style.display = "inline-block";
                } else if (selection === "refurbished") {
                    refurbishedBtn.style.display = "inline-block";
                }
                
                // Show the buttons container if a valid selection is made, otherwise hide it
                if (selection) {
                    purchaseButtonsDiv.style.display = "block";
                } else {
                    purchaseButtonsDiv.style.display = "none";
                }
            });
        });
    </script>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <!-- Product Details Section -->
        <div class="product-details-container">
            <div class="product-image">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-info">
                <div>
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                <div>
                    <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
                    <!-- Purchase Option Dropdown -->
                    <select id="purchase-option" class="purchase-option-select">
                        <option value="">-- Select Purchase Option --</option>
                        <option value="new">New</option>
                        <option value="openbox">Open Box</option>
                        <option value="refurbished">Refurbished</option>
                    </select>
                    <!-- Purchase Buttons Container (initially hidden) -->
                    <div id="purchase-buttons" class="purchase-buttons">
                        <a id="new-btn" href="cart.php?add=<?php echo $product['id']; ?>" class="new-btn" style="display:none;">
                            <span class="material-symbols-outlined" style="vertical-align: middle;">shopping_cart_checkout</span> Add to Cart
                        </a>
                        <a id="openbox-btn" href="openbox_purchase.php?id=<?php echo $product['id']; ?>" class="openbox-btn" style="display:none;">
                            <span class="material-symbols-outlined" style="vertical-align: middle;">shopping_cart_checkout</span> Buy Open Box
                        </a>
                        <a id="refurbished-btn" href="refurbished_purchase.php?id=<?php echo $product['id']; ?>" class="refurbished-btn" style="display:none;">
                            <span class="material-symbols-outlined" style="vertical-align: middle;">shopping_cart_checkout</span> Buy Refurbished
                        </a>
                    </div>
                    <div class="trust-badges">
                        <div class="trust-badge">Secure Checkout</div>
                        <div class="trust-badge">Fast Shipping</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Reviews Section -->
        <?php
        // Query reviews for this specific product.
        $stmt = $pdo->prepare("SELECT r.rating, r.comment, r.created_at, m.username FROM reviews r JOIN members m ON r.member_id = m.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $stmt->execute([$productId]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="reviews-container">
            <h3>Customer Reviews</h3>
            <?php if (count($reviews) > 0): ?>
                <?php foreach ($reviews as $review): 
                    $rating = intval($review['rating']);
                    $filled = str_repeat("★", $rating);
                    $empty = str_repeat("☆", 5 - $rating);
                    $stars = $filled . $empty;
                ?>
                    <div class="review">
                        <p><strong><?php echo htmlspecialchars($review['username']); ?></strong> on <?php echo htmlspecialchars(date("Y-m-d", strtotime($review['created_at']))); ?></p>
                        <p><?php echo $stars; ?></p>
                        <p><?php echo htmlspecialchars($review['comment']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">There are no reviews yet. Be the first to leave one!</p>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
