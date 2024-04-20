<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\EquipoModel;
use CpuModel;

class Equipo extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $numSerie = (isset($_GET['numSerie']) && $_REQUEST['numSerie'] != NULL) ? $_GET['numSerie'] : '';
        $idTipo = (isset($_GET['idTipo']) && $_REQUEST['idTipo'] != NULL) ? $_GET['idTipo'] : 0;
        $idArea = (isset($_GET['idArea']) && $_REQUEST['idArea'] != NULL) ? $_GET['idArea'] : 0;
        $idPersona = (isset($_GET['idPersona']) && $_REQUEST['idPersona'] != NULL) ? $_GET['idPersona'] : 0;
        $status = (isset($_GET['status']) && $_REQUEST['status'] != NULL) ? $_GET['status'] : '';

        $this->response(["equipos" => EquipoModel::getEquipos($numSerie, $idTipo, $idArea, $idPersona, $status)]);
    }

    public function create()
    {
        $this->exists(['idTipo', 'numSerie']);
        $equipo = new EquipoModel();
        $equipo->setIdTipo(($this->data['idTipo']) ? $this->data['idTipo'] : null);
        $equipo->setIdPersona(($this->data['idPersona']) ? $this->data['idPersona'] : null);
        $equipo->setIdProveedor(($this->data['idProveedor']) ? $this->data['idProveedor'] : null);
        $equipo->setMarca(($this->data['marca']) ? $this->data['marca'] : '');
        $equipo->setModelo(($this->data['modelo']) ? $this->data['modelo'] : '');
        $equipo->setNumSerie(($this->data['numSerie']) ? $this->data['numSerie'] : '');
        $equipo->setFechaCompra(($this->data['fechaCompra']) ? $this->data['fechaCompra'] : '');
        $equipo->setNumFactura(($this->data['numFactura']) ? $this->data['numFactura'] : '');
        $equipo->setObservaciones(($this->data['observaciones']) ? $this->data['observaciones'] : '');
        $equipo->setStatus('activo');

        $res = $equipo->save($this->data);
        if (!$res['ok']) {
            $this->response(array("ok" => false, "msj" => $res['msj']));
        }

        $this->response(array("ok" => true, "msj" => $res['msj']));
    }

    public function getTipos()
    {
        $this->response(["tipos" => EquipoModel::getTiposEquipo()]);
    }

    public function getDetalles()
    {
        $idEquipo = (isset($_GET['idEquipo']) && $_REQUEST['idEquipo'] != NULL) ? $_GET['idEquipo'] : '';
        $this->response(["detalles" => EquipoModel::getDetallesEquipo($idEquipo)]);
    }

    public function edit()
    {
        $this->exists(['idEquipo', 'idTipo', 'numSerie']);
        $equipo = new EquipoModel();
        $equipo->setId(($this->data['idEquipo']) ? $this->data['idEquipo'] : null);
        $equipo->setIdTipo(($this->data['idTipo']) ? $this->data['idTipo'] : null);
        $equipo->setIdPersona(($this->data['idPersona']) ? $this->data['idPersona'] : null);
        $equipo->setIdProveedor(($this->data['idProveedor']) ? $this->data['idProveedor'] : null);
        $equipo->setMarca(($this->data['marca']) ? $this->data['marca'] : '');
        $equipo->setModelo(($this->data['modelo']) ? $this->data['modelo'] : '');
        $equipo->setNumSerie(($this->data['numSerie']) ? $this->data['numSerie'] : '');
        $equipo->setFechaCompra(($this->data['fechaCompra']) ? $this->data['fechaCompra'] : '');
        $equipo->setNumFactura(($this->data['numFactura']) ? $this->data['numFactura'] : '');
        $equipo->setObservaciones(($this->data['observaciones']) ? $this->data['observaciones'] : '');
        $equipo->setStatus(($this->data['status']) ? $this->data['status'] : '');

        $this->response(array("ok" => true, "msj" => $equipo->edit($this->data)));
    }
}
