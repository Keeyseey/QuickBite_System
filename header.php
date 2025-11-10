<?php
if (!isset($conn)) {
    include 'connection.php';
}

// Initialize CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $user_id = 0;
} else {
    $user_id = intval($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>QuickBite</title>
</head>
<body>
<header class="header">
    <div class="flex">
        <a href="index.php" class="logo"><img src="img/logo.png" alt="QuickBite Logo"></a>

        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="shop.php">Shop</a>
            <a href="order.php">Order</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="icons">
            <i class="bi bi-person" id="user-btn"></i>

            <?php if ($user_id > 0): ?>
                <!-- Wishlist Count -->
                <?php
                    $select_wishlist = mysqli_query($conn, "SELECT COUNT(*) as count FROM wishlist WHERE user_id = $user_id");
                    $wishlist_result = mysqli_fetch_assoc($select_wishlist);
                    $wishlist_num_rows = $wishlist_result['count'];
                ?>
                <a href="wishlist.php"><i class="bi bi-heart"></i><sup><?php echo $wishlist_num_rows; ?></sup></a>

                <!-- Cart Count -->
                <?php
                    $select_cart = mysqli_query($conn, "SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id");
                    $cart_result = mysqli_fetch_assoc($select_cart);
                    $cart_num_rows = $cart_result['count'];
                ?>
                <a href="cart.php"><i class="bi bi-cart"></i><sup><?php echo $cart_num_rows; ?></sup></a>
            <?php else: ?>
                <a href="login.php"><i class="bi bi-heart"></i><sup>0</sup></a>
                <a href="login.php"><i class="bi bi-cart"></i><sup>0</sup></a>
            <?php endif; ?>

            <i class="bi bi-list" id="menu-btn"></i>
        </div>

        <div class="user-box">
            <?php if (isset($_SESSION['user_name'])): ?>
                <p>Username: <span><?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Email: <span><?php echo htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" name="logout" class="logout-btn">Log out</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Please login</a></p>
            <?php endif; ?>
        </div>
    </div>
</header>
</body>
</html>
