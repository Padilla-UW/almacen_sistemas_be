<?php

namespace ApiSistemas\Libs;

class Model
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function connect()
    {
        return $this->db->connect();
    }

    public function query($sql)
    {
        return $this->db->connect()->query($sql);
    }

    public function prepare($sql)
    {
        return $this->db->connect()->prepare($sql);
    }
}
