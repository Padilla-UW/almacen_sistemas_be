<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class MonitorModel
{
    public $id;
    public $idEquipo;
    public $pulgadas;

    public function save($c)
    {
        try {
            $query = $c->prepare("INSERT INTO monitor (idEquipo, pulgadas) VALUES (?,?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->pulgadas, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('Monitor::save()->' . $e->getMessage());
            return false;
        }
    }

    public function edit($c)
    {
        try {
            $query = $c->prepare("UPDATE monitor SET pulgadas = :pulgadas WHERE idEquipo = :id_equipo");
            $query->bindValue(':pulgadas', $this->pulgadas, PDO::PARAM_STR);
            $query->bindValue(':id_equipo', $this->idEquipo, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('Monitor::edit()->' . $e->getMessage());
            return false;
        }
    }
}
