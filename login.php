<?php
include 'connection.php';
session_start();

if (isset($_POST['submit-btn'])) {
    // Sanitize inputs
    $email = mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = mysqli_real_escape_string($conn, filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    // Fetch user by email
    $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'") or die('Query Failed');

    if (mysqli_num_rows($select_user) > 0) {
        $row = mysqli_fetch_assoc($select_user);

        // Verify password (hashed in DB)
        if (password_verify($password, $row['password'])) {
            if ($row['user_type'] === 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                header('location: admin_panel.php');
                exit();
            } else {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                header('location: index.php');
                exit();
            }
        } else {
            $message[] = 'Incorrect password!';
        }
    } else {
        $message[] = 'Email not found!';
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
        echo '<div class="message"><span>'.$msg.'</span>
              <i class="bx bx-x-circle" onclick="this.parentElement.remove()"></i>
              </div>';
    }
}
?>
<section class="form-container">
<form method="post">
    <h1>Login Now</h1>
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
