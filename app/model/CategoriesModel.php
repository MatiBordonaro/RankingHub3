<?php
require_once 'app/model/model.php';

class CategoriesModel extends Model{

    function getAll(){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT * FROM categorias');
        $query->execute();
        $categories = $query->fetchAll(PDO::FETCH_OBJ);
        return $categories;
    }
    function get($id){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT*FROM categorias WHERE id_categorias=?');
        $query->execute([$id]);
        $category=$query->fetch(PDO::FETCH_OBJ);
        return $category;
    }
    function getAllSorted($sort, $order){
        $db=$this->createConexion();
        $sql='SELECT*FROM categorias';
        $order=strtoupper($order)=== 'DESC' ? 'DESC':'ASC';
        $sql .= ' ORDER BY ' . $sort . ' ' . $order;
        $query = $db->prepare($sql);
        $query->execute();
        $Category=$query->fetchAll(PDO::FETCH_OBJ);
        return $Category;
    }
    function add($nombre, $descripcion){
        $db=$this->createConexion();
        $query=$db->prepare('INSERT INTO categorias (nombre, descripcion) VALUES (?,?)');
        $query->execute([$nombre, $descripcion]);
        return $db->lastInsertId();
    }
    
}
