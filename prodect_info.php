<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <?php
    //connect to the database
    $conn = mysqli_connect("localhost", "root", "", "brief4");

    //check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    //retrieve the product ID from the URL parameter
    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
    } else {
        die("Product ID not specified.");
    }

    //retrieve the product details from the database
    $sql = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $sql);

    //display the product details
    if (mysqli_num_rows($result) > 0) {
        $row1 = mysqli_fetch_assoc($result);
    ?>
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="<?= $row1['img_url'] ?>" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
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
                        <a href="cart_items.php?id=<?= $items['id'] ?>"
                        
                        <?php
                        if (isset($_POST['add_to_cart'])) {
                            $product_id=$_POST['product_id'];
                            $product_name=$_POST['name'];
                            $product_price=$_POST['price'];
                            $product_image=$_POST['img_url'];
                            $product_quantity=$_POST['tittle'];

                            $check_cart_numbers=mysqli_query($conn, "SELECT * FROM `cart_items`
                                                            WHERE name = '$product_name' AND user_id = '$user_id'" )
                                                            or die('query failed');
                            
                            if (mysqli_num_rows($check_cart_numbers)> 0) {
                                $message[] = 'already added to cart';
                            }
                            mysqli_query($conn, "INSERT INTO `cart_items`(user_id, product_id, name, price, quantity, image)
                                VALUES('$user_id', '$product_id',
                                        '$product_name', '$product_price',
                                        '$product_quantity', '$product_image')")
                                or die('query failed');
                            $message[] = 'product added to cart';
                            }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    $con = mysqli_connect("localhost", "root", "", "brief4");

    if (isset($_POST['post_comment'])) {
        $name = $_POST['name'];
        $message = $_POST['message'];

        // Check if name and message are not empty
        if (empty($name) || empty($message)) {
            echo "Please fill in all required fields.";
        } else {
            $sql1 = "INSERT INTO comments (name, comment) VALUES ('$name', '$message')";

            if ($con->query($sql1) === TRUE) {
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
        $result = $con->query($sql1);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
        ?>
                <h4>
                    <?php echo $row['name']; ?>
                </h4>
                <p>
                    <?php echo $row['comments']; ?>
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