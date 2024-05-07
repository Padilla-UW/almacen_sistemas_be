<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;
use PDO;
use PDOException;

class TraspasoModel extends Model
{
    protected $id;
    protected $idEquipo;
    protected $idPersonaOrigen;
    protected $idPersonaDestino;
    protected $fecha;
    protected $observaciones;

    public function save()
    {

        try {
            $c = $this->connect();

            $c->beginTransaction();

            $queryActual = $c->prepare("SELECT idPersona FROM equipo WHERE idEquipo = ? ");
            $queryActual->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $queryActual->execute();
            $equipo = $queryActual->fetch(PDO::FETCH_ASSOC);
            $this->idPersonaOrigen = $equipo['idPersona'];

            if ($this->idPersonaOrigen == $this->idPersonaDestino) {
                return array("ok" => false, "msj" => "El equipo ya está asignado a esa persona");
            }

            $queryEquipo = $c->prepare("UPDATE equipo SET idPersona = ? WHERE idEquipo = ?");
            $queryEquipo->bindValue(1, $this->idPersonaDestino, PDO::PARAM_INT);
            $queryEquipo->bindValue(2, $this->idEquipo, PDO::PARAM_INT);
            $queryEquipo->execute();

            $query = $c->prepare("INSERT INTO traspaso (idEquipo, idPersonaOrigen, 	idPersonaDestino,  observaciones ) VALUES (?,?,?,?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->idPersonaOrigen, PDO::PARAM_INT);
            $query->bindValue(3, $this->idPersonaDestino, PDO::PARAM_INT);
            $query->bindValue(4, $this->observaciones, PDO::PARAM_STR);
            if ($query->execute()) {
                $c->commit();
                return array("ok" => true, "msj" =>  "Traspaso guardado");
            }
            $c->rollBack();
            return array("ok" => false, "msj" => "Error al guardar traspaso");
        } catch (PDOException $e) {
            error_log('Traspaso::save()->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public function getTraspasos()
    {
        try {
            $c = $this->connect();
            $query = $c->prepare("SELECT t.idTraspaso, t.observaciones, t.fecha, CONCAT(p.nombre, ' ', p.apellidos) AS origen,
            CONCAT(p2.nombre, ' ', p2.apellidos) AS destino, e.marca, e.modelo, e.numSerie, tp.tipo
                FROM traspaso t 
                INNER JOIN equipo e ON t.idEquipo =  e.idEquipo
                INNER JOIN tipo_equipo tp ON e.idTipo = tp.idTipo
                LEFT JOIN persona p ON p.idPersona =  t.idPersonaOrigen  
                LEFT JOIN persona p2 ON p2.idPersona =  t.idPersonaDestino
                WHERE t.idEquipo = ? ");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Traspaso::save()->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    /**
     * Get the value of idPersonaDestino
     */
    public function getIdPersonaDestino()
    {
        return $this->idPersonaDestino;
    }

    /**
     * Set the value of idPersonaDestino
     *
     * @return  self
     */
    public function setIdPersonaDestino($idPersonaDestino)
    {
        $this->idPersonaDestino = $idPersonaDestino;

        return $this;
    }

    /**
     * Get the value of fecha
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of observaciones
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set the value of observaciones
     *
     * @return  self
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get the value of idPersonaOrigen
     */
    public function getIdPersonaOrigen()
    {
        return $this->idPersonaOrigen;
    }

    /**
     * Set the value of idPersonaOrigen
     *
     * @return  self
     */
    public function setIdPersonaOrigen($idPersonaOrigen)
    {
        $this->idPersonaOrigen = $idPersonaOrigen;

        return $this;
    }

    /**
     * Get the value of idEquipo
     */
    public function getIdEquipo()
    {
        return $this->idEquipo;
    }

    /**
     * Set the value of idEquipo
     *
     * @return  self
     */
    public function setIdEquipo($idEquipo)
    {
        $this->idEquipo = $idEquipo;

        return $this;
    }
}
