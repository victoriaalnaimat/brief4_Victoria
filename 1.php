<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Document</title>
</head>
<style>
    .imgcard {
        max-width: 250px;
        height: 250px;
    }
</style>

<body>

    <?php
    $con = mysqli_connect("localhost", "root", "", "brief4");

    // Get search input
    $search_input = "";
    if (isset($_GET['search'])) {
        $search_input = $_GET['search'];
    }

    // Get filter values
    $min_price = 100;
    $max_price = 900;
    $category_ids = array();
    if (isset($_GET['filter'])) {
        $min_price = $_GET['min_price'];
        $max_price = $_GET['max_price'];
        if (isset($_GET['category_ids'])) {
            $category_ids = $_GET['category_ids'];
            // rest of the code
        } else {
            // handle the case when the key is not set
        }
    }

    // Prepare SQL statement with search and filter conditions
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_input%'";
    if (!empty($category_ids)) {
        $category_ids_str = implode(",", $category_ids);
        $sql .= " AND category_id IN ($category_ids_str)";
    }
    $sql .= " AND price >= $min_price AND price <= $max_price";
    $result = mysqli_query($con, $sql);

    ?>
    <!DOCTYPE html>
    <html lang="eng">

    <head>
        <title>Product List</title>

    </head>

    <body>
        <?php
        $con = mysqli_connect("localhost", "root", "", "brief4");

        // Get all categories
        $brand_query = "SELECT * FROM categories";
        $brand_query_run = mysqli_query($con, $brand_query);

        // Add price filter to query if present
        $where = "";
        $price_filter = false;
        if (isset($_GET['start_price']) && isset($_GET['end_price'])) {
            $start_price = $_GET['start_price'];
            $end_price = $_GET['end_price'];
            if ($start_price != "" && $end_price != "") {
                $where .= " WHERE price BETWEEN $start_price AND $end_price";
                $price_filter = true;
            }
        }

        // Add name filter to query if present
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            if (count($name) > 0) {
                $name_array = [];
                foreach ($name as $n) {
                    $name_array[] = "category_id = $n";
                }
                $name_str = implode(" OR ", $name_array);
                if ($price_filter) {
                    $where .= " AND ($name_str)";
                } else {
                    $where .= " WHERE ($name_str)";
                }
            }
        }

        // Get products based on filters
        $query = "SELECT * FROM products $where";
        $result = mysqli_query($con, $query);

        ?>

        <form action="" method="GET">
            <div class="row">
                <div class="col-md-4 m-3">
                    <label for="">Start Price</label>
                    <input type="text" name="start_price" value="<?php if (isset($_GET['start_price'])) {
                            echo $_GET['start_price'];
                        } else {
                            echo "0";
                        } ?>" class="form-control">
                </div>
                <div class="col-md-4 m-3">
                    <label for="">End Price</label>
                    <input type="text" name="end_price" value="<?php if (isset($_GET['end_price'])) {
                            echo $_GET['end_price'];
                        } else {
                            echo "900";
                        } ?>" class="form-control">
                </div>
                <div class="col-md-4 m-3">
                    <label for="">categories</label><br>
                    <?php
                    if (mysqli_num_rows($brand_query_run) > 0) {
                        foreach ($brand_query_run as $brandlist) {
                            $checked = [];
                            if (isset($_GET['name'])) {
                                $checked = $_GET['name'];
                            }
                    ?>
                            <div>
                                <input type="checkbox" name="name[]" value="<?= $brandlist['id']; ?>"
                                    <?php if (in_array($brandlist['id'], $checked)) {
                                        echo "checked";
                                    } ?> />
                                <?= $brandlist['name']; ?>
                            </div>
                    <?php
                        }
                    } else {
                        echo "No categories found";
                    }
                    ?>
                </div>
                <div class="col-md-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4">Filter</button>
                </div>
            </div>
        </form>

        <div class="row gap-3 m-3">
            <?php if (mysqli_num_rows($result) > 0) {
                foreach ($result as $items) { ?>
                    <div class="col-md-2 gap-3">

                        <div class="card text-center p-1 m-2 cardhw w-100" style="max-width: 200px;">
                            <img src="<?= $items['img_url'] ?>" class="imgcard" alt="<?= $items['name'] ?>">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $items['name'] ?>
                                </h5>
                                <p class="card-text">JD
                                    <?= $items['price'] ?>
                                </p>
                                <a href="prodect_info.php?id=<?= $items['id'] ?>" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
            <?php }
            } else {
                echo "No items found.";
            } ?>
        </div>
        <form method="GET" class="p-2">
            <div>
                <label for="searchInput" class="form-label">Search by name</label>
                <input type="text" class="form-control form-control-sm" id="searchInput"
                    name="search" placeholder="Enter item name" style="width: 300px; height: 30px;">
            </div>
            <button type="submit" class="btn btn-primary ">Search</button>
        </form>

        <?php


        // Check if search query is submitted
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            // Search for items that match the name
            $result = mysqli_query($con, "SELECT * FROM products WHERE name LIKE '%$search%'");
        } else {
            // If no search query, display all items
            $result = mysqli_query($con, "SELECT * FROM products");
        }
        ?>


        <div class="carousel-inner p-2" style="width: 300px; height: 300px; ">
            <?php $i = 0;
            foreach ($result as $item) { ?>
                <div class="carousel-item <?= ($i == 0) ? 'active' : '' ?>" data-bs-interval="3000">
                    <img src="<?= $item['img_url'] ?>" class="d-block w-100" alt="<?= $item['name'] ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>
                            <?= $item['name'] ?>
                        </h5>
                    </div>
                </div>
            <?php $i++;
            } ?>
        </div>





        <?php if (mysqli_num_rows($result) == 0) {
            echo "No items found.";
        } ?>



        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>