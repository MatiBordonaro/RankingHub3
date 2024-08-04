<?php
require_once 'app/model/GamesModel.php';
require_once 'app/controller/APIcontroller.php';
require_once 'config.php';
ini_set('display_errors', 0);

class GamesApiController extends APIcontroller {

    private $model;

    public function __construct() {   //una vez construimos a gamesapicontroller hacemos que se construya apicontroller
        parent::__construct();
        $this->model = new GamesModel();
    }
    
    function get($params = []) {

        if (empty($params)) { //si no hay parámetros, obtenemos todos los juegos
            //creo variables booleanas para no repetir tanto código. 
            $sort = isset($_GET['sort']); //clasificar
            $order = isset($_GET['order']); //ordenar
            
            $page = isset($_GET['page']); //página
            $limit = isset($_GET['limit']); //límite
            
            $key = isset($_GET['key']); //clave
            $value = isset($_GET['value']); //valor
            //CLASIFICAR Y/O ORDENAR
            if($sort || $order){//si el cliente quiere clasificar u ordenar
                if($page || $limit || $key || $value){ //condición por si el cliente quiere realizar otras acciones mientras clasifica
                    $this->view->response(['msg:' => 'No puede realizar otras acciones mientras clasifica u ordena'], 404);
                    die();
                }
                $this->getAllSorted(); //obtiene los juegos clasificados o muestra por defecto(id)
                die();
            }
            //PAGINAR
            if($page || $limit){ //si el cliente quiere paginar o poner un limite a mostrar
                if($sort || $order || $key || $value){ //condición por si el cliente quiere realizar otras acciones mientras realiza paginación
                    $this->view->response(['msg:' => 'No puede realizar otras acciones mientras realiza una paginación o limita objetos'], 404);
                    die();
                }
                $this->getPaginated();
                die();
            }
            //FILTRAR
            if($key || $value){ //acá hago un paso extra para que toda la lógica de filtrar quede adentro de esta porcion de codigo
                if($key && $value){
                    if($sort || $order || $page || $limit){ //prohibir otras acciones mientras filtre
                        $this->view->response(['msg:' => 'No puede realizar otras acciones mientras se filtran juegos'], 404);
                        die();
                    } else {
                        $this->getFiltered();
                        die();
                    }
                } else { //si llega acá quiere decir que indico una clave o un valor, pero no ambos
                    $this->view->response(['msg:' => 'Debe indicar si o si una clave y luego un valor, intente de nuevo'], 404);
                }
            }
            //OBTENER TODOS 
            else {
                $games = $this->model->getAll(); //si el cliente no realiza ninguna petición extra, obtiene todos los juegos
                $this->view->response($games, 200);
                die();
            }
        } else { // Sí params no está vacio, obtenemos un solo juego
            $game = $this->model->get($params[':ID']);
            if (!empty($game)) {
                $this->view->response($game, 200);
            } else {
                $this->view->response(['msg:' => 'El juego con el id = ' . $params[':ID'] . ' no existe'], 404);
            }
        }
    }

    function getAllSorted(){
        //CLASIFICAR Y ORDENAR
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id'; //por defecto es id
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; //por defecto es ascendente
        $sortValidos = ['id', 'nombre', 'categoria', 'precio', 'fecha'];
        $orderValidos = ['asc', 'desc', 'ASC', 'DESC'];
        //acá abajo verifico que cuando el cliente quiera clasificar, la columna exista, sino se muestra por defecto(id)
        if(!(in_array($sort, $sortValidos))){
            $this->view->response(['msg:' => 'No se puede clasificar por este campo, seleccione uno de estos: id, nombre, categoria, precio, fecha'], 404);
            die();
        }
        if(!(in_array($order, $orderValidos))){
            $this->view->response(['msg:' => 'El orden debe ser `asc` (ascendente) o `desc` (descendente)'], 404);
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
            $this->view->response(['msg:' => 'La página o el límite no es válido, por favor indique una página válida y un límite mayor a 2'], 400);
            die();
        } else {
            $Games = $this->model->getPaginated($page, $limit);
            $this->view->response($Games, 200);
        }
    }

    function getFiltered(){
        $key = isset($_GET['key']) ? $_GET['key'] : null;
        $value = isset($_GET['value']) ? $_GET['value'] : null;
        $keyValidos = ['id', 'nombre', 'categoria', 'precio', 'fecha'];
        if(!(in_array($key, $keyValidos))){
            $this->view->response(['msg:' => 'No puede filtrar por este campo, por favor seleccione uno de estos: id, nombre, categoria, precio, fecha'], 404);
            die();
        } else {
            $games = $this->model->getFiltered($key, $value);
            if(empty($games)){
                $this->view->response(['msg:' => 'No hay resultados para esta búsqueda.'], 404);
                die();
            }
            $this->view->response($games, 200);
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
        if(!$this->VerificarToken()){//verificar la autorizacion
            $this->view->response("No autorizado", 401);
            die;
        }
        $body = $this->getdata(); // json que introdujo el cliente
        // verificar que todos los campos existan (menos el de id que el usuario no debe especificarlo)
        if(isset($body->nombre) && isset($body->id_categoria)&& isset($body->categoria) && isset($body->precio) && isset($body->fecha)) {
            $nombre = $body->nombre;
            $id_categoria=$body->id_categoria;
            $categoria = $body->categoria;
            $precio = $body->precio;
            $fecha = $body->fecha;
            $id = $this->model->add($nombre, $id_categoria, $categoria, $precio, $fecha);
            $this->view->response(['msg' => 'El juego fue creado con el id: ' . $id], 201);
        } else {
            $this->view->response(['msg' => 'Los datos igresados no coinciden con los datos solicitados para agregar el juego'], 400);
        }
    }
    
    
    function update($params = []){
        if(!$this->VerificarToken()){
            $this->view->response("No autorizado", 401);
            die;
        }
        $id = $params[':ID'];
        $Game = $this->model->get($id);
        if($Game){
            $body = $this->getdata();
            if(isset($body->nombre) && isset($body->categoria) && isset($body->precio) && isset($body->fecha)) {
                $nombre = $body->nombre;
                $id_categoria=$body->id_categoria;
                $categoria = $body->categoria;
                $precio = $body->precio;
                $fecha = $body->fecha;

                $this->model->update($nombre,  $id_categoria, $categoria, $precio, $fecha, $id);
                $this->view->response(['msg:' => 'El juego con el id: ' . $id . ' fue modificado'], 200);
            } else {
                $this->view->response(['msg:' => 'Faltan datos obligatorios para modificar o los datos ingresados no coinciden con los datos de la tabla'], 400);   
            }
        }
        else{
            $this->view->response(['msg:' => 'El juego con el id: ' . $id . ' no existe'], 404);
        }
    }

    function VerificarToken(){
        global $config;

        $encabezados= apache_request_headers();
        $authorization= $encabezados['Authorization'];
        $params= explode(' ', $authorization);

        $token=$params[1];
        $itemsJWT=explode('.', $token);

        $header= base64_decode($itemsJWT[0]);
        $payload= json_decode(base64_decode($itemsJWT[1])); 

        $usuario=$payload->usuario;
        $clave=$payload->contraseña;
        
        if($usuario===$config['userpass']['user']&&$clave===$config['userpass']['pass']){
            return true;
        }else{
            return false;
        }; 
    }
    
}
