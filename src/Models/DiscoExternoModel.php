<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class DiscoExternoModel
{
    public $idEquipo;
    public $capacidad;

    public function save($c)
    {
        try {
            $query = $c->prepare("INSERT INTO  disco_externo (idEquipo, capacidad) VALUES (?,?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->capacidad, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('discoExterno::save()->' . $e->getMessage());
            return false;
        }
    }

    public function delete($con)
    {
        try {
            $query = $con->prepare("DELETE FROM disco_externo  WHERE idEquipo = ? ");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('Disco externo::delete()->' . $e->getMessage());
            return false;
        }
    }
}
