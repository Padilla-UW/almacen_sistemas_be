<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;
use PDO;
use PDOException;

class EquipoModel extends Model
{
    public $id;
    public $idTipo;
    public $idPersona;
    public $idProveedor;
    public $marca;
    public $modelo;
    public $numSerie;
    public $status;
    public $fechaCompra;
    public $numFactura;
    public $observaciones;
    public $firma;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getEquipos(string $numSerie = '', int $idType = 0, int $idArea = 0, int $idPersona = 0, string $status = '')
    {
        try {
            $pdo = new Model();
            $arrayFilters = array();
            if ($numSerie != '') {
                $arrayFilters[] = " e.numSerie LIKE :numSerie ";
                $arrayParams[':numSerie'] = "%$numSerie%";
            }

            if ($idType != 0) {
                $arrayFilters[] = " e.idTipo = '$idType' ";
                $arrayParams[':idTipo'] = $idType;
            }

            if ($idArea != 0) {
                $arrayFilters[] = " p.idArea = '$idArea' ";
                $arrayParams[':idArea'] = $idArea;
            }

            if ($idPersona != 0) {
                $arrayFilters[] = " p.idPersona = '$idPersona' ";
                $arrayParams[':idPersona'] = $idPersona;
            }
            if ($status != '') {
                $arrayFilters[] = " e.status = '$status' ";
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

            $sql = "SELECT * FROM equipo e 
            INNER JOIN tipo_equipo t ON e.idTipo = t.idTipo
            LEFT JOIN persona p ON p.idPersona = e.idPersona
            INNER JOIN area_persona a ON p.idArea = a.idArea";

            $sql .= $sqlFiltros;
            $query = $pdo->prepare($sql);


            foreach ($arrayParams as $key => $value) {
                $query->bindParam($key, $value);
            }


            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('EquipoModel::getEquipos -> ' . $e->getMessage());
            return false;
        }
    }
}
