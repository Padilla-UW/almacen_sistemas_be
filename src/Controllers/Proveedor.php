<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\ProveedorModel;

class Proveedor extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        $this->exists(['nombre', 'apellidos']);
        $proveedor = new ProveedorModel();
        $proveedor->setNombre($this->data['nombre']);
        $proveedor->setApellidos($this->data['apellidos']);
        (isset($this->data['telefono'])) && $proveedor->setTelefono($this->data['telefono']);
        (isset($this->data['razonSocial'])) && $proveedor->setRazonSocial($this->data['razonSocial']);
        $this->response($proveedor->save());
    }
}
