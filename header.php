<?php
if(!isset($conn)){ 
    include 'connection.php'; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>QuickBite</title>
</head>
<body>
<header class="header">
    <div class="flex">
        <a href="index.php" class="logo"><img src="img/logo.png"></a>

        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="shop.php">Shop</a>
            <a href="order.php">Order</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="icons">
            <i class="bi bi-person" id="user-btn"></i>

            <!-- Wishlist Count -->
            <?php
                $select_wishlist = mysqli_query($conn,"SELECT * FROM wishlist WHERE user_id = '$user_id'") or die('Query Failed');
                $wishlist_num_rows = mysqli_num_rows($select_wishlist);
            ?>
            <a href="wishlist.php"><i class="bi bi-heart"></i><sup><?php echo $wishlist_num_rows; ?></sup></a>

            <!-- Cart Count -->
            <?php
                $select_cart = mysqli_query($conn,"SELECT * FROM cart WHERE user_id = '$user_id'") or die('Query Failed');
                $cart_num_rows = mysqli_num_rows($select_cart);
            ?>
            <a href="cart.php"><i class="bi bi-cart"></i><sup><?php echo $cart_num_rows; ?></sup></a>

            <i class="bi bi-list" id="menu-btn"></i>
        </div>

        <div class="user-box">
            <p>Username: <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>Email: <span><?php echo $_SESSION['user_email']; ?></span></p>

            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" name="logout" class="logout-btn">Log out</button>
            </form>
        </div>
    </div>
</header>
