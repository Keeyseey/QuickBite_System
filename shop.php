<?php
    include 'connection.php';
    $admin_id = $_SESSION['user_name'];

    if (!isset($admin_id)) {
        header('location:login.php');
    }

    if (!isset($_POST['logout'])) {
        session_destroy();
        header('location:login.php');
    }
    //adding food in wishlist
    if (isset($_POST['add_to_wishlist'])) {
        $food_id = $_POST['food_id'];
        $food_name = $_POST['food_name'];
        $food_price = $_POST['food_price'];
        $food_image = $_POST['food_image'];

        $wishlist_number = mysqli_query($conn, "SELECT * FROM 'wishlist' WHERE name = '$food_name' AND user_id='$user_id'") or die('Query Failed');
        $cart_num = mysqli_query($conn, "SELECT * FROM 'cart' WHERE name = '$food_name' AND user_id='$user_id'") or die('Query Failed');
        if (mysqli_num_rows($wishlist_number)>0) {
            $message[]='Food already exist in wishlist';
        } else if (mysqli_num_rows($wishlist_number)> 0) {
            $message[]= 'Food already exist in cart';
        }else{
            mysqli_query($conn,"INSERT INTO 'wishlist'('user_id,'fid','name','price','image') VALUES('$user_id','$food_id','$food_name','$food_price','$food_image')");
            $message[]= 'Food successfully added in your wishlist';
        }
    }
    //adding food in cart
    if (isset($_POST['add_to_cart'])) {
        $food_id = $_POST['food_id'];
        $food_name = $_POST['food_name'];
        $food_price = $_POST['food_price'];
        $food_image = $_POST['food_image'];
        $food_quantity = $_POST['food_quantity'];

        $cart_num = mysqli_query($conn, "SELECT * FROM 'cart' WHERE name = '$product_name' AND user_id='$user_id'") or die('Query Failed');
        if (mysqli_num_rows($cart_num)>0) {
            $message[]='Food already exist in cart';
        }else{
            mysqli_query($conn,"INSERT INTO 'cart'('user_id,'fid','name','price','quantity','image') VALUES('$user_id','$food_id','$food_name','$food_price','$food_quantity','$food_image')");
            $message[]= 'Food successfully added in your cart';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!------------------------bootstrap icon link------------------------------------------->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons!1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
    <title>Veggen - Home Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <div class="details">
            <h1>Our Shop</h1>
            <p>EYYY</p>
            <a href="index.php">Home</a><span>About us</span>
        </div> 
    </div>
    <div class="line"></div>
    <!------------------------About us------------------------------------------->
    <section class="shop">
        <h1 class="title">Shop Best Sellers</h1>
        
        <?php
            if (isset($message)){
                foreach ($message as $message) {
                    echo'
                        <div class="message">
                            <span>'.$message.'</span>
                            <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                        </div>
                    ';
                }
            }
        ?>
        <div class="box-container">
            <?php
                $select_foods = mysqli_query($conn,"SELECT * FROM 'products'") or die('Query Failed');
                if (mysqli_num_rows($select_foods)>0) {
                    while ($fetch_foods = mysqli_fetch_assoc($select_foods)){
                            
            ?>
            <form method="post" class="box">
                <img src="image/<?php echo $fetch_foods['image']; ?>">
                <div class="price">$<?php echo $fetch_foods['price']; ?></div>
                <div class="name"><?php echo $fetch_foods['name']; ?></div>
                <input type="hidden" name="food_id" value="<?php echo $fetch_foods['id']; ?>">
                <input type="hidden" name="food_name" value="<?php echo $fetch_foods['name']; ?>">
                <input type="hidden" name="food_price" value="<?php echo $fetch_foods['price']; ?>">
                <input type="hidden" name="food_quantity" value="1" min="1">
                <input type="hidden" name="food_image" value="<?php echo $fetch_foods['image']; ?>">
                <div class="icon">
                    <a href="view_page.php?pid=<?php echo $fetch_foods['id']; ?>" class="bi bi-eye-fill"></a>
                    <button type="submit" name="add_to_wishlist" class="bi bi-heart"></button>
                    <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                </div>
            </form>
            
            <?php
                    }
                }else{
                    echo '<p class="empty"> No foods added yet!</p>';
                }
            ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>