<?php

class Orders
{
    public static $pdo;

    public $id;
    public $user_id;
    public $products;
    public $total;
    public $created_at;

    public static function createOrder($user_id, $products, $total)
    {
        $stm = self::$pdo->prepare("INSERT INTO orders(user_id, products, total) VAlUES(:user_id, :products, :total)");
        $stm->execute([
            'user_id' => $user_id,
            'products' => $products,
            'total' => $total
        ]);
        $names = $stm->fetchAll(PDO::FETCH_COLUMN, 0);

        $stm = self::$pdo->prepare("DELETE FROM bag WHERE user_id = :user_id");
        $stm->execute([
            'user_id' => $user_id
        ]);

        $row = $stm->rowCount();
        return $row;
    }
}
