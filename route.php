<?php
require_once 'libs/Router.php';
require_once 'app/controller/GamesApiController.php';
require_once 'app/controller/CategoriesApiController.php';

// crea el router
$router = new Router();

$router->addRoute('juegos',         'GET',    'GamesApiController',      'get'   );//obtener todos los juegos
$router->addRoute('juegos/:ID',     'GET',    'GamesApiController',      'get'   );//obtengo solo uno
$router->addRoute('juegos',         'POST',   'GamesApiController',      'add'   );//agregar un juego
$router->addRoute('juegos/:ID',     'PUT',    'GamesApiController',      'update');//modificar un juego
$router->addRoute('juegos/:ID',     'DELETE', 'GamesApiController',      'delete');//borrar un juego
$router->addRoute('categorias',     'GET',    'CategoriesApiController', 'get'   );//obtener las categorias
$router->addRoute('categorias/:ID', 'GET',    'CategoriesApiController', 'get'   );//obtener una categoria
$router->addRoute('categorias',     'POST',   'CategoriesApiController', 'add'   );//agregar una categoria


$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);