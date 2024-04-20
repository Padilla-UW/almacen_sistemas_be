<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\PersonaModel;

class Persona extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAreas()
    {
        $this->response(["areas" => PersonaModel::getAreas()]);
    }

    public function getUbicaciones()
    {
        $this->response(["ubicaciones" => PersonaModel::getUbicaciones()]);
    }

    public function create()
    {
        $persona = new PersonaModel();
        $persona->idArea = $this->data['idArea'];
        $persona->idUbicacion = $this->data['idUbicacion'];
        $persona->idNivel = $this->data['idNivel'];
        $persona->idResponsable = $this->data['idResponsable'];
        $persona->nombre = $this->data['nombre'];
        $persona->apellidos = $this->data['apellidos'];
        $persona->nivelNum = $this->data['nivelNum'];
        $persona->status = 'activo';
        if ($persona->save()) {
            $this->response(["Message"]);
        }
    }



    public function getPersonas()
    {
        $idPersona = (isset($_GET['idPersona']) && $_GET['idPersona'] != '') ? $_GET['idPersona'] : null;
        $idArea = (isset($_GET['idArea']) && $_GET['idArea'] != '') ? $_GET['idArea'] : null;
        $idUbicacion = (isset($_GET['idUbicacion']) && $_GET['idUbicacion'] != '') ? $_GET['idUbicacion'] : null;
        $idResponsable = (isset($_GET['idResponsable']) && $_GET['idResponsable'] != '') ? $_GET['idResponsable'] : null;
        $nombre = (isset($_GET['nombre'])) ? $_GET['nombre'] : null;
        $status = (isset($_GET['status'])) ? $_GET['status'] : null;

        $this->response(["personas" => PersonaModel::getPersonas($idPersona, $idArea, $idUbicacion, $idResponsable, $nombre, $status)]);
    }
}
