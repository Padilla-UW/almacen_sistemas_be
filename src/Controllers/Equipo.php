<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\EquipoModel;

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
}
