<?php

namespace ApiSistemas\Libs;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $user;
    private $db;
    private $password;
    private $charset;

    public function __construct()
    {
        $this->host = constant('HOST');
        $this->db = constant('DB');
        $this->user = constant('USER');
        $this->password = constant('PASSWORD');
    }

    public function connect()
    {
        try {
            $connection = "mysql:host={$this->host};dbname={$this->db}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($connection, $this->user, $this->password, $options);
            return $pdo;
        } catch (PDOException $e) {
            error_log('Error connection: ' . $e->getMessage());
            return false;
        }
    }
}
