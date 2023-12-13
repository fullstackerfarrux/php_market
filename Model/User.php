<?php

class User
{
    public static $pdo;

    public $id;
    public $username;
    public $password;
    public $created_at;

    public static function createUser($username, $password)
    {
        $stm = self::$pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stm->execute([
            "username" => $username,
        ]);
        $findUser = $stm->fetch();

        if ($findUser == false) {
            // create data
            $stm = self::$pdo->prepare("INSERT INTO users(username, password) VALUES(:username, :password)");
            $stm->execute([
                'username' => $username,
                'password' => $password,
            ]);

            //  get password after create data

            $stm = self::$pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stm->execute([
                "username" => $username,
            ]);
            $findUser = $stm->fetch();
            return $findUser[2];
        } else {
            $stm = self::$pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stm->execute([
                "username" => $username,
            ]);
            $findUser = $stm->fetch();
            if ($findUser[2] == $password) {
                return $findUser[2];
            } else if ($findUser[2] !== $password) {
                return "Password error";
            } else {
                return 'User exists';
            }
        }
    }
}
