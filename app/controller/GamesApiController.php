<?php
    require_once 'app/model/GamesModel.php';
    require_once 'app/model/CategoriesModel.php';
    require_once 'app/view/GamesApiView.php';

    class GamesApiController{
        
        private $model;
        private $view;
        private $categories;

        public function __construct(){
            $this->model = new GamesModel();
            $this->categories = new CategoriesModel();
            $this->view = new GamesApiView();
        } 

        function getAll($params = []){
            $Games=$this->model->getAll();
            $this->view->response($Games, 200);
        } 
    }
