<?php
session_start();
include 'connection.php';

// Set Content Security Policy header (adjust as needed for your resources)
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://code.jquery.com https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' https://cdn.jsdelivr.net data:;");

// Initialize $message to avoid undefined variable notices
$message = [];

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Check if user is logged in and user_id is set and valid
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id']) || intval($_SESSION['user_id']) <= 0) {
    header('location: login.php');
    exit();
}

$user_id = intval($_SESSION['user_id']); // use for queries

// Note: session_regenerate_id(true) should be called on successful login in login.php

// Logout logic
if (isset($_POST['logout'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
    session_destroy();
    header('location: login.php');
    exit();
}

// Adding food to wishlist
if (isset($_POST['add_to_wishlist'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
    $food_id = isset($_POST['food_id']) ? intval($_POST['food_id']) : 0;
    $food_name = isset($_POST['food_name']) ? $_POST['food_name'] : '';
    $food_price = isset($_POST['food_price']) ? floatval($_POST['food_price']) : 0;
    $food_image = isset($_POST['food_image']) ? $_POST['food_image'] : '';

    // Duplicate check by fid (product id) instead of name
    $wishlist_stmt = mysqli_prepare($conn, "SELECT 1 FROM wishlist WHERE fid = ? AND user_id = ?");
    if (!$wishlist_stmt) die('Query Failed: ' . mysqli_error($conn));
    mysqli_stmt_bind_param($wishlist_stmt, 'ii', $food_id, $user_id);
    mysqli_stmt_execute($wishlist_stmt);
    mysqli_stmt_store_result($wishlist_stmt);

    $cart_stmt = mysqli_prepare($conn, "SELECT 1 FROM cart WHERE fid = ? AND user_id = ?");
    if (!$cart_stmt) die('Query Failed: ' . mysqli_error($conn));
    mysqli_stmt_bind_param($cart_stmt, 'ii', $food_id, $user_id);
    mysqli_stmt_execute($cart_stmt);
    mysqli_stmt_store_result($cart_stmt);

    if (mysqli_stmt_num_rows($wishlist_stmt) > 0) {
        $message[] = 'Food already exists in wishlist';
    } elseif (mysqli_stmt_num_rows($cart_stmt) > 0) {
        $message[] = 'Food already exists in cart';
    } else {
        $insert_stmt = mysqli_prepare($conn, "INSERT INTO wishlist (user_id, fid, name, price, image) VALUES (?, ?, ?, ?, ?)");
        if (!$insert_stmt) die('Query Failed: ' . mysqli_error($conn));
        mysqli_stmt_bind_param($insert_stmt, 'iisdis', $user_id, $food_id, $food_name, $food_price, $food_quantity, $food_image);
        mysqli_stmt_execute($insert_stmt);
        $message[] = 'Food successfully added to your wishlist';
        mysqli_stmt_close($insert_stmt);
    }
    mysqli_stmt_close($wishlist_stmt);
    mysqli_stmt_close($cart_stmt);
}

// Adding food to cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
    $food_id = isset($_POST['food_id']) ? intval($_POST['food_id']) : 0;
    $food_name = isset($_POST['food_name']) ? $_POST['food_name'] : '';
    $food_price = isset($_POST['food_price']) ? floatval($_POST['food_price']) : 0;
    $food_image = isset($_POST['food_image']) ? $_POST['food_image'] : '';
    $food_quantity = isset($_POST['food_quantity']) ? intval($_POST['food_quantity']) : 1;

    // Duplicate check by fid (product id) instead of name
    $cart_stmt = mysqli_prepare($conn, "SELECT 1 FROM cart WHERE fid = ? AND user_id = ?");
    if (!$cart_stmt) die('Query Failed: ' . mysqli_error($conn));
    mysqli_stmt_bind_param($cart_stmt, 'ii', $food_id, $user_id);
    mysqli_stmt_execute($cart_stmt);
    mysqli_stmt_store_result($cart_stmt);

    if (mysqli_stmt_num_rows($cart_stmt) > 0) {
        $message[] = 'Food already exists in cart';
    } else {
        $insert_stmt = mysqli_prepare($conn, "INSERT INTO cart (user_id, fid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$insert_stmt) die('Query Failed: ' . mysqli_error($conn));
        mysqli_stmt_bind_param($insert_stmt, 'iisdss', $user_id, $food_id, $food_name, $food_price, $food_quantity, $food_image);
        mysqli_stmt_execute($insert_stmt);
        $message[] = 'Food successfully added to your cart';
        mysqli_stmt_close($insert_stmt);
    }
    mysqli_stmt_close($cart_stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBite - Home Page</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Slick Slider CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <!-- Display messages -->
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message"><span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
                  <i class="bx bx-x-circle" onclick="this.parentElement.remove()"></i>
                  </div>';
        }
    }
    ?>

    <!-- Home Slider -->
    <div class="container-fluid">
        <div class="hero-slider">
            <div class="slider-item">
                <img src="img/slider.jpg" alt="Slider Image 1">
                <div class="slider-caption">
                    <span>Test The Quality</span>
                    <h1>Organic Premium <br>Honey</h1>
                    <p>Enjoy, sweet chuchuchu people of chuchuchu</p>
                    <a href="shop.php" class="btn">Order Now!</a>
                </div>
            </div>
            <div class="slider-item">
                <img src="img/slider2.jpg" alt="Slider Image 2">
                <div class="slider-caption">
                    <span>Test The Quality</span>
                    <h1>Organic Premium <br>Honey</h1>
                    <p>Enjoy, sweet chuchuchu people of chuchuchu</p>
                    <a href="shop.php" class="btn">Order Now!</a>
                </div>
            </div>
        </div>
        <div class="control">
            <i class="bi bi-chevron-left prev"></i>
            <i class="bi bi-chevron-right next"></i>
        </div>
    </div>

    <div class="line"></div>

    <!-- Services Section -->
    <div class="services">
        <div class="row">
            <div class="box">
                <img src="img/0.png" alt="">
                <div>
                    <h1>Free Delivery Fast</h1>
                    <p>chuchuchu</p>
                </div>
            </div>
            <div class="box">
                <img src="img/0.png" alt="">
                <div>
                    <h1>Money Back & Guarantee</h1>
                    <p>chuchuchu</p>
                </div>
            </div>
            <div class="box">
                <img src="img/0.png" alt="">
                <div>
                    <h1>Online Support 24/7</h1>
                    <p>chuchuchu</p>
                </div>
            </div>
        </div>
    </div>

    <div class="line2"></div>

    <!-- Story Section -->
    <div class="story">
        <div class="row">
            <div class="box">
                <span>Our Story</span>
                <h1>CHUCHUCHU</h1>
                <p>CHUCHUCHU</p>
                <a href="shop.php" class="btn">Order Now!</a>
            </div>
            <div class="box">
                <img src="img/8.png" alt="">
            </div>
        </div>
    </div>

    <div class="line3"></div>

    <!-- Testimonial Section -->
    <div class="line4"></div>
    <div class="testimonial-fluid">
        <h1 class="title">What Our Customer Say</h1>
        <div class="testimonial-slider">
            <div class="testimonial-item">
                <img src="img/3.jpg" alt="">
                <div class="testimonial-caption">
                    <span>Test the Quality</span>
                    <h1>Organic Premium</h1>
                    <p>CHUCHUCHU</p>
                </div>
            </div>
            <div class="testimonial-item">
                <img src="img/4.jpg" alt="">
                <div class="testimonial-caption">
                    <span>Test the Quality</span>
                    <h1>Organic Premium</h1>
                    <p>CHUCHUCHU</p>
                </div>
            </div>
            <div class="testimonial-item">
                <img src="img/profile.jpg" alt="">
                <div class="testimonial-caption">
                    <span>Test the Quality</span>
                    <h1>Organic Premium</h1>
                    <p>CHUCHUCHU</p>
                </div>
            </div>
        </div>
        <div class="control">
            <i class="bi bi-chevron-left prev1"></i>
            <i class="bi bi-chevron-right next1"></i>
        </div>
    </div>

    <div class="line2"></div>

    <!-- Discover Section -->
    <div class="discover">
        <div class="detail">
            <h1 class="title">Organic chuchu</h1>
            <span>Buy Now And Save 30% Off!</span>
            <p>Chuchuchuchu</p>
            <a href="shop.php" class="btn">Order Now</a>
        </div>
        <div class="img-box">
            <img src="img/13.png" alt="">
        </div>
    </div>

    <div class="line3"></div>

    <!-- Home Shop Section -->
    <?php include 'homeshop.php'; ?>

    <div class="line2"></div>

    <!-- Newsletter -->
    <div class="newsletter">
        <h1 class="title">Join our newsletter</h1>
        <p>Get 15% off your next order. chuchuchu</p>
        <form method="post" action="">
            <input type="text" placeholder="Your Email Address..." name="newsletter_email">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit">Subscribe now</button>
        </form>
    </div>

    <div class="line3"></div>

    <!-- Clients Section -->
    <div class="client">
        <div class="box">
            <img src="img/client0.png" alt="">
        </div>
        <div class="box">
            <img src="img/client1.png" alt="">
        </div>
        <div class="box">
            <img src="img/client2.png" alt="">
        </div>
        <div class="box">
            <img src="img/client3.png" alt="">
        </div>
        <div class="box">
            <img src="img/client.png" alt="">
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="script2.js"></script>
    <script src="script.js"></script>
</body>
</html>
