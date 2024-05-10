<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;
use PDO;
use PDOException;

class ProveedorModel extends Model
{

    protected $id;
    protected $nombre;
    protected $apellidos;
    protected $telefono;
    protected $razonSocial;

    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {

        try {

            $queryValidacion = $this->prepare("SELECT * FROM proveedor WHERE nombre = ? AND apellidos = ?");
            $queryValidacion->bindValue(1, $this->nombre, PDO::PARAM_STR);
            $queryValidacion->bindValue(2, $this->apellidos, PDO::PARAM_STR);
            $queryValidacion->execute();

            if ($queryValidacion->rowCount() > 0) {
                return array("ok" => false, "msj" => "Proveedor ya registrado");
            }

            $query = $this->prepare("INSERT INTO proveedor (nombre, apellidos, telefono, razonSocial ) VALUES (:nombre,:apellidos,:telefono,:razonSocial)");
            $query->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $query->bindValue(':apellidos', $this->apellidos, PDO::PARAM_STR);
            $query->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
            $query->bindValue(':razonSocial', $this->razonSocial, PDO::PARAM_STR);
            if ($query->execute()) {
                return array("ok" => true);
            }
        } catch (PDOException $e) {
            error_log('Disco externo::delete()->' . $e->getMessage());
            return array("ok" => false, "message" => $e->getMessage());
        }
    }

    public function get()
    {

        try {

            $queryValidacion = $this->prepare("SELECT * FROM proveedor WHERE nombre = ? AND apellidos = ?");
            $queryValidacion->bindValue(1, $this->nombre, PDO::PARAM_STR);
            $queryValidacion->bindValue(2, $this->apellidos, PDO::PARAM_STR);
            $queryValidacion->execute();

            if ($queryValidacion->rowCount() > 0) {
                return array("ok" => false, "msj" => "Proveedor ya registrado");
            }

            $query = $this->prepare("INSERT INTO proveedor (nombre, apellidos, telefono, razonSocial ) VALUES (:nombre,:apellidos,:telefono,:razonSocial)");
            $query->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $query->bindValue(':apellidos', $this->apellidos, PDO::PARAM_STR);
            $query->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
            $query->bindValue(':razonSocial', $this->razonSocial, PDO::PARAM_STR);
            if ($query->execute()) {
                return array("ok" => true);
            }
        } catch (PDOException $e) {
            error_log('Disco externo::delete()->' . $e->getMessage());
            return array("ok" => false, "message" => $e->getMessage());
        }
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

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of apellidos
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set the value of apellidos
     *
     * @return  self
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get the value of telefono
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of razonSocial
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set the value of razonSocial
     *
     * @return  self
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = $razonSocial;

        return $this;
    }
}
