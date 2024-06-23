<?php
require_once('Model.php');

class GamesModel extends Model {
    
    function getAll(){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT * FROM juegos');
        $query->execute();
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        return $games;
    }

    function add($nombre, $categoria, $precio, $fecha){
        $db = $this->createConexion();
        $query = $db->prepare('INSERT INTO juegos (nombre, categoria, precio, fecha) VALUES (?,?,?,?)');
        $query->execute([$nombre, $categoria, $precio, $fecha]);
        return $db->lastInsertId();
    }

    function delete($id){
        $db = $this->createConexion();
        $query = $db->prepare('DELETE FROM juegos WHERE id=?');
        $query->execute([$id]);
    }

    function update($nombre, $categoria, $precio, $fecha, $id){
        $db = $this->createConexion();
        $query = $db->prepare('UPDATE juegos SET nombre=?, categoria=?, precio=?, fecha=? WHERE id=?');
        $query->execute([$nombre, $categoria, $precio, $fecha, $id]);
    }

    function get($id){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT * FROM juegos WHERE id=?');
        $query->execute([$id]);
        $juego = $query->fetch(PDO::FETCH_OBJ);
        return $juego;
    }

    function getAllSorted($sort = null, $order='ASC'){
        $db =  $this->createConexion();
        $sql = 'SELECT * FROM juegos';
        if($sort){ //acá se verifica si existe un campo para clasificar los juegos, y si no lo hay realiza un simple getAll
            $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= ' ORDER BY ' . $sort . ' ' . $order;
        }
        $query = $db->prepare($sql);
        $query->execute();
        $juegos = $query->fetchAll(PDO::FETCH_OBJ);
        return $juegos;
    }
}