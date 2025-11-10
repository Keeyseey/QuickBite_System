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

// Delete user from database
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $delete_id");
    $message[] = 'User has been removed';
    header('location: admin_user.php');
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
    <title>Admin Panel - Users</title>
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
    <section class="message-container">
        <h1 class="title">Total User Accounts</h1>
        <div class="box-container">
            <?php
                $select_users = mysqli_query($conn, "SELECT * FROM users");
                if ($select_users && mysqli_num_rows($select_users) > 0) {
                    while ($fetch_users = mysqli_fetch_assoc($select_users)) {
            ?>
            <div class="box">
                <p>User ID: <span><?php echo htmlspecialchars($fetch_users['id'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Name: <span><?php echo htmlspecialchars($fetch_users['name'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Email: <span><?php echo htmlspecialchars($fetch_users['email'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>User Type: <span style="color:<?php if($fetch_users['user_type'] == 'admin') { echo 'orange'; } ?>"><?php echo htmlspecialchars($fetch_users['user_type'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <a href="admin_user.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
            </div>
            <?php
                    }
                } else {
                    echo '<div class="empty"><p>No users found!</p></div>';
                }
            ?>
        </div>
    </section>
    <div class="line"></div>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
