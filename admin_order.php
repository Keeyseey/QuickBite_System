<?php
    include 'connection.php';
    $admin_id = $_SESSION['admin_name'];

    if (!isset($admin_id)) {
        header('location:login.php');
    }

    if (!isset($_POST['logout'])) {
        session_destroy();
        header('location:login.php');
    }
    

    //delete foods from database
    if(isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        
        mysqli_query($conn, "DELETE FROM 'orders' WHERE id = '$delete_id'") or die('Query Failed');
        $message[] = 'User has been removed';
        header('location:admin_order.php');
    }
    
    //updating payment status

    if(isset($_POST['update_order'])) {
        $order_id = $_POST['order_id'];
        $update_payment = $_POST['update_payment'];
        
        mysqli_query($conn,"UPDATE 'orders' SET payment_status ='$update_payment' WHERE id='$order_id'") or die('Query Failed');

    }
?>
<style type="text/css">
    <?php
        include 'style.css';
    ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--box icon link-->
    <link rel="stylesheet" href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Panel</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '
                    <div class="message">
                    <span>' .$message. '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i> 
                    </div>
                ';
            }
        }
    ?>
    <div class="line4"></div>
    <section class="order-container">
        <h1 class="title">Total Order Placed</h1>
        <div class="box-container">
            <?php
                $select_orders = mysqli_query($conn,"SELECT * FROM 'orders'") or die('Query Failed');
                if (mysqli_num_rows($select_orders) > 0) {
                    while ($fetch_orders = mysqli_fetch_assoc($select_users)) {

            ?>
            <div class="box">
                <p> User Name: <span><?php echo $fetch_orders['name']; ?></span></p>
                <p> User ID:: <span><?php echo $fetch_orders['user_id']; ?></span></p>
                <p> Placed On: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                <p> Number: <span><?php echo $fetch_orders['number']; ?></span></p>
                <p> Email: <span><?php echo $fetch_orders['email']; ?></span></p>
                <p> Total Price: <span><?php echo $fetch_orders['total_price']; ?></span></p>
                <p> Method: <span><?php echo $fetch_orders['method']; ?></span></p>
                <p> Address: <span><?php echo $fetch_orders['address']; ?></span></p>
                <p> Total Food: <span><?php echo $fetch_orders['total_foods']; ?></span></p>
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                    <select name="update_payment">
                        <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
                        <option value="pending">Pending</option>
                        <option value="complete">Complete</option>
                    </select>
                    <input type="submit" name="update_order" value="Update Payment" class="btn">
                </form>
                <a href="admin_order.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm ('Delete this order?');">Delete</a>
            </div>
            <?php
                    }
                }else{
                        echo '
                        <div class="empty">
                            <p>No food added yet!</p>
                        </div>
                        ';
                    }
            ?>
        </div>

    </section>
    <div class="line"></div>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>