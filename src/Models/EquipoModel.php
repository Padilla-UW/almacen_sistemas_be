<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;

use PDO;
use PDOException;

class EquipoModel extends Model
{
    protected $id;
    protected $idTipo;
    protected $tipo;
    protected $idPersona;
    protected $idProveedor;
    protected $marca;
    protected $modelo;
    protected $numSerie;
    protected $status;
    protected $fechaCompra;
    protected $numFactura;
    protected $observaciones;
    protected $firma;

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

            $sql = "SELECT e.idEquipo, e.numSerie, t.tipo, a.area, p.idPersona, CONCAT(p.nombre, ' ', p.apellidos) AS nombre,e.fechaCompra, e.status, e.modelo FROM equipo e 
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
                switch ($this->idTipo) {
                    case 1:
                        if ($this->saveCpu($data, $c)) {
                            return array("ok" => true, "msj" => "Cpu agregada");
                        } else {
                            return array("ok" => false, "msj" => "Error al agregar Cpu");
                        }
                        break;
                    case 2:
                        if ($this->saveMonitor($data, $c)) {
                            return array("ok" => true, "msj" => "Monitor agregado");
                        } else {
                            return array("ok" => false, "msj" => "Error al agregar Monitor");
                        }
                        break;
                    case 5:
                        if($this->saveDisco($data, $c)) {
                            return array("ok" => true, "msj" => "Disco externo agregado");
                        } else {
                            return array("ok" => false, "msj" => "Error al agregar Disco externo");
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
        $monitor = new MonitorModel();
        $monitor->idEquipo = $c->lastInsertId();
        $monitor->pulgadas = $data['pulgadas'];
        if ($monitor->save($c)) {
            $c->commit();
            return true;
        } else {
            $c->rollBack();
            return false;
        }
    }

    public static function saveDisco($data, $c)
    {
        $disco = new DiscoModel();
        $disco->idEquipo = $c->lastInsertId();
        $disco->capacidad = $data['capacidad'];
        if($disco->save($c)){
            $c->commit();
            return true;
        } else {
            $c->rollBack();
            return false;
        }
    }

    public function edit($data)
    {
        try {
            $pdo = new Model();
            $con = $pdo->connect();
            $con->beginTransaction();

            $query = $con->prepare("UPDATE equipo SET idTipo = :id_tipo, idPersona=:id_persona, idProveedor = :id_proveedor, marca=:marca, modelo=:modelo, numSerie = :num_serie, status=:status, fechaCompra =:fecha_compra,numFactura = :num_factura,observaciones=:observaciones WHERE idEquipo = :id_equipo");
            $query->bindValue(':id_equipo', $this->id, PDO::PARAM_INT);
            $query->bindValue(':id_tipo', $this->idTipo, PDO::PARAM_INT);
            $query->bindValue(':id_persona', $this->idPersona, PDO::PARAM_INT);
            $query->bindValue(':id_proveedor', $this->idProveedor, PDO::PARAM_INT);
            $query->bindValue(':marca', $this->marca, PDO::PARAM_STR);
            $query->bindValue(':modelo', $this->modelo, PDO::PARAM_STR);
            $query->bindValue(':num_serie', $this->numSerie, PDO::PARAM_STR);
            $query->bindValue(':status', $this->status, PDO::PARAM_STR);
            $query->bindValue(':fecha_compra', $this->fechaCompra, PDO::PARAM_STR);
            $query->bindValue(':num_factura', $this->numFactura, PDO::PARAM_STR);
            $query->bindValue(':observaciones', $this->observaciones, PDO::PARAM_STR);
            if ($query->execute()) {
                switch ($this->idTipo) {
                    case 1:
                        if ($this->editCpu($data, $con)) {
                            return array("ok" => true, "msj" => "Cpu editada");
                        } else {
                            return array("ok" => false, "msj" => "Error al editar Cpu");
                        }
                        break;
                }
            }
        } catch (PDOException $e) {
            error_log('EquipoModel::edit()->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public static function editCpu($data, $c)
    {
        $cpu = new CpuModel();
        // $cpu->id = $data['idCpu'];
        $cpu->idEquipo = $data['idEquipo'];
        $cpu->sistemaOperativo = (isset($data['sistemaOperativo'])) ? $data['sistemaOperativo'] : '';
        $cpu->macAddress = (isset($data['macAddress'])) ? $data['macAddress'] : '';
        $cpu->procesador = (isset($data['procesador'])) ? $data['procesador'] : '';
        $cpu->tipo = (isset($data['tipoCpu'])) ? $data['tipoCpu'] : null;
        $cpu->benchmark = (isset($data['benchmark'])) ? $data['benchmark'] : '';
        $cpu->ligaBenchmark = (isset($data['ligaBenchmark'])) ? $data['ligaBenchmark'] : '';
        $cpu->valuacion = (isset($data['valuacion'])) ? $data['valuacion'] : '';
        $cpu->year = (isset($data['year'])) ? $data['year'] : null;
        $cpu->ram = (isset($data['ram'])) ? $data['ram'] : null;
        $cpu->expancionRam = (isset($data['expancionRam'])) ? $data['expancionRam'] : null;
        $cpu->almacenamiento = (isset($data['almacenamiento'])) ? $data['almacenamiento'] : null;
        $cpu->lugar = (isset($data['lugar'])) ? $data['lugar'] : '';
        $cpu->certificado = (isset($data['certificado'])) ? $data['certificado'] : null;
        $cpu->versionOffice = (isset($data['versionOffice'])) ? $data['versionOffice'] : '';
        $cpu->tarjetaVideo = (isset($data['tarjetaVideo'])) ? $data['tarjetaVideo'] : '';
        $cpu->tarjetaMadre = (isset($data['tarjetaMadre'])) ? $data['tarjetaMadre'] : '';
        $cpu->otroSotfware = (isset($data['otroSotfware'])) ? $data['otroSotfware'] : '';
        $cpu->precio = (isset($data['precio'])) ? $data['precio'] : null;
        $cpu->valorDepreciado = (isset($data['valorDepreciado'])) ? $data['valorDepreciado'] : null;
        $cpu->responsiva = (isset($data['responsiva'])) ? $data['responsiva'] : '';
        $cpu->precioMercado = (isset($data['precioMercado'])) ? $data['precioMercado'] : null;
        $cpu->fechaRenovacion = (isset($data['fechaRenovacion'])) ? $data['fechaRenovacion'] : null;
        $cpu->numParte = (isset($data['numParte'])) ? $data['numParte'] : '';
        echo "aqui " . $cpu->edit($c) . " -";
        if ($cpu->edit($c)) {
            $c->commit();
            return true;
        } else {
            $c->rollBack();
            return false;
        }
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdTipo()
    {
        return $this->idTipo;
    }

    public function setIdTipo($idTipo)
    {
        $this->idTipo = $idTipo;

        return $this;
    }

    public function getIdPersona()
    {
        return $this->idPersona;
    }

    public function setIdPersona($idPersona)
    {
        $this->idPersona = $idPersona;
    }

    public function getFirma()
    {
        return $this->firma;
    }

    public function setFirma($firma)
    {
        $this->firma = $firma;
    }

    public function getObservaciones()
    {
        return $this->observaciones;
    }

    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    public function getIdProveedor()
    {
        return $this->idProveedor;
    }

    public function setIdProveedor($idProveedor)
    {
        $this->idProveedor = $idProveedor;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    public function getModelo()
    {
        return $this->modelo;
    }

    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }


    public function getNumSerie()
    {
        return $this->numSerie;
    }

    public function setNumSerie($numSerie)
    {
        $this->numSerie = $numSerie;
    }


    public function getStatus()
    {
        return $this->status;
    }


    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getFechaCompra()
    {
        return $this->fechaCompra;
    }

    public function setFechaCompra($fechaCompra)
    {
        $this->fechaCompra = $fechaCompra;
    }

    public function getNumFactura()
    {
        return $this->numFactura;
    }

    public function setNumFactura($numFactura)
    {
        $this->numFactura = $numFactura;
    }
}
