<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/Configuration/Connection.php';
require '../src/Rest/main.php';


$app->run();
