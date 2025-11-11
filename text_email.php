<?php
// ✅ Make sure these paths are correct and exist in your computer
require 'C:/xampp/htdocs/QuickBite/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/QuickBite/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/QuickBite/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // ✅ SMTP Server Settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // ⚠️ Replace these with your actual Gmail and App Password
    $mail->Username = 'your_gmail@gmail.com';
    $mail->Password = 'your_app_password'; // Use your Google App Password, not your normal password!

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // ✅ Sender and Recipient
    $mail->setFrom('your_gmail@gmail.com', 'QuickBite Support');
    $mail->addAddress('your_gmail@gmail.com'); // You can change this to any test email

    // ✅ Email Content
    $mail->isHTML(true);
    $mail->Subject = 'PHPMailer Test Email';
    $mail->Body    = '<h3>✅ PHPMailer is working!</h3><p>If you see this, your setup is correct.</p>';

    $mail->send();
    echo '✅ Message has been sent successfully!';
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
