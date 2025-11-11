<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!----bootstrap icon link---->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!---bootstrap css link--->
    <!---slick slider link--->
    <link rel="stylesheet" type="text/css" href="slick.css" />
    <!---default css link--->
    <link rel="stylesheet" href="main.css">
    <title>veggen - home page</title>
</head>

<body>
    <section class="popular-picks">
        <h2>POPULAR PICKS</h2>
        <div class="controls">
            <i class="bi bi-chevron-left left"></i>
            <i class="bi bi-chevron-right right"></i>
        </div>
        <?php
        if (isset($message)){
            foreach ($message as $message) {
                echo'
                    <div class="message">
                        <span>'.$message.'</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>
                ';
            }
        }
    ?>
        <div class="popular-picks-content">
            <?php
                $select_foods = mysqli_query($conn,"SELECT * FROM 'products'") or die('Query Failed');
                if (mysqli_num_rows($select_foods)>0) {
                    while ($fetch_foods = mysqli_fetch_assoc($select_foods)){
                            
            ?>
            <form method="post" class="card">
                <img src="image/<?php echo $fetch_foods['image']; ?>">
                <div class="price">$<?php echo $fetch_foods['price']; ?></div>
                <div class="name"><?php echo $fetch_foods['name']; ?></div>
                <input type="hidden" name="food_id" value="<?php echo $fetch_foods['id']; ?>">
                <input type="hidden" name="food_name" value="<?php echo $fetch_foods['name']; ?>">
                <input type="hidden" name="food_price" value="<?php echo $fetch_foods['price']; ?>">
                <input type="hidden" name="food_quantity" value="1" min="1">
                <input type="hidden" name="food_image" value="<?php echo $fetch_foods['image']; ?>">
                <div class="icon">
                    <a href="view_page.php?pid=<?php echo $fetch_foods['id']; ?>" class="bi bi-eye-fill"></a>
                    <button type="submit" name="add_to_wishlist" class="bi bi-heart"></button>
                    <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                </div>
            </form>
            
            <?php
                    }
                }else{
                    echo '<p class="empty"> No foods added yet!</p>';
                }
            ?>
        </div>
    </section>
    <script src="jquary.js"></script>
    <script src="slick.js"></script>    

    <script type="text/javascript">
        $('.popular-picks-content').slick({
            lazyLoad: 'ondemand',
            slidesToShow: 4,
            slidesToScroll: 1,
            nextArrow: $('.left'),
            prevArrow: $('.right'),
            responsive: [
              {
                breakpoint: 1024,
                settings: {
                  slidesToShow: 3,
                  slidesToScroll: 3,
                  infinite: true,
                  dots: true,
                }
              },
              {
                breakpoint: 600,
                settings: {
                  slidesToShow: 2,
                  slidesToScroll: 2  
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1  
                }
              }            
            ]
        });
    </script>
</body>

</html>