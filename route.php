<?php
require_once 'libs/Router.php';
require_once 'app/controller/GamesApiController.php';

// crea el router
$router = new Router();

$router->addRoute('juegos', 'GET', 'GamesApiController', 'getAll');
$router->addRoute('juegos/:ID', 'GET', 'GamesApiController', 'get');
$router->addRoute('juegos', 'POST', 'GamesApiController', 'add');

$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);