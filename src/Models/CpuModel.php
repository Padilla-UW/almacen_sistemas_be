<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;
use PDO;
use PDOException;

class CpuModel extends Model
{
    public $id;
    public $idEquipo;
    public $tipo;
    public $sistemaOperativo;
    public $macAddress;
    public $procesador;
    public $benchmark;
    public $ligaBenchmark;
    public $valuacion;
    public $year;
    public $ram;
    public $expancionRam;
    public $tarjetaMadre;
    public $almacenamiento;
    public $lugar;
    public $certificado;
    public $versionOffice;
    public $tarjetaVideo;
    public $otroSotfware;
    public $precio;
    public $valorDepreciado;
    public $responsiva;
    public $precioMercado;
    public $fechaRenovacion;
    public $numParte;

    public function __construct()
    {
        parent::__construct();
    }

    public function save($c)
    {
        try {
            $query = $c->prepare("INSERT INTO cpu (idEquipo, tipo, sistemaOperativo, macAddress, procesador, benchmark,ligaBenchmark, valuacion, year, ram, expancionRam, tarjetaMadre, almacenamiento, lugar, certificado,	versionOffice, tarjetaVideo, otroSotfware, precio, valorDepreciado, responsiva, precioMercado,fechaRenovacion, numParte) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $query->bindValue(1, $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(2, $this->tipo, PDO::PARAM_STR);
            $query->bindValue(3, $this->sistemaOperativo, PDO::PARAM_STR);
            $query->bindValue(4, $this->macAddress, PDO::PARAM_STR);
            $query->bindValue(5, $this->procesador, PDO::PARAM_STR);
            $query->bindValue(6, $this->benchmark, PDO::PARAM_STR);
            $query->bindValue(7, $this->ligaBenchmark, PDO::PARAM_STR);
            $query->bindValue(8, $this->valuacion, PDO::PARAM_STR);
            $query->bindValue(9, $this->year, PDO::PARAM_STR);
            $query->bindValue(10, $this->ram, PDO::PARAM_INT);
            $query->bindValue(11, $this->expancionRam, PDO::PARAM_INT);
            $query->bindValue(12, $this->tarjetaMadre, PDO::PARAM_STR);
            $query->bindValue(13, $this->almacenamiento, PDO::PARAM_INT);
            $query->bindValue(14, $this->lugar, PDO::PARAM_STR);
            $query->bindValue(15, $this->certificado, PDO::PARAM_INT);
            $query->bindValue(16, $this->versionOffice, PDO::PARAM_STR);
            $query->bindValue(17, $this->tarjetaVideo, PDO::PARAM_STR);
            $query->bindValue(18, $this->otroSotfware, PDO::PARAM_STR);
            $query->bindValue(19, $this->precio, PDO::PARAM_STR);
            $query->bindValue(20, $this->valorDepreciado, PDO::PARAM_STR);
            $query->bindValue(21, $this->responsiva, PDO::PARAM_STR);
            $query->bindValue(22, $this->precioMercado, PDO::PARAM_STR);
            $query->bindValue(23, $this->fechaRenovacion, PDO::PARAM_STR);
            $query->bindValue(24, $this->numParte, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            error_log('Cpu::save()->' . $e->getMessage());
            return false;
        }
    }
}
