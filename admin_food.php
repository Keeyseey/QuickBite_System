<?php
    include 'connection.php';
    $admin_id = $_SESSION['admin_name'];

    if (!isset($admin_id)) {
        header('location:login.php');
    }

    if (!isset($_POST['logout'])) {
        session_destroy();
        header('location:login.php');
    }
    //adding-foods to database
    if (isset($_POST['add_product'])) {
        $food_name = mysqli_real_escape_string($conn, $_POST['name']);
        $food_price = mysqli_real_escape_string($conn, $_POST['price']);
        $food_detail = mysqli_real_escape_string($conn, $_POST['detail']);
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'image/'.$image;

        $select_food_name = mysqli_query($conn, "SELECT name FROM 'foods' WHERE name = '$food_name'") or die('Query Failed');
        if(mysqli_num_rows($select_food_name)>0) {
            $message[] = 'Food name already exist';
        }else{
            $insert_product = mysqli_query($conn,"INSERT INTO 'foods'('name', 'price', 'food_detail', 'image')
                VALUES ('$food_name', '$food_price', '$food_detail', '$image')") or die('Query Failed');
            if ($insert_food) {
                if ($image_size > 2000000) {
                    $message[] = 'Image size is too large';
                }else{
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'Food added successfully';
                }
            }
        }
    }

    //delete foods from database
    if(isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $select_delete_image = mysqli_query($conn, "SELECT image FROM 'foods' WHERE id ='$delete_id'") or die('Query Failed');
        $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
        unlink('image/' .$delete_delete_image['image']);

        mysqli_query($conn, "DELETE FROM 'foods' WHERE id = '$delete_id'") or die('Query Failed');
        mysqli_query($conn, "DELETE FROM 'cart' WHERE id = '$delete_id'") or die('Query Failed');
        mysqli_query($conn, "DELETE FROM 'wishlist' WHERE id = '$delete_id'") or die('Query Failed');
        
        header('location:admin_food.php');
    }
    //update food
    if(isset($_POST['update_food'])) {
        $update_id = $_POST['update_id'];
        $update_name = $_POST['update_name'];
        $update_price = $_POST['update_price'];
        $update_detail = $_POST['update_detail'];
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'image/' .$update_image;

        $update_query = mysqli_query($conn,"UPDATE 'foods' SET 'id'='$update_id', 'name' = '$update_name', 'price' = '$update_price',
            'product_detail' ='$update_detail', 'image'='$update_image' WHERE id = '$update_id'") or die('Query Failed');
        if($update_query) {
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
            header('location:admin_food.php');
        }
    }
?>
<style type="text/css">
    <?php
        include 'style.css';
    ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--box icon link-->
    <link rel="stylesheet" href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Panel</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '
                    <div class="message">
                    <span>' .$message. '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i> 
                    </div>
                ';
            }
        }
    ?>
    <div class="line2"></div>
    <section class="add-foods form-container">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="input-field">
                <label>Food Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-field">
                <label>Food Price</label>
                <input type="text" name="price" required>
            </div>
            <div class="input-field">
                <label>Food Detail</label>
                <input type="text" name="detail" required>
            </div>
            <div class="input-field">
                <label>Food Image</label>
                <input type="text" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" required>
            </div>
            <input type="submit" name="add_food" value="Add Food" class="btn">
        </form>

    </section>
    <div class="line3"></div>
    <div class="line4"></div>
    <section class="show-foods">
        <div class="box-container">
            <?php
                $select_foods = mysqli_query($conn,"SELECT * FROM 'foods'") or die('Query Failed');
                if(mysqli_num_rows($select_foods)>0) {
                    while($fetch_foods = mysqli_fetch_assoc($select_foods)){

            ?>
            <div class="box">
                <img src="image/<?php echo $fetch_foods['image']; ?>">
                <p>Price: $<?php echo $fetch_foods['price']; ?> </p>
                <h4><?php echo $fetch_foods['name']; ?></h4>
                <details><?php echo $fetch_foods['food_detail']; ?></details>
                <a href="admin_food.php?edit=<?php echo $fetch_foods['id']; ?>" class="edit">Edit</a>
                <a href="admin_food.php?delete=<?php echo $fetch_foods['id']; ?>" class="delete" onclick="
                    return confirm('Want to delete this food?');">Delete</a>
            </div>
            <?php
                        }
                }else{
                        echo '
                            <div class="empty">
                            <p> No food added yet!</p>
                            </div> 
                        ';
                }
            ?>

        </div>
    </section>
    <div class="line"></div>
    <section class="update-container">
        <?php
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $edit_query = mysqli_query($conn,"SELECT * FROM 'foods' WHERE id = '$edit_id'") or die('Query Failed');
            if (mysqli_num_rows($edit_query)> 0) {
                while($fetch_edit = mysqli_fetch_assoc($edit_query)){
        ?>
        <form method="POST" enctype="multipart/form-data">
                <img src="image/<?php echo $fetch_edit['image']; ?>">
                <input type="hidden" name="update_id" value="<?php echo $fetch_id['id']; ?>">
                <input type="text" name="update_name" value="<?php echo $fetch_edit['name']; ?>">
                <input type="number" name="update_price" min="0" value="<?php echo $fetch_edit['price']; ?>">
                <textarea><?php echo $fetch_edit['food_detail']; ?></textarea>
                <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png, image/webp">
                <input type="submit" name="update_food" value="Update" class="edit">
                <input type="reset" name="" value="Cancel" class="option-btn btn" id="close-form">                
        </form>
        <?php
                    }
                }
                echo "<script>document.querySelector('.update-container').style.display='block'</script>";
            }
        ?>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>