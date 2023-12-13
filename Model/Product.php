<?php
// require "config/database.php";

class Product extends Database
{
    public static $pdo;

    public $id;
    public $title;
    public $price;
    public $created_at;

    public static function getAll()
    {
        $stm = self::$pdo->prepare("SELECT * FROM products");
        $stm->execute();
        $products = $stm->fetchAll();

        return $products;
    }

    public static function createProduct($title, $price)
    {
        $stm = self::$pdo->prepare("INSERT INTO products(title, price) VALUES(:title, :price)");
        $stm->execute([
            "title" => $title,
            "price" => $price,
        ]);
        $row = $stm->rowCount();

        return $row;
    }
}
