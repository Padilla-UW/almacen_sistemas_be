<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class ChecadorModel
{
    public $id;
    public $idEquipo;
    public $tipoChecada;
    public $ip;

    public function save($c)
    {
        try {
            $query = $c->prepare("INSERT INTO  checador (idEquipo, tipoChecada, ip) VALUES (?,?,?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->tipoChecada, PDO::PARAM_STR);
            $query->bindValue(3, $this->ip, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('checador::save()->' . $e->getMessage());
            return false;
        }
    }
}
