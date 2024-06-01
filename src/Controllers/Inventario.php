<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\InventarioModel;

class Inventario extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        $this->exists(['idArea', 'idUbicacion', 'detalles']);
        $inventario = new InventarioModel();
        $inventario->setIdArea($this->data['idArea']);
        $inventario->setIdUbicacion($this->data['idUbicacion']);
        $inventario->setObservacion($this->data['observacion']);
        $inventario->setDetalles($this->data['detalles']);
        $inventario->setFecha(date('y-m-d'));
        $this->response($inventario->save());
    }

    public function get()
    {
        $inventario = new InventarioModel();
        $inventario->setIdArea(isset($_GET['idArea']) ? $_GET['idArea'] : '');
        $inventario->setIdUbicacion(isset($_GET['idUbicacion']) ? $_GET['idUbicacion'] : '');
        $inventario->setFecha(isset($_GET['fecha']) ? $_GET['fecha'] : '');

        $this->response($inventario->getInventarios());
    }
    public function getDetalles()
    {
        $inventario = new InventarioModel();
        $inventario->setId(isset($_GET['idInventario']) ? $_GET['idInventario'] : '');

        $this->response($inventario->getDetallesInv());
    }
}
