<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;

use PDO;
use PDOException;

class EquipoModel extends Model
{
    public $id;
    public $idTipo;
    public $tipo;
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
            $arrayParams = array();
            if ($numSerie != '') {
                $arrayFilters[] = " e.numSerie LIKE :num_serie ";
                $arrayParams[':num_serie'] = "%$numSerie%";
            }

            if ($idType != 0) {
                $arrayFilters[] = " e.idTipo = :id_tipo ";
                $arrayParams[':id_tipo'] = $idType;
            }

            if ($idArea != 0) {
                $arrayFilters[] = " p.idArea = :id_area ";
                $arrayParams[':id_area'] = $idArea;
            }

            if ($idPersona != 0) {
                $arrayFilters[] = " p.idPersona = :id_persona ";
                $arrayParams[':id_persona'] = $idPersona;
            }
            if ($status != '') {
                $arrayFilters[] = " e.status = :status ";
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

            $sql = "SELECT e.idEquipo, e.numSerie, t.tipo, a.area, p.nombre,e.fechaCompra, e.status, e.modelo FROM equipo e 
            INNER JOIN tipo_equipo t ON e.idTipo = t.idTipo
            LEFT JOIN persona p ON p.idPersona = e.idPersona
            LEFT JOIN area_persona a ON p.idArea = a.idArea";

            $sql .= $sqlFiltros;
            $query = $pdo->prepare($sql);

            $query->execute($arrayParams);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('EquipoModel::getEquipos -> ' . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public function save($data)
    {
        try {
            $c = $this->connect();
            $c->beginTransaction();

            if ($this->numSerie == '') {
                return array("ok" => false, "msj" => "Numero de serie vacio");
            }

            $queryValidacion = $c->prepare("SELECT * FROM equipo WHERE numSerie = ?");
            $queryValidacion->bindValue(1, $this->numSerie, PDO::PARAM_STR);
            $queryValidacion->execute();

            if ($queryValidacion->rowCount() > 0) {
                return array("ok" => false, "msj" => "Numero de serie ya registrado");
            }

            $query = $c->prepare("INSERT INTO equipo (idTipo, idPersona, idProveedor, marca, modelo, numSerie, status, fechaCompra,numFactura,observaciones ) VALUES (?,?,?,?,?,?,?,?,?,?) ");
            $query->bindValue(1, $this->idTipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->idPersona, PDO::PARAM_INT);
            $query->bindValue(3, $this->idProveedor, PDO::PARAM_INT);
            $query->bindValue(4, $this->marca, PDO::PARAM_STR);
            $query->bindValue(5, $this->modelo, PDO::PARAM_STR);
            $query->bindValue(6, $this->numSerie, PDO::PARAM_STR);
            $query->bindValue(7, $this->status, PDO::PARAM_STR);
            $query->bindValue(8, $this->fechaCompra, PDO::PARAM_STR);
            $query->bindValue(9, $this->numFactura, PDO::PARAM_STR);
            $query->bindValue(10, $this->observaciones, PDO::PARAM_STR);

            if ($query->execute()) {
                if ($this->idTipo == 1) {
                    if ($this->saveCpu($data, $c)) {
                        return array("ok" => true, "msj" => "Cpu agregada");
                    } else {
                        return array("ok" => false, "msj" => "Error al agregar Cpu");
                    }
                }
            }
        } catch (PDOException $e) {

            error_log('EquipoModel::save()->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public static function getTiposEquipo()
    {
        try {
            $pdo = new Model();
            $query = $pdo->query("SELECT * FROM  tipo_equipo");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('EquipoModel::getTipos -> ' . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public static function getDetallesEquipo($idEquipo)
    {
        try {
            $pdo = new Model();
            $sql = "SELECT t.tabla FROM equipo e INNER JOIN tipo_equipo t ON e.idTipo = t.idTipo WHERE e.idEquipo = :id_equipo ";
            $query = $pdo->prepare($sql);
            $query->execute([":id_equipo" => $idEquipo]);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) == 0) {
                return [];
            }

            $tabla = $result[0]["tabla"];
            $sqlDetalle = "SELECT * FROM equipo e INNER JOIN $tabla ON e.idEquipo = $tabla.idEquipo WHERE e.idEquipo = :id_equipo";
            $query = $pdo->prepare($sqlDetalle);
            $query->execute([":id_equipo" => $idEquipo]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public static function saveCpu($data, $c)
    {
        $cpu = new CpuModel();
        $cpu->idEquipo = $c->lastInsertId();
        $cpu->sistemaOperativo = ($data['sistemaOperativo']) ? $data['sistemaOperativo'] : null;
        $cpu->macAddress = ($data['macAddress']) ? $data['macAddress'] : '';
        $cpu->procesador = ($data['procesador']) ? $data['procesador'] : '';
        $cpu->tipo = ($data['tipoCpu']) ? $data['tipoCpu'] : null;
        $cpu->benchmark = ($data['benchmark']) ? $data['benchmark'] : '';
        $cpu->ligaBenchmark = ($data['ligaBenchmark']) ? $data['ligaBenchmark'] : '';
        $cpu->valuacion = ($data['valuacion']) ? $data['valuacion'] : '';
        $cpu->year = ($data['year']) ? $data['year'] : null;
        $cpu->ram = ($data['ram']) ? $data['ram'] : null;
        $cpu->expancionRam = ($data['expancionRam']) ? $data['expancionRam'] : null;
        $cpu->almacenamiento = ($data['almacenamiento']) ? $data['almacenamiento'] : null;
        $cpu->lugar = ($data['lugar']) ? $data['lugar'] : '';
        $cpu->certificado = ($data['certificado']) ? $data['certificado'] : null;
        $cpu->versionOffice = ($data['versionOffice']) ? $data['versionOffice'] : '';
        $cpu->tarjetaVideo = ($data['tarjetaVideo']) ? $data['tarjetaVideo'] : '';
        $cpu->tarjetaMadre = ($data['tarjetaMadre']) ? $data['tarjetaMadre'] : '';
        $cpu->otroSotfware = ($data['otroSotfware']) ? $data['otroSotfware'] : '';
        $cpu->precio = ($data['precio']) ? $data['precio'] : null;
        $cpu->valorDepreciado = ($data['valorDepreciado']) ? $data['valorDepreciado'] : null;
        $cpu->responsiva = ($data['responsiva']) ? $data['responsiva'] : '';
        $cpu->precioMercado = ($data['precioMercado']) ? $data['precioMercado'] : null;
        $cpu->fechaRenovacion = ($data['fechaRenovacion']) ? $data['fechaRenovacion'] : null;
        $cpu->numParte = ($data['numParte']) ? $data['numParte'] : '';

        if ($cpu->save($c)) {
            $c->commit();
            return true;
        } else {
            $c->rollBack();
            return false;
        }
    }

    public static function saveMonitor($data, $c)
    {
    }
}
