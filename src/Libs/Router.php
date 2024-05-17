<?php

use ApiSistemas\Controllers\Equipo;
use ApiSistemas\Controllers\Inicio;
use ApiSistemas\Controllers\Persona;
use ApiSistemas\Controllers\Proveedor;
use ApiSistemas\Controllers\Traspaso;
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

$router->post('/equipo/edit', function () {
    $equipo = new Equipo();
    $equipo->edit();
});

$router->get('/equipo/qr', function () {
    $equipo = new Equipo();
    $equipo->getQr();
});

$router->get('/', function () {
    new Inicio();
});

$router->post('/traspaso/create', function () {
    $traspaso = new Traspaso();
    $traspaso->create();
});

$router->get('/traspaso', function () {
    $traspaso = new Traspaso();
    $traspaso->get();
});

$router->post('/proveedor/create', function () {
    $proveedor = new Proveedor();
    $proveedor->create();
});

$router->get('/proveedor', function () {
    $proveedor = new Proveedor();
    $proveedor->get();
});

$router->put('/proveedor/edit', function () {
    $proveedor = new Proveedor();
    $proveedor->edit();
});

$router->get('/equipo/excel', function () {
    $equipo = new Equipo();
    $equipo->getExcel();
});

$router->set404(function () {
    echo json_encode(["message" => "404 not found"]);
});

$router->run();
