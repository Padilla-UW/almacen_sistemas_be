<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Controllers\Equipo;

class Cpu extends Equipo
{
    public $idEquipo;
    public function __construct()
    {
        parent::__construct();
    }

    public function createCpu($idEquipo)
    {
        $cpu = new Cpu();
        $cpu->idEquipo = $idEquipo;
        echo $idEquipo;
    }
}
