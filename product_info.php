<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Product info</title>
</head>

<body>
    <?php
    @include 'config.php';

    session_start();

    $user_id = $_SESSION['user_id'];

    /*if (!isset($user_id)) {
            header('location:login.php');
        };*/

    //retrieve the product ID from the URL parameter
    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
    } else {
        die("Product ID not specified.");
    }

    //retrieve the product details from the database
    $sql = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $sql);

    // add product to cart when "add to cart" button is clicked
    if (isset($_POST['add_to_cart'])) {

        // create an array to hold product data
        $product_data = array(
            'product_id' => $_POST['product_id'],
            'product_name' => $_POST['product_name'],
            'product_price' => $_POST['product_price'],
            'product_image' => $_POST['product_image'],
            'product_quantity' => $_POST['product_quantity']
        );

        // if cart is empty, create a new session variable to hold cart items
        if (!isset($_SESSION['caaarts'])) {
            $_SESSION['caaarts'] = array();
        }

        // check if product is already in cart
        $product_exists = false;
        foreach ($_SESSION['caaarts'] as $key => $value) {
            if ($value['product_id'] == $product_data['product_id']) {
                // update product quantity
                $_SESSION['caaarts'][$key]['product_quantity'] += $product_data['product_quantity'];
                $product_exists = true;
                break;
            }
        }

        // if product is not in cart, add it to cart
        if (!$product_exists) {
            $_SESSION['caaarts'][] = $product_data;
        }

        // display success message
        echo 'product added to cart';
    }

    //display the product details
    if (mysqli_num_rows($result) > 0) {
        $row1 = mysqli_fetch_assoc($result);
    ?>
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="<?= $row1['img_url'] ?>" class="d-block mx-lg-auto img-fluid"
                        alt="Bootstrap Themes" width="700" height="500" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">
                        <?= $row1['name'] ?>
                    </h1>
                    <p class="lead">
                        <?= $row1['tittle'] ?>
                    </p>
                    <p class="lead"> JD
                        <?= $row1['price'] ?>
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <form action="" method="POST" class="box">
                            <input type="number" name="product_quantity" value="1" min="0" class="qty">
                            <input type="hidden" name="product_id" value="<?= $row1['id'] ?>">
                            <input type="hidden" name="product_name" value="<?= $row1['name'] ?>">
                            <input type="hidden" name="product_price" value="<?= $row1['price'] ?>">
                            <input type="hidden" name="product_image" value="<?= $row1['img_url'] ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo "No product found.";
    }


   
    ?>



    <?php
    

    if (isset($_POST['post_comment'])) {
        $name = $_POST['name'];
        $message = $_POST['message'];

        // Check if name and message are not empty
        if (empty($name) || empty($message)) {
            echo "Please fill in all required fields.";
        } else {
            $sql1 = "INSERT INTO comments (name, comment) VALUES ('$name', '$message')";

            if ($conn->query($sql1) === true) {
                echo "";
            } else {
                echo "Error: " . $sql1 . "<br>" . $con->error;
            }
        }
    }

    ?>
    <div class="wrapper">
        <form action="" method="post" class="form">
            <input type="text" class="name" name="name" value="<?= $row1['name'] ?>" readonly>
            <br>
            <textarea name="message" rows="5" cols="50" class="message" placeholder="Message"></textarea>
            <br>
            <button type="submit" class="btn" name="post_comment">Post Comment</button>
        </form>
    </div>

    <div class="content">
        <?php
        $sql1 = "SELECT * FROM comments";
        $result = $conn->query($sql1);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
        ?>
                <h4>
                    <?php echo $row['name']; ?>
                </h4>
                <p>
                    <?php echo $row['comment']; ?>
                </p>
        <?php
            }
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
