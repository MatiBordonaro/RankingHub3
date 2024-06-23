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
        if (empty($params)) { // Si params está vacío, obtenemos todos los juegos
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id'; //por defecto es id
            $order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; //por defecto es ascendente
            //acá abajo verifico que cuando el cliente quiera clasificar, la columna exista, sino se muestra por defecto(id)
            if(!($sort === 'id' || $sort === 'nombre' || $sort === 'categoria' || $sort === 'precio' || $sort === 'fecha')){
                $this->view->response(['msg:' => 'No se puede clasificar por este campo, seleccione uno de estos: id, nombre, categoria, precio, fecha'], 404);
                return;
            }
            $Games = $this->model->getAllSorted($sort, $order);
            //Los datos se mostrarán clasificados o por defecto gracias a la función GetAllSorted, en el model se encuentra la explicación
            $this->view->response($Games, 200);
        } else { // Sí params no está vacio, obtenemos un solo juego
            $Game = $this->model->get($params[':ID']);
            if (!empty($Game)) {
                $this->view->response($Game, 200);
            } else {
                $this->view->response(['msg:' => 'El juego con el id= ' . $params[':ID'] . ' no existe'], 404);
            }
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
        // Verificar que todos los campos necesarios estén presentes
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
