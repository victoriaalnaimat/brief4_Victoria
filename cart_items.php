<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart_items` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart_items.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart_items` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart_items.php');
};

if (isset($_POST['update_quantity'])) {
    $cart_items_id = $_POST['cart_items_id'];
    $cart_items_quantity = $_POST['cart_items_quantity'];
    mysqli_query($conn, "UPDATE `cart_items` SET quantity = '$cart_items_quantity' WHERE id = '$cart_items_id'") or die('query failed');
    $message[] = 'cart quantity updated!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopping cart</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/cart_and_payment.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <h3>shopping cart</h3>
        <p> <a href="home.php">home</a> / cart </p>
    </section>

    <section class="shopping-cart">

        <h1 class="title">products added</h1>

        <div class="box-container">

            <?php
            $grand_total = 0;
            $select_cart_items = mysqli_query($conn, "SELECT * FROM `cart_items` WHERE user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select_cart_items) > 0) {
                while ($fetch_cart_items = mysqli_fetch_assoc($select_cart_items)) {
            ?>
                    <div class="box">
                        <a href="cart_items.php?delete=<?php echo $fetch_cart_items['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
                        <a href="view_page.php?pid=<?php echo $fetch_cart_items['pid']; ?>" class="fas fa-eye"></a>
                        <img src="uploaded_img/<?php echo $fetch_cart_items['image']; ?>" alt="" class="image">
                        <div class="name"><?php echo $fetch_cart_items['name']; ?></div>
                        <div class="price">$<?php echo $fetch_cart_items['price']; ?>/-</div>
                        <form action="" method="post">
                            <input type="hidden" value="<?php echo $fetch_cart_items['id']; ?>" name="cart_items_id">
                            <input type="mobile_number" min="1" value="<?php echo $fetch_cart_items['quantity']; ?>" name="cart_items_quantity" class="qty">
                            <input type="submit" value="update" class="option-btn" name="update_quantity">
                        </form>
                        <div class="sub-total"> sub-total : <span>$<?php echo $sub_total = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']); ?>/-</span> </div>
                    </div>
            <?php
                    $grand_total += $sub_total;
                }
            } else {
                echo '<p class="empty">your cart is empty</p>';
            }
            ?>
        </div>

        <div class="more-btn">
            <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>" onclick="return confirm('delete all from cart?');">delete all</a>
        </div>

        <div class="cart-total">
            <p>grand total : <span>$<?php echo $grand_total; ?>/-</span></p>
            <a href="shop.php" class="option-btn">continue shopping</a>
            <a href="checkout.php" class="btn  <?php echo ($grand_total > 1) ? '' : 'disabled' ?>">proceed to checkout</a>
        </div>

    </section>






    <!--?php @include 'footer.php'; ?>-->

    <script src="js/script.js"></script>

</body>

</html>