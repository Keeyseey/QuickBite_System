<?php
    include 'connection.php';

    if (isset($_POST['submit-btn'])) {
        // Sanitize and escape inputs properly
        $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $name = mysqli_real_escape_string($conn, $filter_name);
    
        $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $email = mysqli_real_escape_string($conn, $filter_email);
        
        $filter_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $password = mysqli_real_escape_string($conn, $filter_password);
    
        $filter_cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_STRING);
        $cpassword = mysqli_real_escape_string($conn, $filter_cpassword);
    
        $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query Failed');

        if (mysqli_num_rows($select_user) > 0) {
            $message[] = 'User already exists';
        } else {
            if ($password != $cpassword) {
                $message[] = 'Passwords do not match';
            } else {
                // Optional: hash the password for security
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                mysqli_query($conn, "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('$name', '$email', '$hashed_password')") or die('Query Failed');
                $message[] = 'Registered successfully';
                header('location: login.php');
                exit(); // always exit after header redirect
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--box icon link-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Register page</title>
</head>
<body>
    <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '
                    <div class="message">
                        <span>'.$msg.'</span>
                        <i class="bx bx-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>
                ';
            }
        }
    ?>
    <section class="form-container">
        <form method="post">
            <h1>Register Now</h1>
            <input type="text" name="name" placeholder="Enter name" required>
            <input type="email" name="email" placeholder="Enter email" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
            <input type="submit" name="submit-btn" value="Register Now" class="btn">
            <p>Already have an account? <a href="login.php">Login Now</a></p>    
        </form>
    </section> 
</body>
</html>
