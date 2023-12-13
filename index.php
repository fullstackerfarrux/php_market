<?php

require "config/database.php";
require "autoloader.php";

$connection = new Database('127.0.0.1', 'root', '', 'market');
$pdo = $connection->connect();
Product::$pdo = $pdo;
Bag::$pdo = $pdo;

$products = Product::getAll();

if (!$_COOKIE["Log"]) {
    header("Location: /login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["productName"])) {
    $productName = $_POST["productName"];
    $productPrice = $_POST["productPrice"];

    $result = Product::createProduct($productName, $productPrice);

    if ($result == 1) {
        header("Location: /");
        exit;
    }
}

if (isset($_GET["addBag"])) {
    $product_id = $_GET["product_id"];
    $user_id = $_COOKIE['Log'];

    $result = Bag::addToBag($user_id, $product_id);
}


?>


<!DOCTYPE html>
<html>

<head>
    <title>Market</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Add custom styles here */
        .product {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px;
            text-align: center;
        }

        .product img {
            max-width: 100px;
            max-height: 100px;
        }

        .btn-add-to-cart {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-add-to-cart:hover {
            background-color: #218838;
        }

        .shopping-bag {
            font-size: 24px;
            color: #007bff;
        }

        .btn-create {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <h1>Market</h1>
            <div class="col-md-12 text-right">
                <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#createProductModal">Create Product</button>
            </div>
        </div>

        <div class="row">
            <?php
            foreach (array_reverse($products) as $product) {
            ?>
                <div class="col-md-4">
                    <div class="product">
                        <img src="https://via.placeholder.com/150">
                        <h4><?php echo $product['title']; ?></h4>
                        <p>$<?php echo $product['price']; ?></p>
                        <form method="get">
                            <input type="hidden" value="<?php echo $product['id'] ?>" id="<?php echo $product['id'] ?> " name="product_id">
                            <button type="submit" name="addBag" class="btn btn-add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <a href="/bag.php"><i class="fas fa-shopping-bag shopping-bag"></i> View Cart</a>
            </div>
        </div>
    </div>
    <!-- ... previous code remains unchanged ... -->

    <!-- Create Product Modal -->

    <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductModalLabel">Create New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form to create a new product -->
                    <form method="POST">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="form-group">
                            <label for="productPrice">Product Price ($)</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" min="0" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>