<?php
session_start();
include 'connection.php';

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
    header('location: login.php');
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
    session_destroy();
    header('location: login.php');
    exit();
}

// Adding foods to database
if (isset($_POST['add_product'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message[] = 'Invalid request';
    } else {
        $food_name = mysqli_real_escape_string($conn, $_POST['name']);
        $food_price = floatval($_POST['price']);
        $food_detail = mysqli_real_escape_string($conn, $_POST['detail']);

        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($image_extension, $allowed_extensions)) {
            $message[] = 'Invalid image format. Only JPG, JPEG, PNG, and WEBP allowed.';
        } elseif ($image_size > 2000000) {
            $message[] = 'Image size is too large (max 2MB)';
        } else {
            $select_food_name = mysqli_query($conn, "SELECT name FROM foods WHERE name = '$food_name'");
            if ($select_food_name && mysqli_num_rows($select_food_name) > 0) {
                $message[] = 'Food name already exists';
            } else {
                $new_image_name = uniqid() . '.' . $image_extension;
                $image_folder = 'image/' . $new_image_name;

                $insert_product = mysqli_query($conn, "INSERT INTO foods (name, price, food_detail, image) VALUES ('$food_name', '$food_price', '$food_detail', '$new_image_name')");

                if ($insert_product) {
                    if (move_uploaded_file($image_tmp_name, $image_folder)) {
                        $message[] = 'Food added successfully';
                    } else {
                        $message[] = 'Failed to upload image';
                    }
                } else {
                    $message[] = 'Failed to add food';
                }
            }
        }
    }
}

// Delete food from database
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    $select_delete_image = mysqli_query($conn, "SELECT image FROM foods WHERE id = $delete_id");
    if ($select_delete_image && mysqli_num_rows($select_delete_image) > 0) {
        $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
        if (file_exists('image/' . $fetch_delete_image['image'])) {
            unlink('image/' . $fetch_delete_image['image']);
        }

        mysqli_query($conn, "DELETE FROM foods WHERE id = $delete_id");
        mysqli_query($conn, "DELETE FROM cart WHERE fid = $delete_id");
        mysqli_query($conn, "DELETE FROM wishlist WHERE fid = $delete_id");
    }

    header('location: admin_food.php');
    exit();
}

// Update food
if (isset($_POST['update_food'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message[] = 'Invalid request';
    } else {
        $update_id = intval($_POST['update_id']);
        $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
        $update_price = floatval($_POST['update_price']);
        $update_detail = mysqli_real_escape_string($conn, $_POST['update_detail']);

        if (!empty($_FILES['update_image']['name'])) {
            $update_image = $_FILES['update_image']['name'];
            $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
            $update_image_extension = strtolower(pathinfo($update_image, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($update_image_extension, $allowed_extensions)) {
                $new_image_name = uniqid() . '.' . $update_image_extension;
                $update_image_folder = 'image/' . $new_image_name;

                $update_query = mysqli_query($conn, "UPDATE foods SET name = '$update_name', price = '$update_price', food_detail = '$update_detail', image = '$new_image_name' WHERE id = $update_id");

                if ($update_query) {
                    move_uploaded_file($update_image_tmp_name, $update_image_folder);
                    header('location: admin_food.php');
                    exit();
                }
            }
        } else {
            $update_query = mysqli_query($conn, "UPDATE foods SET name = '$update_name', price = '$update_price', food_detail = '$update_detail' WHERE id = $update_id");
            if ($update_query) {
                header('location: admin_food.php');
                exit();
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
    <link rel="stylesheet" href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Panel - Foods</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="message">
                    <span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>';
            }
        }
    ?>
    <div class="line2"></div>
    <section class="add-foods form-container">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="input-field">
                <label>Food Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-field">
                <label>Food Price</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            <div class="input-field">
                <label>Food Detail</label>
                <textarea name="detail" required></textarea>
            </div>
            <div class="input-field">
                <label>Food Image</label>
                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" required>
            </div>
            <input type="submit" name="add_product" value="Add Food" class="btn">
        </form>
    </section>
    <div class="line3"></div>
    <div class="line4"></div>
    <section class="show-foods">
        <div class="box-container">
            <?php
                $select_foods = mysqli_query($conn, "SELECT * FROM foods");
                if ($select_foods && mysqli_num_rows($select_foods) > 0) {
                    while ($fetch_foods = mysqli_fetch_assoc($select_foods)) {
            ?>
            <div class="box">
                <img src="image/<?php echo htmlspecialchars($fetch_foods['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Food Image">
                <p>Price: $<?php echo number_format($fetch_foods['price'], 2); ?></p>
                <h4><?php echo htmlspecialchars($fetch_foods['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                <p><?php echo htmlspecialchars($fetch_foods['food_detail'], ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="admin_food.php?edit=<?php echo $fetch_foods['id']; ?>" class="edit">Edit</a>
                <a href="admin_food.php?delete=<?php echo $fetch_foods['id']; ?>" class="delete" onclick="return confirm('Want to delete this food?');">Delete</a>
            </div>
            <?php
                    }
                } else {
                    echo '<div class="empty"><p>No food added yet!</p></div>';
                }
            ?>
        </div>
    </section>
    <div class="line"></div>
    <section class="update-container">
        <?php
        if (isset($_GET['edit'])) {
            $edit_id = intval($_GET['edit']);
            $edit_query = mysqli_query($conn, "SELECT * FROM foods WHERE id = $edit_id");
            if ($edit_query && mysqli_num_rows($edit_query) > 0) {
                $fetch_edit = mysqli_fetch_assoc($edit_query);
        ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <img src="image/<?php echo htmlspecialchars($fetch_edit['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Food Image">
            <input type="hidden" name="update_id" value="<?php echo $fetch_edit['id']; ?>">
            <input type="text" name="update_name" value="<?php echo htmlspecialchars($fetch_edit['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            <input type="number" step="0.01" name="update_price" min="0" value="<?php echo $fetch_edit['price']; ?>" required>
            <textarea name="update_detail" required><?php echo htmlspecialchars($fetch_edit['food_detail'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png, image/webp">
            <input type="submit" name="update_food" value="Update" class="edit">
            <input type="reset" value="Cancel" class="option-btn btn" id="close-form">
        </form>
        <?php
                echo "<script>document.querySelector('.update-container').style.display='block'</script>";
            }
        }
        ?>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
