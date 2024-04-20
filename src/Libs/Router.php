<?php

use ApiSistemas\Controllers\Equipo;
use ApiSistemas\Controllers\Inicio;
use ApiSistemas\Controllers\Persona;
use Bramus\Router\Router;

$router = new Router();

$router->get('/equipos', function () {
    $equipos = new Equipo();
    $equipos->index();
});

$router->get('/personas', function () {
    $persona = new Persona();
    $persona->getPersonas();
});

$router->get('/equipos/tipos', function () {
    $equipos = new Equipo();
    $equipos->getTipos();
});

$router->get('/personas/areas', function () {
    $persona = new Persona();
    $persona->getAreas();
});

$router->get('/personas/ubicaciones', function () {
    $persona = new Persona();
    $persona->getUbicaciones();
});

$router->get('/personas/ubicaciones', function () {
    $persona = new Persona();
    $persona->getUbicaciones();
});

$router->post('/personas/create', function () {
    $persona = new Persona();
    $persona->create();
});

$router->post('/equipo/create', function () {
    $equipo = new Equipo();
    $equipo->create();
});

$router->get('/equipo/detalles', function () {
    $equipo = new Equipo();
    $equipo->getDetalles();
});

$router->put('/equipo/edit', function () {
    $equipo = new Equipo();
    $equipo->edit();
});

$router->get('/', function () {
    new Inicio();
});

$router->set404(function () {
    echo json_encode(["message" => "404 not found"]);
});

$router->run();
