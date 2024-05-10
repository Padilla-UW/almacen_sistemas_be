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

    public function edit($c)
    {
        try {
            $query = $c->prepare("UPDATE impresora SET impresionesXMes = :impresiones WHERE idEquipo = :id_equipo");
            $query->bindValue(':impresiones', $this->impresionesXMes, PDO::PARAM_STR);
            $query->bindValue(':id_equipo', $this->idEquipo, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('Impresora::edit()->' . $e->getMessage());
            return false;
        }
    }

    public function delete($con)
    {
        try {
            $query = $con->prepare("DELETE FROM  impresora  WHERE idEquipo = ? ");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('Impresora::delete()->' . $e->getMessage());
            return false;
        }
    }
}
