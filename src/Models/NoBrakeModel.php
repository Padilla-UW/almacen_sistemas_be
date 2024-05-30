<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class NoBrakeModel
{
    public $id;
    public $idEquipo;

    public function save($c)
    {
        try {
            $query = $c->prepare("INSERT INTO  no_brake (idEquipo) VALUES (?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('NoBrakeModel::save()->' . $e->getMessage());
            return false;
        }
    }

    public function delete($con)
    {
        try {
            $query = $con->prepare("DELETE FROM  no_brake  WHERE idEquipo = ? ");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('NoBrake::delete()->' . $e->getMessage());
            return false;
        }
    }
}
