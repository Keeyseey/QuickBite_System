<?php
    include 'connection.php';
    $admin_id = $_SESSION['user_name'];

    if (!isset($admin_id)) {
        header('location:login.php');
    }

    if (!isset($_POST['logout'])) {
        session_destroy();
        header('location:login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!------------------------bootstrap icon link------------------------------------------->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons!1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
    <title>Veggen - Home Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <div class="details">
            <h1>About us</h1>
            <p>EYYY</p>
            <a href="index.php">Home</a><span>About us</span>
        </div> 
    </div>
    <div class="line"></div>
    <!------------------------About us------------------------------------------->
    <div class="line2"></div>
    <div class="about-us">
        <div class="row">
            <div class="box">
                <div class="title">
                    <span>ABOUT OUR ONLINE STORE</span>
                    <h1>Hi, How are you to find out</h1>
                </div>
                <p>CHUCUHCUCHUCHU</p>
            </div>
            <div class="img-box">
                <img src="img/about3.jpg">
            </div>
        </div>
    </div>
    <div class="line3"></div>
    <!------------------------features------------------------------------------->
    <div class="line4"></div>
    <div class="features">
        <div class="title">
            <h1>Complete Customer Ideas</h1>
            <span>Best Features</span>
        </div>
        <div class="row">
            <div class="box">
                <img src="img/icon2.png">
                <h4>24 X 7</h4>
                <p>Online Support 27/7</p>
            </div>
            <div class="box">
                <img src="img/icon1.png">
                <h4>Money Back Guarantee</h4>
                <p>100% Secure Payment</p>
            </div>
            <div class="box">
                <img src="img/icon0.png">
                <h4>Special Gift Card</h4>
                <p>Give The Perfect Gift</p>
            </div>
            <div class="box">
                <img src="img/icon.png">
                <h4>Worldwide Shipping</h4>
                <p>On Order Over chuchuchu</p>
            </div>
        </div>
    </div>
    <div class="line"></div>
    <!------------------------team section------------------------------------------>
    <div class="line2"></div>
    <div class="team">
        <div class="title">
            <h1>Our Workable Team</h1>
            <span>Best Team</span>
        </div>
        <div class="row">
            <div class="box">
                <div class="img-box">
                    <img src="img/team.jpg">
                </div>
                <div class="detail">
                    <span>Finance Manager</span>
                    <h4>Carl Jibney</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/te.jpg">
                </div>
                <div class="detail">
                    <span>Finance Manager</span>
                    <h4>Carl Jibney</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/team1.jpg">
                </div>
                <div class="detail">
                    <span>Finance Manager</span>
                    <h4>Carl Jibney</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/team2.jpg">
                </div>
                <div class="detail">
                    <span>Finance Manager</span>
                    <h4>Carl Jibney</h4>
                    <div class="icons">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>