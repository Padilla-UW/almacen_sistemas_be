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
}
