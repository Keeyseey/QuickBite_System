<?php
session_start();
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

// Delete order from database
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM orders WHERE id = $delete_id");
    $message[] = 'Order has been deleted';
    header('location: admin_order.php');
    exit();
}

// Updating payment status
if (isset($_POST['update_order'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message[] = 'Invalid request';
    } else {
        $order_id = intval($_POST['order_id']);
        $update_payment = mysqli_real_escape_string($conn, $_POST['update_payment']);

        if (in_array($update_payment, ['pending', 'complete'])) {
            mysqli_query($conn, "UPDATE orders SET payment_status = '$update_payment' WHERE id = $order_id");
            $message[] = 'Payment status updated successfully';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Panel - Orders</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
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
    <div class="line4"></div>
    <section class="order-container">
        <h1 class="title">Total Orders Placed</h1>
        <div class="box-container">
            <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM orders");
                if ($select_orders && mysqli_num_rows($select_orders) > 0) {
                    while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
            ?>
            <div class="box">
                <p>User Name: <span><?php echo htmlspecialchars($fetch_orders['name'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>User ID: <span><?php echo htmlspecialchars($fetch_orders['user_id'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Placed On: <span><?php echo htmlspecialchars($fetch_orders['placed_on'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Number: <span><?php echo htmlspecialchars($fetch_orders['number'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Email: <span><?php echo htmlspecialchars($fetch_orders['email'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Total Price: <span>$<?php echo number_format($fetch_orders['total_price'], 2); ?></span></p>
                <p>Method: <span><?php echo htmlspecialchars($fetch_orders['method'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Address: <span><?php echo htmlspecialchars($fetch_orders['address'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Total Food: <span><?php echo htmlspecialchars($fetch_orders['total_foods'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                    <select name="update_payment">
                        <option disabled selected><?php echo htmlspecialchars($fetch_orders['payment_status'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <option value="pending">Pending</option>
                        <option value="complete">Complete</option>
                    </select>
                    <input type="submit" name="update_order" value="Update Payment" class="btn">
                </form>
                <a href="admin_order.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this order?');">Delete</a>
            </div>
            <?php
                    }
                } else {
                    echo '<div class="empty"><p>No orders placed yet!</p></div>';
                }
            ?>
        </div>
    </section>
    <div class="line"></div>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
