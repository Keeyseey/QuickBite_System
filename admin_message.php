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

// Delete message from database
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM message WHERE id = $delete_id");
    header('location: admin_message.php');
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
    <title>Admin Panel - Messages</title>
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
        <h1 class="title">Unread Messages</h1>
        <div class="box-container">
            <?php
                $select_message = mysqli_query($conn, "SELECT * FROM message");
                if ($select_message && mysqli_num_rows($select_message) > 0) {
                    while ($fetch_message = mysqli_fetch_assoc($select_message)) {
            ?>
            <div class="box">
                <p>User ID: <span><?php echo htmlspecialchars($fetch_message['id'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Name: <span><?php echo htmlspecialchars($fetch_message['name'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p>Email: <span><?php echo htmlspecialchars($fetch_message['email'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                <p><?php echo htmlspecialchars($fetch_message['message'], ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="admin_message.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');">Delete</a>
            </div>
            <?php
                    }
                } else {
                    echo '<div class="empty"><p>No messages yet!</p></div>';
                }
            ?>
        </div>
    </section>
    <div class="line"></div>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
