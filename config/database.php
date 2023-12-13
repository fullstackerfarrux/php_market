<?php

class Database
{
    private $host = "127.0.0.1";
    private $servername = "root";
    private $password = "";
    private $db = "market";

    public function connect()
    {
        try {
            $pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->servername, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " .  $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->connect();
    }
}
