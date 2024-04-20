<?php

namespace ApiSistemas\Models;

use PDO;
use PDOException;

class CpuModel
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
            error_log('Cpu::save()->' . $e->getMessage());
            return false;
        }
    }


    public function edit($con)
    {
        try {

            $query = $con->prepare("UPDATE cpu SET tipo = :tipo, sistemaOperativo = :sistema_operativo, macAddress=:mac_address, procesador=:procesador, benchmark= :benchmark,ligaBenchmark = :liga_benchmark, valuacion = :valuacion, year=:year, ram=:ram, expancionRam = :expancion_ram, tarjetaMadre = :tarjeta_madre, almacenamiento = :almacenamiento, lugar = :lugar, certificado=:certificado,versionOffice = :version_office, tarjetaVideo = :tarjeta_video, otroSotfware = :otro_sotfware, precio = :precio, valorDepreciado = :valor_depreciado, responsiva = :responsiva, precioMercado = :precio_mercado,fechaRenovacion = :fecha_renovacion, numParte = :num_parte WHERE idEquipo = :id_equipo");

            $query->bindValue(':id_equipo', $this->idEquipo, PDO::PARAM_INT);
            $query->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $query->bindValue(':sistema_operativo', $this->sistemaOperativo, PDO::PARAM_STR);
            $query->bindValue(':mac_address', $this->macAddress, PDO::PARAM_STR);
            $query->bindValue(':procesador', $this->procesador, PDO::PARAM_STR);
            $query->bindValue(':benchmark', $this->benchmark, PDO::PARAM_STR);
            $query->bindValue(':liga_benchmark', $this->ligaBenchmark, PDO::PARAM_STR);
            $query->bindValue(':valuacion', $this->valuacion, PDO::PARAM_STR);
            $query->bindValue(':year', $this->year, PDO::PARAM_STR);
            $query->bindValue(':ram', $this->ram, PDO::PARAM_INT);
            $query->bindValue(':expancion_ram', $this->expancionRam, PDO::PARAM_INT);
            $query->bindValue(':tarjeta_madre', $this->tarjetaMadre, PDO::PARAM_STR);
            $query->bindValue(':almacenamiento', $this->almacenamiento, PDO::PARAM_INT);
            $query->bindValue(':lugar', $this->lugar, PDO::PARAM_STR);
            $query->bindValue(':certificado', $this->certificado, PDO::PARAM_INT);
            $query->bindValue(':version_office', $this->versionOffice, PDO::PARAM_STR);
            $query->bindValue(':tarjeta_video', $this->tarjetaVideo, PDO::PARAM_STR);
            $query->bindValue(':otro_sotfware', $this->otroSotfware, PDO::PARAM_STR);
            $query->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $query->bindValue(':valor_depreciado', $this->valorDepreciado, PDO::PARAM_STR);
            $query->bindValue(':responsiva', $this->responsiva, PDO::PARAM_STR);
            $query->bindValue(':precio_mercado', $this->precioMercado, PDO::PARAM_STR);
            $query->bindValue(':fecha_renovacion', $this->fechaRenovacion, PDO::PARAM_STR);
            $query->bindValue(':num_parte', $this->numParte, PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            error_log('Cpu::edit()->' . $e->getMessage());
            return false;
        }
    }
}
