<?php
session_start();
require_once 'includes/check_member_status.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Logout icon font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=logout" />
    <style>
        /* Container for the membership page */
        .membership-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 900px;
            margin: 40px auto;
            gap: 20px;
            padding: 0 20px;
        }

        /* Common box styling */
        .login-form, .membership-info, .new-5080-series {
            flex: 1 1 400px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .login-form h2, .membership-info h2, .new-5080-series h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Sign In form styling */
        .login-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .login-form form input[type="text"],
        .login-form form input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            width: 100%;
            box-sizing: border-box;
        }
        .login-form form button {
            padding: 8px 12px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 0.9em;
            cursor: pointer;
            width: auto;
            align-self: center;
        }
        .login-form form button:hover {
            background-color: #0056b3;
        }

        /* Membership info styling */
        .membership-info p, .membership-info ul {
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .membership-info ul {
            list-style: disc;
            margin-left: 20px;
        }
        .membership-info a.create-account {
            display: block;
            padding: 12px 25px;
            background-color: #28a745;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1em;
            margin: 10px auto 0;
            text-align: center;
            width: fit-content;
        }
        .membership-info a.create-account:hover {
            background-color: #218838;
        }

        /* Logout button styling */
        .logout-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            margin: 10px auto;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .logout-btn .logout-text {
            margin-left: 5px;
            margin-top: -2px; /* Adjust for vertical alignment */
        }

        /* RMA Section styling */
        .rma-section {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .rma-section h2 {
            margin-bottom: 20px;
        }
        .rma-section p, .rma-section ul {
            line-height: 1.6;
            margin-bottom: 15px;
            text-align: left;
        }
        .rma-section ul {
            list-style: disc;
            margin-left: 20px;
        }
        .rma-section a.rma-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1em;
            margin-top: 20px;
        }
        .rma-section a.rma-btn:hover {
            background-color: #0056b3;
        }

        /* Ensure the logout icon displays correctly */
        .material-symbols-outlined {
          font-variation-settings:
          'FILL' 0,
          'wght' 400,
          'GRAD' 0,
          'opsz' 24;
        }

        /* 5080 Series section */
        .new-5080-series video {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
        }

        /* Custom GPU Quote Container styling */
        .custom-quote-container {
            flex: 1 1 400px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .custom-quote-container p {
            line-height: 1.6;
            margin-bottom: 15px;
            text-align: left; /* Keep paragraphs left-aligned */
        }
        .custom-quote-container a.quote-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1em;
            margin-top: 20px;
        }
        .custom-quote-container a.quote-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <?php 
    // If there's a login error, display an alert and then clear the error.
    if(isset($_SESSION['login_error'])): ?>
        <script>
            alert("Incorrect username or password. Please check your credentials and try again.");
        </script>
    <?php unset($_SESSION['login_error']); endif; ?>

    <main>
        <div class="membership-container">
            <?php if(isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true): ?>
                <!-- If the member is logged in, display a Logout button -->
                <div style="width:100%; text-align:center;">
                    <a href="logout.php" class="logout-btn">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="logout-text">Logout</span>
                    </a>
                </div>

                <!-- Discover the new 5080 Series (Only visible when logged in) -->
                <div class="new-5080-series">
                    <h2>Discover the New 5080 Series</h2>
                    <p>Experience the latest in cutting-edge design and performance.</p>
                    <video controls>
                        <source src="videos/5080.mp4" type="video/mp4">
                        <!-- Fallback text if the browser does not support <video> -->
                        Your browser does not support the video tag.
                    </video>
                    <br>
                    <!-- Second video -->
                    <video controls>
                        <source src="videos/5080N.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <br>
                    <!-- Third video -->
                    <video controls>
                        <source src="videos/5080P.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- Custom GPU Quote Section (for logged in members), in its own container -->
                <div class="custom-quote-container">
                    <h2>Customize Your GPU Quote</h2>
                    <p>
                        As a valued member, you now have exclusive access to our GPU Quote Customizer.
                        Tailor your GPU purchase with a wide range of optional upgrades and add-ons to get a personalized quote.
                        Enjoy expert recommendations, competitive pricing, and flexible options to suit your needs.
                    </p>
                    <p>
                        Click the button below to start your quote and receive your personalized estimate via email.
                    </p>
                    <!-- Center the button -->
                    <div style="text-align:center;">
                        <a href="gpu_quote_customizer.php" class="quote-btn">Start Your Quote</a>
                    </div>
                </div>
                  <div class="custom-quote-container">
                      <h2>Get Your Financing Quote</h2>
                      <p>
                          As a valued member, you now have exclusive access to our Financing Quote Calculator.
                          Customize your payment plan with flexible options and competitive rates tailored to your needs.
                          Enjoy an instant, personalized financing estimate that makes purchasing your dream GPU easier.
                      </p>
                       <p>
                          Click the button below to calculate your monthly payments and receive your personalized financing quote via email.
                      </p>
                      <!-- Center the button -->
                     <div style="text-align:center;">
                        <a href="financing_quote.php" class="quote-btn">Get Your Financing Quote</a>
                     </div>
               </div>

            <?php else: ?>
                <!-- Sign In Form -->
                <div class="login-form">
                    <h2>Sign In</h2>
                    <form action="login_member.php" method="post">
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit">Sign In</button>
                    </form>
                </div>

                <!-- Membership Advantages (Only visible when NOT logged in) -->
                <div class="membership-info">
                    <h2>Benefits of Membership</h2>
                    <p>Join our community to enjoy exclusive benefits such as:</p>
                    <ul>
                        <li>Exclusive discounts and special offers</li>
                        <li>Faster checkout and order tracking</li>
                        <li>Personalized recommendations</li>
                        <li>Early access to new products</li>
                    </ul>
                    <p>Become a member today and start enjoying these advantages!</p>
                    <a href="create_account.php" class="create-account">Create an Account</a>
                </div>
            <?php endif; ?>
        </div>

        <?php if(isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true): ?>
        <!-- RMA Section (only visible to logged in members) -->
        <div class="rma-section">
            <h2>Return Merchandise Authorization (RMA) Request</h2>
            <p>Hi there!</p>
            <p>Are you experiencing issues with your recent purchase? Whether it’s not functioning as expected or something just doesn’t seem right, we’re here to help!</p>
            <p>Start your Return Merchandise Authorization (RMA) request by creating a ticket below. Our hassle-free RMA process ensures that you get the support you need quickly and efficiently.</p>
            <h3>How does the RMA process work?</h3>
            <ul>
                <li><strong>Submit a Ticket</strong> – Fill out a short form with your order details and a brief description of the issue.</li>
                <li><strong>Get a Response</strong> – Our support team will review your request and provide return instructions within 24-48 hours.</li>
                <li><strong>Ship Your Item</strong> – If approved, you'll receive a prepaid return label (for eligible products). Package the item securely and send it back.</li>
                <li><strong>Inspection &amp; Resolution</strong> – Once we receive your item, we’ll inspect it and process a replacement or refund within 5-7 business days.</li>
            </ul>
            <p>We value your satisfaction and are committed to making this process as smooth as possible. If you have any questions, don’t hesitate to reach out to our support team!</p>
            <a href="rma.php" class="rma-btn">Click to start your RMA request now</a>
        </div>
        <?php endif; ?>
        <p style="text-align:center;">
         Need help using this page?
        <a href="membershiphelp.html">Click here for instructions.</a>
        </p>

    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>


