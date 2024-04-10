<?php

use ApiSistemas\Controllers\Equipo;
use ApiSistemas\Controllers\Inicio;
use Bramus\Router\Router;

$router = new Router();

$router->get('/equipos', function () {
    $equipos = new Equipo();
    $equipos->index();
});

$router->get('/', function () {
    new Inicio();
});

$router->set404(function () {
    echo json_encode(["message" => "404 not found"]);
});

$router->run();
