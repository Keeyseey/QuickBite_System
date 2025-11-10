<?php
include 'connection.php';

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_POST['submit-btn'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message[] = 'Invalid request. Please try again.';
    } else {
        $name = mysqli_real_escape_string($conn, filter_var($_POST['name'], FILTER_SANITIZE_STRING));
        $email = mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];

        $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

        if ($select_user && mysqli_num_rows($select_user) > 0) {
            $message[] = 'User already exists';
        } else {
            if ($password !== $cpassword) {
                $message[] = 'Passwords do not match';
            } else {
                if (strlen($password) < 8) {
                    $message[] = 'Password must be at least 8 characters long';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $insert_query = mysqli_query($conn, "INSERT INTO users (name, email, password, user_type) VALUES ('$name', '$email', '$hashed_password', 'user')");

                    if ($insert_query) {
                        $message[] = 'Registered successfully';
                        header('location: login.php');
                        exit();
                    } else {
                        $message[] = 'Registration failed. Please try again.';
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Register Page</title>
</head>
<body>
    <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="message">
                        <span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
                        <i class="bx bx-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>';
            }
        }
    ?>
    <section class="form-container">
        <form method="post">
            <h1>Register Now</h1>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="text" name="name" placeholder="Enter name" required>
            <input type="email" name="email" placeholder="Enter email" required>
            <input type="password" name="password" placeholder="Enter password (min 8 characters)" required minlength="8">
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
            <input type="submit" name="submit-btn" value="Register Now" class="btn">
            <p>Already have an account? <a href="login.php">Login Now</a></p>
        </form>
    </section>
</body>
</html>
