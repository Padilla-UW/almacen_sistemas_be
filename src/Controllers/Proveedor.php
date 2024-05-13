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

    public function get()
    {
        $proveedor = new ProveedorModel();
        (isset($_GET['nombre'])) && $proveedor->setNombre($_GET['nombre']);
        (isset($_GET['apellidos'])) && $proveedor->setApellidos($_GET['apellidos']);
        (isset($_GET['telefono'])) && $proveedor->setTelefono($_GET['telefono']);
        (isset($_GET['status'])) && $proveedor->setStatus($_GET['status']);
        (isset($_GET['idProveedor'])) && $proveedor->setId($_GET['idProveedor']);

        $this->response($proveedor->get());
    }

    public function edit()
    {
        $proveedor = new ProveedorModel();
        $this->exists(['idProveedor', 'nombre', 'apellidos']);
        $proveedor = new ProveedorModel();
        $proveedor->setId($this->data['idProveedor']);
        $proveedor->setNombre($this->data['nombre']);
        $proveedor->setApellidos($this->data['apellidos']);
        (isset($this->data['telefono'])) && $proveedor->setTelefono($this->data['telefono']);
        (isset($this->data['razonSocial'])) && $proveedor->setRazonSocial($this->data['razonSocial']);

        $this->response($proveedor->edit());
    }
}
