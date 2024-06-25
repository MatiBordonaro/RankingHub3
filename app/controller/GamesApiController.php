<?php
require_once 'app/model/GamesModel.php';
require_once 'app/controller/APIcontroller.php';


class GamesApiController extends APIcontroller {

    private $model;

    public function __construct() {   //una vez construimos a gamesapicontroller hacemos que se construya apicontroller
        parent::__construct();
        $this->model = new GamesModel();
    }

    function get($params = []) {
        if (empty($params)) { //si no hay parámetros, obtenemos todos los juegos
            //CLASIFICAR
            if(isset($_GET['sort']) || isset($_GET['order'])){//si el cliente quiere clasificar u ordenar
                if(isset($_GET['page']) || isset($_GET['limit']) || isset($_GET['key']) || isset($_GET['value'])){ //condición por si el cliente quiere realizar otras acciones mientras clasifica
                    $this->view->response(['msg:' => 'No puede realizar otras acciones mientras clasifica'], 404);
                    die();
                }
                $this->getAllSorted(); //obtiene los juegos clasificados o muestra por defecto(id)
            }
            //PAGINAR
            if(isset($_GET['page']) || isset($_GET['limit'])){ //si el cliente quiere paginar o poner un limite a mostrar
                if(isset($_GET['sort']) || isset($_GET['order']) || isset($_GET['key']) || isset($_GET['value'])){ //condición por si el cliente quiere realizar otras acciones mientras realiza paginación
                    $this->view->response(['msg:' => 'No puede realizar otras acciones mientras realiza una paginación'], 404);
                    die();
                }
                $this->getPaginated();
            }
            //FILTRAR
            if(isset($_GET['key']) && isset($_GET['value'])){
                if(isset($_GET['sort']) || isset($_GET['order']) || isset($_GET['page']) || isset($_GET['limit'])){
                    $this->view->response(['msg:' => 'No puede realizar otras acciones mientras quiere filtrar juegos'], 404);
                    die();
                }
                $this->getFiltered();
            }
            else {
                $Games = $this->model->getAll(); //si el cliente no realiza ninguna petición extra, obtiene todos los juegos
                $this->view->response($Games, 200);
            }
        } else { // Sí params no está vacio, obtenemos un solo juego
            $Game = $this->model->get($params[':ID']);
            if (!empty($Game)) {
                $this->view->response($Game, 200);
            } else {
                $this->view->response(['msg:' => 'El juego con el id = ' . $params[':ID'] . ' no existe'], 404);
            }
        }
    }

    function getAllSorted(){
        //CLASIFICAR Y ORDENAR
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id'; //por defecto es id
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; //por defecto es ascendente
        //acá abajo verifico que cuando el cliente quiera clasificar, la columna exista, sino se muestra por defecto(id)
        if(!($sort === 'id' || $sort === 'nombre' || $sort === 'categoria' || $sort === 'precio' || $sort === 'fecha')){
            $this->view->response(['msg:' => 'No se puede clasificar por este campo, seleccione uno de estos: id, nombre, categoria, precio, fecha'], 404);
            die();
        }
        if(!($order === 'asc' || $order === 'desc' || $order === 'ASC' || $order === 'DESC')){
            $this->view->response(['msg:' => 'Advertencia, el orden debe ser `asc` (ascendente) o `desc` (descendente)'], 404);
            die();
        }
        //Los datos se mostrarán clasificados o por defecto(id) gracias a la función GetAllSorted, en el model se encuentra la explicación
        $Games = $this->model->getAllSorted($sort, $order);
        $this->view->response($Games, 200);
    }

    function getPaginated(){
        //PAGINACIÓN
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
        if(!(is_numeric($page) && is_numeric($limit) && $page >= 1 && $limit > 2 )){
            $this->view->response(['msg:' => 'La página o el límite no es válido, por favor indique una página válida y un límite mayor a 2'], 404);
            die();
        } else {
            $Games = $this->model->getPaginated($page, $limit);
            $this->view->response($Games, 200);
            die();
        }
    }

    function getFiltered(){
        $key = isset($_GET['key']) ? $_GET['key'] : null;
        $value = isset($_GET['value']) ? $_GET['value'] : null;
        if(!($key && $value)){
            $this->view->response(['msg:' => 'No puede filtrar por este campo, por favor seleccione uno de estos: id, nombre, categoria, precio, fecha'], 404);
            die();
        } else {
            $games = $this->model->getFiltered($key, $value);
            $this->view->response($games, 200);
            die();
        }
    }

    function delete($params = []) {
        $id = $params[':ID'];
        $Game = $this->model->get($id);
        if ($Game) {
            $this->model->delete($id);
            $this->view->response(['msg:' => 'El juego con id: ' . $id . ' ha sido borrado con exito'], 200);
        } else {
            $this->view->response(['msg:' => 'El juego con id: ' . $id . ' NO existe'], 404);
        }
    }

    function add() {
        $body = $this->getdata(); // json que introdujo el cliente
        // verificar que todos los campos existan (menos el de id que el usuario no debe especificarlo)
        if(isset($body->nombre) && isset($body->categoria) && isset($body->precio) && isset($body->fecha)) {
            $nombre = $body->nombre;
            $categoria = $body->categoria;
            $precio = $body->precio;
            $fecha = $body->fecha;
            $id = $this->model->add($nombre, $categoria, $precio, $fecha);
            $this->view->response(['msg' => 'El juego fue creado con el id: ' . $id], 201);
        } else {
            $this->view->response(['msg' => 'Ingrese los datos correctos para agregar el juego'], 400);
        }
    }
    
    
    function update($params = []){
        $id = $params[':ID'];
        $Game = $this->model->get($id);
        if($Game){
            $body = $this->getdata();
            if(isset($body->nombre) && isset($body->categoria) && isset($body->precio) && isset($body->fecha)) {
                $nombre = $body->nombre;
                $categoria = $body->categoria;
                $precio = $body->precio;
                $fecha = $body->fecha;

                $this->model->update($nombre, $categoria, $precio, $fecha, $id);
                $this->view->response(['msg:' => 'El juego con el id: ' . $id . ' fue modificado'], 200);
            } else {
                $this->view->response(['msg:' => 'Faltan datos obligatorios para modificar'], 400);   
            }
        }
        else{
            $this->view->response(['msg:' => 'El juego con el id: ' . $id . ' no existe'], 404);
        }
    }

    
}
