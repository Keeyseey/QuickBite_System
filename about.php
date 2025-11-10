<?php
session_start();
include 'connection.php';

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if user is logged in
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
    session_destroy();
    header('location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
    <title>QuickBite - About Us</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <div class="details">
            <h1>About Us</h1>
            <p>Learn more about QuickBite</p>
            <a href="index.php">Home</a><span> / About Us</span>
        </div>
    </div>
    <div class="line"></div>
    <div class="line2"></div>
    <div class="about-us">
        <div class="row">
            <div class="box">
                <div class="title">
                    <span>ABOUT OUR ONLINE STORE</span>
                    <h1>Welcome to QuickBite</h1>
                </div>
                <p>We are dedicated to providing you with the finest selection of fresh, quality food items delivered right to your doorstep. Our commitment to excellence ensures that every meal you prepare is special.</p>
            </div>
            <div class="img-box">
                <img src="img/about3.jpg" alt="About Us">
            </div>
        </div>
    </div>
    <div class="line3"></div>
    <div class="line4"></div>
    <div class="features">
        <div class="title">
            <h1>Complete Customer Satisfaction</h1>
            <span>Best Features</span>
        </div>
        <div class="row">
            <div class="box">
                <img src="img/icon2.png" alt="24/7 Support">
                <h4>24 X 7</h4>
                <p>Online Support 24/7</p>
            </div>
            <div class="box">
                <img src="img/icon1.png" alt="Money Back">
                <h4>Money Back Guarantee</h4>
                <p>100% Secure Payment</p>
            </div>
            <div class="box">
                <img src="img/icon0.png" alt="Gift Card">
                <h4>Special Gift Card</h4>
                <p>Give The Perfect Gift</p>
            </div>
            <div class="box">
                <img src="img/icon.png" alt="Shipping">
                <h4>Worldwide Shipping</h4>
                <p>On All Orders</p>
            </div>
        </div>
    </div>
    <div class="line"></div>
    <div class="line2"></div>
    <div class="team">
        <div class="title">
            <h1>Our Professional Team</h1>
            <span>Best Team</span>
        </div>
        <div class="row">
            <div class="box">
                <div class="img-box">
                    <img src="img/team.jpg" alt="Team Member">
                </div>
                <div class="detail">
                    <span>Finance Manager</span>
                    <h4>John Smith</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/te.jpg" alt="Team Member">
                </div>
                <div class="detail">
                    <span>Operations Manager</span>
                    <h4>Jane Doe</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/team1.jpg" alt="Team Member">
                </div>
                <div class="detail">
                    <span>Marketing Director</span>
                    <h4>Mike Johnson</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/team2.jpg" alt="Team Member">
                </div>
                <div class="detail">
                    <span>Customer Service Lead</span>
                    <h4>Sarah Williams</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
