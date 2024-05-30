<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class SmartTvModel
{
    public $idEquipo;
    public $size;

    public function save($c)
    {
        try {
            $query = $c->prepare("INSERT INTO  smart_tv (idEquipo, size) VALUES (?,?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->size, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('SmartTv::save()->' . $e->getMessage());
            return false;
        }
    }

    public function delete($con)
    {
        try {
            $query = $con->prepare("DELETE FROM  smart_tv  WHERE idEquipo = ? ");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('SmartTv::delete()->' . $e->getMessage());
            return false;
        }
    }
}
