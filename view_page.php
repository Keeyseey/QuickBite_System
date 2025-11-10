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

$user_id = intval($_SESSION['user_id']);
$message = [];

// Handle logout
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
        $message[] = 'Invalid request';
    } else {
        $food_id = intval($_POST['food_id']);
        $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
        $food_price = floatval($_POST['food_price']);
        $food_image = mysqli_real_escape_string($conn, $_POST['food_image']);

        $wishlist_check = mysqli_query($conn, "SELECT * FROM wishlist WHERE fid = $food_id AND user_id = $user_id");
        $cart_check = mysqli_query($conn, "SELECT * FROM cart WHERE fid = $food_id AND user_id = $user_id");

        if ($wishlist_check && mysqli_num_rows($wishlist_check) > 0) {
            $message[] = 'Food already exists in wishlist';
        } elseif ($cart_check && mysqli_num_rows($cart_check) > 0) {
            $message[] = 'Food already exists in cart';
        } else {
            mysqli_query($conn, "INSERT INTO wishlist (user_id, fid, name, price, image) VALUES ($user_id, $food_id, '$food_name', $food_price, '$food_image')");
            $message[] = 'Food successfully added to your wishlist';
        }
    }
}

// Adding food to cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message[] = 'Invalid request';
    } else {
        $food_id = intval($_POST['food_id']);
        $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
        $food_price = floatval($_POST['food_price']);
        $food_image = mysqli_real_escape_string($conn, $_POST['food_image']);
        $food_quantity = intval($_POST['food_quantity']);

        $cart_check = mysqli_query($conn, "SELECT * FROM cart WHERE fid = $food_id AND user_id = $user_id");

        if ($cart_check && mysqli_num_rows($cart_check) > 0) {
            $message[] = 'Food already exists in cart';
        } else {
            mysqli_query($conn, "INSERT INTO cart (user_id, fid, name, price, quantity, image) VALUES ($user_id, $food_id, '$food_name', $food_price, $food_quantity, '$food_image')");
            $message[] = 'Food successfully added to your cart';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
    <title>QuickBite - Product Detail</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <div class="details">
            <h1>Product Detail</h1>
            <p>View product information</p>
            <a href="index.php">Home</a><span> / Product</span>
        </div>
    </div>
    <div class="line"></div>
    <section class="view_page">
        <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '<div class="message">
                        <span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>';
                }
            }
        ?>
        <?php
            if (isset($_GET['fid'])) {
                $fid = intval($_GET['fid']);
                $select_products = mysqli_query($conn, "SELECT * FROM foods WHERE id = $fid");
                if ($select_products && mysqli_num_rows($select_products) > 0) {
                    $fetch_foods = mysqli_fetch_assoc($select_products);
        ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <img src="image/<?php echo htmlspecialchars($fetch_foods['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Food Image">
            <div class="detail">
                <div class="price">$<?php echo number_format($fetch_foods['price'], 2); ?></div>
                <div class="name"><?php echo htmlspecialchars($fetch_foods['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="detail"><?php echo htmlspecialchars($fetch_foods['food_detail'], ENT_QUOTES, 'UTF-8'); ?></div>
                <input type="hidden" name="food_id" value="<?php echo $fetch_foods['id']; ?>">
                <input type="hidden" name="food_name" value="<?php echo htmlspecialchars($fetch_foods['name'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="food_price" value="<?php echo $fetch_foods['price']; ?>">
                <input type="hidden" name="food_image" value="<?php echo htmlspecialchars($fetch_foods['image'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="icon">
                    <button type="submit" name="add_to_wishlist" class="bi bi-heart"></button>
                    <input type="number" name="food_quantity" value="1" min="1" class="quantity">
                    <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                </div>
            </div>
        </form>
        <?php
                } else {
                    echo '<p class="empty">Product not found!</p>';
                }
            } else {
                echo '<p class="empty">No product selected!</p>';
            }
        ?>
    </section>

    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
