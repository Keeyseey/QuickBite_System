<?php
require 'C:\xampp\htdocs\QuickBite\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\QuickBite\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\QuickBite\PHPMailer\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Connect to your database
$conn = new mysqli('localhost', 'root', '', 'quickbite_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(16));
        $conn->query("UPDATE users SET reset_token='$token' WHERE email='$email'");

        // Create reset link
        $reset_link = "http://localhost/QuickBite/reset_password.php?token=$token";

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'fermilankylaclaire@gmail.com';
            $mail->Password = 'dxtwjvrtjqpygupy'; // Use Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your_gmail@gmail.com', 'QuickBite Support');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
                <h3>Reset Your QuickBite Password</h3>
                <p>Click the link below to reset your password:</p>
                <a href='$reset_link'>$reset_link</a>
                <p>This link will expire soon for your security.</p>
            ";

            $mail->send();
            echo "<script>alert('Reset link sent to your email.'); window.location='login.php';</script>";
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>alert('Email not found.'); window.location='forgot_password.php';</script>";
    }
}
?>

<form method="POST">
    <h2>Forgot Password</h2>
    <label>Email:</label>
    <input type="email" name="email" required>
    <button type="submit" name="submit">Send Reset Link</button>
</form>
