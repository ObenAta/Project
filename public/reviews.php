<?php
session_start();
require_once 'includes/config.php';

// Ensure the member is logged in
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true) {
    echo "<script>
         alert('You must be logged in to leave a review.');
         window.location.href = 'membership.php';
         </script>";
    exit;
}

// Determine the product ID from either order_id or product_id
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // If order_id is provided, get product_id from order_items table.
    if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
        $order_id = intval($_GET['order_id']);
        // Assuming each order contains only one product:
        $stmt = $pdo->prepare("SELECT product_id FROM order_items WHERE order_id = ? LIMIT 1");
        $stmt->execute([$order_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            echo "No product found for this order.";
            exit;
        }
        $product_id = intval($row['product_id']);
    } elseif (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);
    } else {
        echo "Product ID is missing or invalid.";
        exit;
    }
} else { // POST
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        echo "Product ID is missing in the form submission.";
        exit;
    }
    $product_id = intval($_POST['product_id']);
}

$review_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);
    $member_id = intval($_SESSION['member_id']);
    
    // Basic validation
    if ($rating < 1 || $rating > 5) {
        $review_message = "Please select a rating between 1 and 5 stars.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO reviews (member_id, product_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$member_id, $product_id, $rating, $comment])) {
            $review_message = "Thank you for your review!";
        } else {
            $review_message = "Error saving review. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave a Review</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Star Rating Styles using Unicode icons */
        .star-rating {
            display: inline-flex;
            font-size: 2rem;
        }
        .star {
            cursor: pointer;
            color: #ccc;
            transition: color 0.2s;
            user-select: none;
            padding: 5px;
        }
        .star.hovered,
        .star.selected {
            color: #FFD700; /* Gold color */
        }
        /* Review Container */
        .review-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .review-container textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-top: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
            font-size: 1rem;
        }
        .review-container button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
        }
        .review-container button:hover {
            background-color: #218838;
        }
        .review-message {
            margin-top: 20px;
            font-weight: bold;
            color: green;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const stars = document.querySelectorAll(".star");
            const ratingInput = document.getElementById("rating");
            let selectedRating = 0;
            
            stars.forEach((star, index) => {
                star.addEventListener("mouseover", function() {
                    fillStars(index + 1);
                });
                star.addEventListener("mouseout", function() {
                    fillStars(selectedRating);
                });
                star.addEventListener("click", function() {
                    selectedRating = index + 1;
                    ratingInput.value = selectedRating;
                    fillStars(selectedRating);
                });
            });
            
            function fillStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.innerText = "★"; // filled star
                        star.classList.add("selected");
                    } else {
                        star.innerText = "☆"; // empty star
                        star.classList.remove("selected");
                    }
                });
            }
        });
    </script>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="review-container">
            <h2>Leave a Review</h2>
            <?php if ($review_message): ?>
                <p class="review-message"><?php echo htmlspecialchars($review_message); ?></p>
            <?php endif; ?>
            <form action="reviews.php" method="post">
                <!-- Hidden field to store the product ID -->
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                <div class="star-rating">
                    <span class="star" data-value="1">☆</span>
                    <span class="star" data-value="2">☆</span>
                    <span class="star" data-value="3">☆</span>
                    <span class="star" data-value="4">☆</span>
                    <span class="star" data-value="5">☆</span>
                </div>
                <!-- Hidden input to store the selected rating -->
                <input type="hidden" name="rating" id="rating" value="0">
                <textarea name="comment" placeholder="Leave your review here..." required></textarea>
                <button type="submit">Submit Review</button>
            </form>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
