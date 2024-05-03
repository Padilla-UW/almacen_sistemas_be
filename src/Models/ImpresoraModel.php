<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class ImpresoraModel
{
    public $id;
    public $idEquipo;
    public $impresionesXMes;

    public function save($c)
    {
        try {
            $query = $c->prepare("INSERT INTO impresora (idEquipo, impresionesXMes) VALUES (?,?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->impresionesXMes, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('Impresora::save()->' . $e->getMessage());
            return false;
        }
    }
}
