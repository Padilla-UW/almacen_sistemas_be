<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\TraspasoModel;

class Traspaso extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        try {
            $traspaso = new TraspasoModel();
            $idEquipo = (isset($_GET['idEquipo']) && $_REQUEST['idEquipo'] != NULL) ? $_GET['idEquipo'] : '';
            $traspaso->setIdEquipo($idEquipo);
            $this->response($traspaso->getTraspasos());
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public function create()
    {
        $this->exists(['idEquipo', 'idPersonaDestino']);
        $traspaso = new TraspasoModel();
        $traspaso->setIdEquipo(($this->data['idEquipo']) ? $this->data['idEquipo'] : null);
        $traspaso->setIdPersonaOrigen(($this->data['idPersonaOrigen']) ? $this->data['idPersonaOrigen'] : null);
        $traspaso->setIdPersonaDestino(($this->data['idPersonaDestino']) ? $this->data['idPersonaDestino'] : null);
        $traspaso->setObservaciones(($this->data['observaciones']) ? $this->data['observaciones'] : '');

        $this->response($traspaso->save());
    }
}
