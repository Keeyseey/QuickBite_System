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
        $email = mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $password = $_POST['password'];

        $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

        if ($select_user && mysqli_num_rows($select_user) > 0) {
            $row = mysqli_fetch_assoc($select_user);

            if (password_verify($password, $row['password'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                if ($row['user_type'] === 'admin') {
                    $_SESSION['admin_name'] = $row['name'];
                    $_SESSION['admin_email'] = $row['email'];
                    $_SESSION['admin_id'] = $row['id'];
                    header('location: admin_panel.php');
                } else {
                    $_SESSION['user_name'] = $row['name'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['user_id'] = $row['id'];
                    header('location: index.php');
                }
                exit();
            } else {
                $message[] = 'Incorrect password!';
            }
        } else {
            $message[] = 'Email not found!';
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
<title>Login Page</title>
</head>
<body>
<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="message"><span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
              <i class="bx bx-x-circle" onclick="this.parentElement.remove()"></i>
              </div>';
    }
}
?>
<section class="form-container">
<form method="post">
    <h1>Login Now</h1>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
    <div class="input-field">
        <label>Email</label><br>
        <input type="email" name="email" placeholder="Enter email" required>
    </div>
    <div class="input-field">
        <label>Password</label><br>
        <input type="password" name="password" placeholder="Enter password" required>
    </div>
    <input type="submit" name="submit-btn" value="Login Now" class="btn">
    <p><a href="forgot_password.php">Forgot Password?</a></p>
    <p>Don't have an account? <a href="register.php">Register Now</a></p>
</form>
</section>
</body>
</html>
