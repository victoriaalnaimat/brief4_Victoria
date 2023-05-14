<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

/*if (!isset($user_id)) {
    header('location:login.php');
};
*/
if (isset($_POST['payment'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile_number = mysqli_real_escape_string($conn, $_POST['mobile_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $address = mysqli_real_escape_string($conn, 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
    $payment_date = date('d-M-Y');

    $caaarts_total = 0;
    $caaarts_products[] = '';

    $caaarts_query = mysqli_query($conn, "SELECT * FROM `caaarts` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($caaarts_query) > 0) {
        while ($caaarts = mysqli_fetch_assoc($caaarts_query)) {
            $caaarts_products[] = $caaarts['name'] . ' (' . $caaarts['quantity'] . ') ';
            $sub_total = ($caaarts['price'] * $caaarts['quantity']);
            $caaarts_total += $sub_total;
        }
    }

    $total_products = implode(', ', $caaarts_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND mobile_number = '$mobile_number' AND email = '$email' AND payment_method = '$payment_method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$caaarts_total'") or die('query failed');

    if ($caaarts_total == 0) {
        $message[] = 'your cart is empty!';
    } elseif (mysqli_num_rows($order_query) > 0) {
        $message[] = 'order placed already!';
    } else {
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, mobile_number, email, payment_method, address, total_products, total_price, payment_date) VALUES('$user_id', '$name', '$mobile_number', '$email', '$payment_method', '$address', '$total_products', '$caaarts_total', '$payment_date')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `caaarts` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'order placed successfully!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/cart_and_payment.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="display-order">
        <?php
        $grand_total = 0;
        $select_caaarts = mysqli_query($conn, "SELECT * FROM `caaarts` WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_caaarts) > 0) {
            while ($fetch_caaarts = mysqli_fetch_assoc($select_caaarts)) {
                $total_price = ($fetch_caaarts['price'] * $fetch_caaarts['quantity']);
                $grand_total += $total_price;
        ?>
                <p> <?php echo $fetch_caaarts['name'] ?> <span>(<?php echo '$' . $fetch_caaarts['price'] . '/-' . ' x ' . $fetch_caaarts['quantity']  ?>)</span> </p>
        <?php
            }
        } else {
            echo '<p class="empty">your cart is empty</p>';
        }
        ?>
        <div class="grand-total">Grand total : <span><?php echo $grand_total; ?>JD</span></div>
    </section>

    <section class="checkout">

        <form action="" method="POST">

            <h3>place your order</h3>

            <div class="flex">
                <div class="inputBox">
                    <span>your name :</span>
                    <input type="text" name="name" placeholder="enter your name">
                </div>
                <div class="inputBox">
                    <span>your mobile number :</span>
                    <input type="number" name="mobile_number" min="0" placeholder="enter your mobile number">
                </div>
                <div class="inputBox">
                    <span>your email :</span>
                    <input type="email" name="email" placeholder="enter your email">
                </div>
                <div class="inputBox">
                    <span>payment method :</span>
                    <select name="payment_method">
                        <option value="cash on delivery">cash on delivery</option>
                        <option value="credit card">credit card</option>
                        <option value="paypal">paypal</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>address line 01 :</span>
                    <input type="text" name="flat" placeholder="e.g. flat no.">
                </div>
                <div class="inputBox">
                    <span>address line 02 :</span>
                    <input type="text" name="street" placeholder="e.g.  street name">
                </div>
                <div class="inputBox">
                    <span>city :</span>
                    <input type="text" name="city" placeholder="e.g. As-Salt">
                </div>
                <div class="inputBox">
                    <span>Government :</span>
                    <input type="text" name="Government" placeholder="e.g. Al-Balqaa">
                </div>
                <div class="inputBox">
                    <span>country :</span>
                    <input type="text" name="country" placeholder="e.g. Jordan">
                </div>
                <div class="inputBox">
                    <span>pin code :</span>
                    <input type="number" min="0" name="pin_code" placeholder="e.g. 123456">
                </div>
            </div>

            <input type="submit" name="payment" value="order now" class="btn">

        </form>

    </section>






    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>