<?php

@include 'config.php';

session_start();

$id = $_SESSION['user_id'];

/*if (!isset($user_id)) {
    header('location:login.php');
};*/

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `caaarts` WHERE id = '$delete_id'") or die('query failed');
    header('location:caaarts.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `caaarts` WHERE user_id = '$user_id'") or die('query failed');
    header('location:caaarts.php');
};

if (isset($_POST['update_quantity'])) {
    $caaarts_id = $_POST['caaarts_id'];
    $caaarts_quantity = $_POST['caaarts_quantity'];
    mysqli_query($conn, "UPDATE `caaarts` SET quantity = '$caaarts_quantity' WHERE id = '$caaarts_id'") or die('query failed');
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

    <section class="shopping-cart">

        <h1 class="title">products added</h1>

        <div class="box-container">

            <?php
            $grand_total = 0;
            $select_caaarts = mysqli_query($conn, "SELECT * FROM `caaarts` WHERE user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select_caaarts) > 0) {
                while ($fetch_caaarts = mysqli_fetch_assoc($select_caaarts)) {
            ?>
                    <div class="box">
                        <a href="caaarts.php?delete=<?php echo $fetch_caaarts['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
                        <a href="product_info.php?product_id=<?php echo $fetch_caaarts['product_id']; ?>" class="fas fa-eye"></a>
                        <img src="uploaded_img/<?php echo $fetch_caaarts['image']; ?>" alt="" class="image">
                        <div class="name"><?php echo $fetch_caaarts['name']; ?></div>
                        <div class="price">$<?php echo $fetch_caaarts['price']; ?>/-</div>
                        <form action="" method="post">
                            <input type="hidden" value="<?php echo $fetch_caaarts['id']; ?>" name="caaarts_id">
                            <input type="mobile_number" min="1" value="<?php echo $fetch_caaarts['quantity']; ?>" name="caaarts_quantity" class="qty">
                            <input type="submit" value="update" class="option-btn" name="update_quantity">
                        </form>
                        <div class="sub-total"> sub-total : <span>$<?php echo $sub_total = ($fetch_caaarts['price'] * $fetch_caaarts['quantity']); ?>/-</span> </div>
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
            <a href="caaarts.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>" onclick="return confirm('delete all from cart?');">delete all</a>
        </div>

        <div class="cart-total">
            <p>grand total : <span>$<?php echo $grand_total; ?>/-</span></p>
            <a href="product_page.php" class="option-btn">continue shopping</a>
            <a href="payments.php" class="btn  <?php echo ($grand_total > 1) ? '' : 'disabled' ?>">proceed to checkout</a>
        </div>

    </section>






    <!--?php @include 'footer.php'; ?>-->

    <script src="js/script.js"></script>

</body>

</html>