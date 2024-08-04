<?php
require_once 'app/view/ApiView.php';

//creamos una api controller donde manejamos construimos la vista y la data
class APIcontroller
{
    protected $view;
    private $data;

    public function __construct()
    {
        $this->view = new ApiView();
        $this->data = file_get_contents('php://input'); //esta funcionn lee el texto que pasa el cliente
    }

    function getData(){
        return json_decode($this->data); //y luego en esta funcion volvemos json a ese texto
    }
}
