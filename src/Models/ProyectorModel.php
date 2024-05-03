<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class ProyectorModel
{
    public $idEquipo;

    public function save($c)
    {
        try {
            $query = $c->prepare("INSERT INTO  proyector (idEquipo) VALUES (?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('ProyectorModel::save()->' . $e->getMessage());
            return false;
        }
    }
}
