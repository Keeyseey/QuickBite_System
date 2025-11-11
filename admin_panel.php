<?php
session_start();
include 'connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
    header('location: login.php');
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
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
<link rel="stylesheet" href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Admin Panel</title>
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="dashboard">
    <div class="box-container">
        <div class="box">
            <?php
                $total_pendings = 0;
                $select_pendings = mysqli_query($conn,"SELECT * FROM orders WHERE payment_status = 'pending'");
                while ($fetch_pending = mysqli_fetch_assoc($select_pendings)) {
                    $total_pendings += $fetch_pending['total_price'];
                }
            ?>
            <h3>$<?php echo $total_pendings; ?>/-</h3>
            <p>Total Pendings</p>
        </div>

        <div class="box">
            <?php
                $total_completes = 0;
                $select_completes = mysqli_query($conn,"SELECT * FROM orders WHERE payment_status = 'complete'");
                while ($fetch_completes = mysqli_fetch_assoc($select_completes)) {
                    $total_completes += $fetch_completes['total_price'];
                }
            ?>
            <h3>$<?php echo $total_completes; ?>/-</h3>
            <p>Total Completes</p>
        </div>

        <div class="box">
            <?php
                $select_orders = mysqli_query($conn,"SELECT * FROM orders");
                $num_of_orders = mysqli_num_rows($select_orders);
            ?>
            <h3><?php echo $num_of_orders; ?></h3>
            <p>Orders Placed</p>
        </div>

        <div class="box">
            <?php
                $select_foods = mysqli_query($conn,"SELECT * FROM foods");
                $num_of_foods = mysqli_num_rows($select_foods);
            ?>
            <h3><?php echo $num_of_foods; ?></h3>
            <p>Food Items Added</p>
        </div>

        <div class="box">
            <?php
                $select_users = mysqli_query($conn,"SELECT * FROM users WHERE user_type = 'user'");
                $num_of_users = mysqli_num_rows($select_users);
            ?>
            <h3><?php echo $num_of_users; ?></h3>
            <p>Total Normal Users</p>
        </div>

        <div class="box">
            <?php
                $select_admin = mysqli_query($conn,"SELECT * FROM users WHERE user_type = 'admin'");
                $num_of_admin = mysqli_num_rows($select_admin);
            ?>
            <h3><?php echo $num_of_admin; ?></h3>
            <p>Total Admin</p>
        </div>

        <div class="box">
            <?php
                $select_message = mysqli_query($conn,"SELECT * FROM message");
                $num_of_message = mysqli_num_rows($select_message);
            ?>
            <h3><?php echo $num_of_message; ?></h3>
            <p>New Messages</p>
        </div>
    </div>
</section>
<script type="text/javascript" src="script.js"></script>
</body>
</html>
