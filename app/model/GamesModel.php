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

    function getByCategory($category){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT * FROM juegos WHERE categoria=?');
        $query->execute([$category]);
        $juegos = $query->fetchAll(PDO::FETCH_OBJ);
        return $juegos;
    }
}