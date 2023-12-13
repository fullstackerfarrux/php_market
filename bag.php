<?php

require "config/database.php";
require "autoloader.php";

$connection = new Database("127.0.0.1", "root", "", "market");
$pdo = $connection->connect();
Bag::$pdo = $pdo;
Orders::$pdo = $pdo;

$products = $_SESSION['products'];

function getTotal()
{
    $products = Bag::getAll();
    $prices = array_map(function ($product) {
        return $product['product_price'] * $product['count'];
    }, $products);

    $totalPrice = array_sum($prices);
    return $totalPrice;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["increase"])) {
    $productId = $_POST["productId"];

    $result = Bag::increaseProduct($productId);

    header("Location: /bag.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["decrease"])) {
    $productId = $_POST["productId"];

    $result = Bag::decreaseProduct($productId);

    header("Location: /bag.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["order"])) {
    $user_id = $_COOKIE['Log'];
    $totalPrice = getTotal();

    $result = Orders::createOrder($user_id, $products, $totalPrice);
    header("Location: /");
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Shopping Bag</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .product a {
            text-decoration: none;
            color: #333;
            margin-left: 10px;
        }

        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .button {
            display: flex;
            width: 100px;
            height: 30px;
            background-color: #030303;
            justify-content: center;
            align-items: center;
            color: white;
            border: none;
            border-radius: 5px;

        }
    </style>
</head>

<body>
    <div class="container">
        <a href="/index.php"><i class="fa-solid fa-angles-left"></i></a>
        <h1>Shopping Bag</h1>
        <ul>
            <?php foreach ($products as $product) : ?>
                <li class="product"><?php echo $product['product_name'] ?> - $<?= $product['product_price'] ?>
                    <div>
                        <form method='POST'>
                            <input type='hidden' name='productId' value='<?php echo $product['product_id'] ?>' />
                            <input type='submit' name='decrease' value='-' />
                            <span name='quantity'> <?php echo $product['count'] ?> </span>
                            <input type='submit' name='increase' value='+' />
                            <!-- <input type="submit"> Remove </input> -->
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
            <li>
                <div class="total">
                    <h2>Total:</h2>
                    <p>$<?php echo getTotal() ?></p>
                </div>
            </li>
        </ul>
        <form method="post">
            <button type="submit" name="order" class="button">Заказать</button>
        </form>
    </div>
</body>

</html>