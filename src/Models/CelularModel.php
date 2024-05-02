<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class CelularModel
{
    public $id;
    public $idEquipo;
    public $numCelular;
    public $fechaInicio;
    public $fechaFin;

    public function save($c)
    {

        try {
            $query = $c->prepare("INSERT INTO celular (idEquipo, numCelular, fechaInicio, fechaFin) VALUES (?,?,?,?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->numCelular, PDO::PARAM_STR);
            $query->bindValue(3, $this->fechaInicio, PDO::PARAM_STR);
            $query->bindValue(4, $this->fechaFin, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('celular::save()->' . $e->getMessage());
            return false;
        }
    }
}
