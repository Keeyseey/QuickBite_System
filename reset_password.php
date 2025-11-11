<?php
include 'connection.php';
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'");

    if (mysqli_num_rows($query) > 0) {
        if (isset($_POST['update-password'])) {
            $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
            $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

            if ($new_password === $confirm_password) {
                // Hash password for security
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password='$hashed', reset_token=NULL WHERE reset_token='$token'");
                echo "<script>alert('Password successfully updated!'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Passwords do not match!');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid or expired token!'); window.location='forgot_password.php';</script>";
    }
} else {
    echo "<script>alert('No token provided!'); window.location='forgot_password.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="form-container">
        <form method="POST">
            <h1>Reset Password</h1>
            <div class="input-field">
                <label>New Password</label><br>
                <input type="password" name="new_password" placeholder="Enter new password" required>
            </div>
            <div class="input-field">
                <label>Confirm Password</label><br>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            <input type="submit" name="update-password" value="Update Password" class="btn">
        </form>
    </section>
</body>
</html>
