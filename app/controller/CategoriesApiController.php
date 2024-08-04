<?php
    require_once 'app/view/ApiView.php';
    require_once 'app/model/CategoriesModel.php';
    require_once 'config.php';

    class CategoriesApiController extends APIcontroller{
        
        private $model;

        public function __construct(){
            parent::__construct();
            $this->model = new CategoriesModel;
        }

        function get($params=[]){
            
            $sort = isset($_GET['sort']); //clasificar
            $order = isset($_GET['order']); //ordenar

            $page = isset($_GET['page']); //página
            $limit = isset($_GET['limit']); //límite
            
            $key = isset($_GET['key']); //clave
            $value = isset($_GET['value']); //valor
            
            if(empty($params)){// si no esta definido params nos da todas las categoria
                $this->sortandorder($sort, $order, $page, $limit, $key, $value);
                $categories=$this->model->getAll();
                $this->view->response($categories, 200);
                die();
            }
            else{//si esta definido params nos trae una sola categoria
            $category=$this->model->get($params[':ID']);
            if(!empty($category)){
                $this->view->response($category, 200);
            }
            else
                $this->view->response("No se ha encontrado una categoria con el id ".$params[":ID"], 404);}
        }
        
        function sortandorder($sort, $order, $page, $limit, $key, $value){
            
            if($sort || $order){
                if($page||$limit||$key||$value){
                    $this->view->response("'msg:' => 'No puede realizar otras acciones mientras clasifica u ordena']", 404);
                    die();
                }else{
                    $this->getAllSorted();
                    die();
                }
            }
        }
        function getAllSorted(){
            $sort=isset($_GET['sort']) ? $_GET['sort'] : 'id_categorias';
            $order= isset($_GET['order'])? $_GET['order']:'ASC';
            $sortsValidos=['id_categorias', 'nombre', 'descripcion'];
            $orderValidos = ['asc', 'desc', 'ASC', 'DESC'];

            if(!in_array($sort, $sortsValidos)){
                $this->view->response("['msg:' => 'No se puede clasificar por este campo, seleccione uno de estos: id, nombre, categoria, precio, fecha']", 400);
                die();
            }else if(!in_array($order, $orderValidos)){
                $this->view->response(['msg:' => 'El orden debe ser `asc` (ascendente) o `desc` (descendente)'], 400);
                die();
            }else {
                $Categories=$this->model->getAllSorted($sort, $order);
                $this->view->response($Categories, 200);
            }
        }     
        function add(){
            if(!$this->VerificarAuterizacion()){
                $this->view->response("No Autorizado", 401);
                die;
            }

            $body= $this->getdata();

            if(isset($body->nombre)&& isset($body->descripcion)){
                $nombre=$body->nombre;
                $descripcion=$body->descripcion;
                $id = $this->model->add($nombre, $descripcion);

                $this->view->response(['msg' => 'El juego fue creado con el id: ' . $id], 201);
            }else
                $this->view->response(['msg'=>'Los datos igresados no coinciden con los datos solicitados para agregar el juego'], 400);
        }
        function VerificarAuterizacion(){
            global $config;

            $encabezados=apache_request_headers();
            $autorizacion=$encabezados['Authorization'];
            $params=explode(' ', $autorizacion);

            $token=$params[1];
            $ItemJWT=explode('.', $token);

            $header= base64_decode($ItemJWT[0]);
            $payload= json_decode(base64_decode($ItemJWT[1]));

            $usuario=$payload->usuario;
            $clave=$payload->contraseña;

            if($usuario==$config['userpass']['user'] && $clave===$config['userpass']['pass']){
                return true;
            }else 
                return false;
        }
    }