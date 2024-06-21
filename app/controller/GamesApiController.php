<?php
require_once 'app/model/GamesModel.php';
require_once 'app/model/CategoriesModel.php';
require_once 'app/controller/APIcontroller.php';


class GamesApiController extends APIcontroller
{

    private $model;
    private $categories;

    public function __construct()
    {   //una vez construimos a gamesapicontroller hacemos que se construya apicontroller
        parent::__construct();
        $this->model = new GamesModel();
        $this->categories = new CategoriesModel();
    }

    function get($params = [])
    {
        if (empty($params)) { //si params esta vacio obtenemos todas las tareas
            $Games = $this->model->getAll();
            $this->view->response($Games, 200);
        } else { //sino obtenemos un solo juego
            $Game = $this->model->get($params[':ID']);
            if (!empty($Game)) {
                $this->view->response($Game, 200);
            } else {
                $this->view->response(['msg:' => 'El juego con el id= '
                    . $params[':ID'] . ' no existe'], 404);
            }
        }
    }
    function delete($params = [])
    {
        $id = $params[':ID'];
        $Game = $this->model->get($id);
        if ($Game) {
            $this->model->delete($id);
            $this->view->response('El juego con id: ' . $id . ' ha sido borrado con exito', 200);
        } else {
            $this->view->response('El juego con id: ' . $id . ' NO existe', 404);
        }
    }
    function add()
    {

        $body = $this->getdata(); //json que introdujo el cliente

        $nombre = $body->nombre;
        $categoria = $body->categoria;
        $precio = $body->precio;
        $fecha = $body->fecha;

        $id = $this->model->add($nombre, $categoria, $precio, $fecha);

        $this->view->response('El juego fue creado con el id: ' . $id, 201);
    }
    function update($params = []){
        $id = $params[':ID'];
        $Game = $this->model->get($id);

        if ($Game) {
            $body=$this->getdata();

            $nombre = $body->nombre;
            $categoria = $body->categoria;
            $precio = $body->precio;
            $fecha = $body->fecha;

            $this->model->update($nombre, $categoria, $precio, $fecha, $id);
            $this->view->response('El juego con el id: ' . $id . ' fue modificado', 200);
        }
        else{
            $this->view->response(['msg:' => 'El juego con el id: ' . $id . ' no existe'], 404);
        }
    }
}
