<?php

session_start();

class Bag
{
    public static $pdo;

    public $id;
    public $user_id;
    public $product_id;
    public $count;
    public $created_at;

    public static function addToBag($user_id, $product_id)
    {
        $stm = self::$pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stm->execute([
            'id' => $product_id
        ]);
        $getProduct = $stm->fetchAll();

        $products = $_SESSION['products'];
        $resProducts = [];

        foreach ($products as $product) {
            $productCount = 1;
            if ($product['product_id'] == $product_id) {
                $productCount = $product['count'] + 1;
            }
            $resProduct = [
                'user_id' => $user_id, 'product_id' => $product_id, 'product_name' => $product['product_name'], 'product_price' => $product['product_price'], 'count' => $productCount
            ];

            array_push($resProducts, $resProduct);
        }

        $resProduct = [
            'user_id' => $user_id, 'product_id' => $product_id, 'product_name' => $getProduct[0]['title'], 'product_price' => $getProduct[0]['price'], 'count' => 1
        ];

        array_push($resProducts, $resProduct);
        $_SESSION['products'] = $resProducts;
    }

    public static function getAll()
    {
        $products = $_SESSION['products'];

        return $products;
    }

    public static function increaseProduct($productId)
    {
        $user_id = $_COOKIE['Log'];
        $products = $_SESSION['products'];
        $resProducts = [];

        foreach ($products as $product) {
            $productCount = $product['count'];
            if ($product['product_id'] == $productId) {
                $productCount = $product['count'] + 1;
            }

            $resProduct = [
                'user_id' => $user_id, 'product_id' => $productId, 'product_name' => $product['product_name'], 'product_price' => $product['product_price'], 'count' => $productCount
            ];

            array_push($resProducts, $resProduct);
        }
        $_SESSION['products'] = $resProducts;

        return $resProducts;
    }

    public static function decreaseProduct($productId)
    {
        $user_id = $_COOKIE['Log'];
        $products = $_SESSION['products'];
        $resProducts = [];

        foreach ($products as $product) {
            $productCount = $product['count'];
            if ($product['product_id'] == $productId) {
                $productCount = $product['count'] - 1;
            }

            if ($productCount > 0) {
                $resProduct = [
                    'user_id' => $user_id, 'product_id' => $productId, 'product_name' => $product['product_name'], 'product_price' => $product['product_price'], 'count' => $productCount
                ];
                array_push($resProducts, $resProduct);
            }
        }
        $_SESSION['products'] = $resProducts;

        return $resProducts;
    }

    public static function getTotal()
    {
        $products = $_SESSION['products'];

        foreach ($products as $product) {
            $productPrice = $product['product_price'];
        }

        $prices = array_map(function ($product) {
            return $product['product_price'] * $product['count'];
        }, $products);

        $totalPrice = array_sum($prices);
        return $totalPrice;
    }
}
