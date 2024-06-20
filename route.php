<?php
require_once 'libs/Router.php';

// crea el router
$router = new Router();

$router->addRoute('juegos', 'GET', 'GamesApiController', 'getAll');
$router->addRoute('juegos', 'GET', 'GamesApiController', 'get');
$router->addRoute('juegos', 'POST', 'GamesApiController', 'add');