<?php

date_default_timezone_set('America/Mexico_City');
error_reporting(E_ALL);
ini_set('ignore_repeat_errors', TRUE);
ini_set('display_errors', FALSE);
ini_set('log_errors', TRUE);
ini_set('error_log', 'debug.log');

header("Access-Control-Allow-Origin: * ");
header("Access-Control-Allow-Headers: Origin, Content_Type, X-Requested-With, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");


require_once 'vendor/autoload.php';
require_once 'src/config/config.php';
require_once 'src/Libs/Router.php';
