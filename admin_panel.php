<?php
include 'connection.php';

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
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
                $select_pendings = mysqli_query($conn, "SELECT * FROM orders WHERE payment_status = 'pending'");
                if ($select_pendings) {
                    while ($fetch_pending = mysqli_fetch_assoc($select_pendings)) {
                        $total_pendings += floatval($fetch_pending['total_price']);
                    }
                }
            ?>
            <h3>$<?php echo number_format($total_pendings, 2); ?></h3>
            <p>Total Pendings</p>
        </div>

        <div class="box">
            <?php
                $total_completes = 0;
                $select_completes = mysqli_query($conn, "SELECT * FROM orders WHERE payment_status = 'complete'");
                if ($select_completes) {
                    while ($fetch_completes = mysqli_fetch_assoc($select_completes)) {
                        $total_completes += floatval($fetch_completes['total_price']);
                    }
                }
            ?>
            <h3>$<?php echo number_format($total_completes, 2); ?></h3>
            <p>Total Completes</p>
        </div>

        <div class="box">
            <?php
                $select_orders = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
                $order_result = mysqli_fetch_assoc($select_orders);
                $num_of_orders = $order_result['count'];
            ?>
            <h3><?php echo $num_of_orders; ?></h3>
            <p>Orders Placed</p>
        </div>

        <div class="box">
            <?php
                $select_foods = mysqli_query($conn, "SELECT COUNT(*) as count FROM foods");
                $food_result = mysqli_fetch_assoc($select_foods);
                $num_of_foods = $food_result['count'];
            ?>
            <h3><?php echo $num_of_foods; ?></h3>
            <p>Food Items Added</p>
        </div>

        <div class="box">
            <?php
                $select_users = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE user_type = 'user'");
                $user_result = mysqli_fetch_assoc($select_users);
                $num_of_users = $user_result['count'];
            ?>
            <h3><?php echo $num_of_users; ?></h3>
            <p>Total Normal Users</p>
        </div>

        <div class="box">
            <?php
                $select_admin = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE user_type = 'admin'");
                $admin_result = mysqli_fetch_assoc($select_admin);
                $num_of_admin = $admin_result['count'];
            ?>
            <h3><?php echo $num_of_admin; ?></h3>
            <p>Total Admin</p>
        </div>

        <div class="box">
            <?php
                $select_message = mysqli_query($conn, "SELECT COUNT(*) as count FROM message");
                $message_result = mysqli_fetch_assoc($select_message);
                $num_of_message = $message_result['count'];
            ?>
            <h3><?php echo $num_of_message; ?></h3>
            <p>New Messages</p>
        </div>
    </div>
</section>
<script type="text/javascript" src="script.js"></script>
</body>
</html>
