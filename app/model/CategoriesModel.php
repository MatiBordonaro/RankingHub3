<?php
require_once('Model.php');

class CategoriesModel extends Model {

    function getAll(){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT * FROM categorias');
        $query->execute();
        $categories = $query->fetchAll(PDO::FETCH_OBJ);
        return $categories;
    }

    function add($nombre, $descripcion){
        $db = $this->createConexion();
        $query = $db->prepare('INSERT INTO categorias (nombre, descripcion) VALUES (?,?)');
        $query->execute([$nombre, $descripcion]);
    }

    function delete($nombre){
        $db = $this->createConexion();
        $query = $db->prepare('DELETE FROM categorias WHERE nombre=?');
        $query->execute([$nombre]);
    }

    function modify($nombreNuevo, $descripcion, $nombreViejo){
        $db = $this->createConexion();
        $query = $db->prepare('UPDATE categorias SET nombre=?, descripcion=? WHERE nombre=?');
        $query->execute([$nombreNuevo, $descripcion, $nombreViejo]);
    }

    function get($nombre){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT * FROM categorias WHERE nombre=?');
        $query->execute([$nombre]);
        $category = $query->fetch(PDO::FETCH_OBJ);
        return $category;
    }
}