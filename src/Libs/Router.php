<?php

use Bramus\Router\Router;

$router = new Router();

$router->set404(function () {
    echo json_encode(["message" => "404 not found"]);
});

$router->get('/', function () {
    echo json_encode(["message" => "Hola mundo"]);
});




$router->run();
