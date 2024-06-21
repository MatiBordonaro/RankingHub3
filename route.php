<?php
require_once 'libs/Router.php';
require_once 'app/controller/GamesApiController.php';

// crea el router
$router = new Router();

$router->addRoute('juegos',     'GET',    'GamesApiController', 'get'   );//obtener todos los juegos
$router->addRoute('juegos/:ID', 'GET',    'GamesApiController', 'get'   );//obtengo solo uno
$router->addRoute('juegos',     'POST',   'GamesApiController', 'add'   );//agregar un juego
$router->addRoute('juegos/:ID', 'DELETE', 'GamesApiController', 'delete');//borrar un juego

$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);