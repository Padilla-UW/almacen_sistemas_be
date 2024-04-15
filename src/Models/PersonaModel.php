<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;
use PDO;
use PDOException;

class PersonaModel extends Model
{
    public  $idPersona;
    public  $idArea;
    public  $idUbicacion;
    public  $idNivel;
    public  $idResponsable;
    public  $nivelNum;
    public  $nombre;
    public  $apellidos;
    public  $status;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getPersonas(int $idPersona = NULL, int $idArea = NULL, int $idUbicacion = NULL, int $idResponsable = NULL, string $nombre = NULL, string $status = NULL)
    {
        try {
            $pdo = new Model();
            $arrayFilters = array();
            $arrayParams = array();
            if (isset($nombre)) {
                $arrayFilters[] = " p.nombre LIKE :nombre ";
                $arrayParams[':nombre'] = "%$nombre%";
            }

            if (isset($idUbicacion)) {
                $arrayFilters[] = " p.idUbicacion = :id_ubicacion ";
                $arrayParams[':id_ubicacion'] = $idUbicacion;
            }

            if (isset($idArea)) {
                $arrayFilters[] = " p.idArea = :id_area ";
                $arrayParams[':id_area'] = $idArea;
            }

            if (isset($idPersona)) {
                $arrayFilters[] = " p.idPersona = :id_persona ";
                $arrayParams[':id_persona'] = $idPersona;
            }
            if (isset($idResponsable)) {
                $arrayFilters[] = " p.idResponsable = :id_responsable ";
                $arrayParams[':id_responsable'] = $idResponsable;
            }
            if (isset($status)) {
                $arrayFilters[] = " p.status = :status ";
                $arrayParams[':status'] = $status;
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

            $sql = "SELECT p.idPersona, p.idArea, p.idUbicacion, p.idNivel, p.idResponsable, p.nombre, p.apellidos, CONCAT(res.nombre, ' ', res.apellidos) AS responsable, a.area, u.ubicacion, p.status FROM persona p INNER JOIN area_persona a ON p.idArea = a.idArea
            INNER JOIN ubicacion_persona u ON p.idUbicacion = u.idUbicacion 
            LEFT JOIN persona res ON p.idResponsable = res.idPersona";

            $sql .= $sqlFiltros;
            $query = $pdo->prepare($sql);

            $query->execute($arrayParams);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('EquipoModel::getEquipos -> ' . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }


    public static function getAreas()
    {
        try {
            $pdo = new Model();
            $query = $pdo->query("SELECT * FROM  area_persona");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PersonaModel::getAreas -> ' . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public static function getUbicaciones()
    {
        try {
            $pdo = new Model();
            $query = $pdo->query("SELECT * FROM  ubicacion_persona");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PersonaModel::getUbicaciones -> ' . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }


    public function save()
    {
        try {
            $query = $this->prepare("INSERT INTO persona (idArea, idUbicacion, idNivel, idResponsable, nivelNum, nombre, apellidos, status ) VALUES (?,?,?,?,?,?,?,?) ");
            $query->bindValue(1, $this->idArea, PDO::PARAM_INT);
            $query->bindValue(2, $this->idUbicacion, PDO::PARAM_INT);
            $query->bindValue(3, $this->idNivel, PDO::PARAM_INT);
            $query->bindValue(4, $this->idResponsable, PDO::PARAM_INT);
            $query->bindValue(5, $this->nivelNum, PDO::PARAM_INT);
            $query->bindValue(6, $this->nombre, PDO::PARAM_STR);
            $query->bindValue(7, $this->apellidos, PDO::PARAM_STR);
            $query->bindValue(8, $this->status, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('PersonaModel::save()->' . $e->getMessage());
            return false;
        }
    }
}
