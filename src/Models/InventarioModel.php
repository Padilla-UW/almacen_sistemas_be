<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;
use PDO;
use PDOException;

class InventarioModel extends Model
{
    protected $id;
    protected $idArea;
    protected $idUbicacion;
    protected $fecha;
    protected $observacion;
    protected $detalles;

    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {

        $pdo = new Model();
        $c = $pdo->connect();
        try {
            $pdo = new Model();
            $c = $pdo->connect();
            $c->beginTransaction();
            $query = $c->prepare("INSERT INTO inventario (idArea,idUbicacion,fecha,observacion) VALUES(:id_area,:id_ubicacion,:fecha,:observacion)");
            $query->bindValue(':id_area', $this->idArea, PDO::PARAM_INT);
            $query->bindValue(':id_ubicacion', $this->idUbicacion, PDO::PARAM_INT);
            $query->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $query->bindValue(':observacion', $this->observacion, PDO::PARAM_STR);
            if ($query->execute()) {
                $this->setId($c->lastInsertId());

                foreach ($this->detalles as $equipo) {
                    $queryDetelle = $c->prepare("INSERT INTO detalle_inventario (idInventario,idEquipo,status,observacion) VALUES(:id_inventario,:id_equipo,:status,:observacion)");
                    $queryDetelle->bindValue(':id_inventario', $this->id, PDO::PARAM_INT);
                    $queryDetelle->bindValue(':id_equipo', $equipo['idEquipo'], PDO::PARAM_INT);
                    $queryDetelle->bindValue(':status', $equipo['status'], PDO::PARAM_STR);
                    $queryDetelle->bindValue(':observacion', ($equipo['observacion']) ? $equipo['observacion'] : '', PDO::PARAM_INT);
                    $queryDetelle->execute();
                }
            }
            $c->commit();
            return array("ok" => true, "msj" => "Inventario guardado");
        } catch (PDOException $e) {
            $c->rollBack();
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public function getInventarios()
    {
        $pdo = new Model();
        try {
            $arrayFilters = array();
            $arrayParams = array();
            if ($this->idArea != '') {
                $arrayFilters[] = " i.idArea = :id_area ";
                $arrayParams[':id_area'] = $this->idArea;
            }

            if ($this->idUbicacion != '') {
                $arrayFilters[] = " i.idUbicacion = :id_ubicacion ";
                $arrayParams[':id_ubicacion'] = $this->idUbicacion;
            }

            if ($this->fecha != '') {
                $arrayFilters[] = " i.fecha = :fecha ";
                $arrayParams[':fecha'] = $this->fecha;
            }

            $sqlFiltros = "";
            if ($arrayFilters != "") {
                for ($i = 0; $i < count($arrayFilters); $i++) {
                    if ($i == 0) {
                        $sqlFiltros .= " WHERE " . $arrayFilters[$i];
                    } else {
                        $sqlFiltros .= " AND " . $arrayFilters[$i];
                    }
                }
            }

            $sql = "SELECT * FROM inventario i 
            INNER JOIN area_persona a ON i.idArea = a.idArea
            INNER JOIN ubicacion_persona u ON i.idUbicacion = u.idUbicacion";
            $sql .= $sqlFiltros;
            $query = $pdo->prepare($sql);
            $query->execute($arrayParams);
            return array("ok" => true, "inventarios" => $query->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log('EquipoModel::getEquipos -> ' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }


    public function getDetallesInv()
    {
        try {
            $query = $this->prepare("SELECT d.idDetalle ,e.marca, t.tipo, e.modelo, e.numSerie, CONCAT(per.nombre,' ', per.apellidos) AS responsable, 
            a.area,u.ubicacion, d.status FROM detalle_inventario d 
            INNER JOIN inventario i ON d.idInventario = i.idInventario
            INNER JOIN equipo e ON d.idEquipo = e.idEquipo
            INNER JOIN tipo_equipo t ON e.idTipo = t.idTipo
            LEFT JOIN persona per ON e.idPersona = per.idPersona
            LEFT JOIN area_persona a ON per.idArea = a.idArea
            LEFT JOIN ubicacion_persona u ON per.idUbicacion = u.idUbicacion
            WHERE i.idInventario = :id_inventario");
            $query->bindValue(':id_inventario', $this->id, PDO::PARAM_INT);
            $query->execute();
            return array("ok" => true, "detalles" => $query->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log('EquipoModel::getEquipos -> ' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }
    /**
     * Get the value of idArea
     */
    public function getIdArea()
    {
        return $this->idArea;
    }

    /**
     * Set the value of idArea
     *
     * @return  self
     */
    public function setIdArea($idArea)
    {
        $this->idArea = $idArea;

        return $this;
    }

    /**
     * Get the value of idUbicacion
     */
    public function getIdUbicacion()
    {
        return $this->idUbicacion;
    }

    /**
     * Set the value of idUbicacion
     *
     * @return  self
     */
    public function setIdUbicacion($idUbicacion)
    {
        $this->idUbicacion = $idUbicacion;

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
     * Get the value of observacion
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set the value of observacion
     *
     * @return  self
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get the value of detalles
     */
    public function getDetalles()
    {
        return $this->detalles;
    }

    /**
     * Set the value of detalles
     *
     * @return  self
     */
    public function setDetalles($detalles)
    {
        $this->detalles = $detalles;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
