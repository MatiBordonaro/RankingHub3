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

    function add($nombre, $id_categoria, $categoria, $precio, $fecha){
        $db = $this->createConexion();
        $query = $db->prepare('INSERT INTO juegos (nombre, id_categoria, categoria, precio, fecha) VALUES (?,?,?,?,?)');
        $query->execute([$nombre, $id_categoria, $categoria, $precio, $fecha]);
        return $db->lastInsertId();
    }

    function delete($id){
        $db = $this->createConexion();
        $query = $db->prepare('DELETE FROM juegos WHERE id=?');
        $query->execute([$id]);
    }

    function update($nombre, $id_categoria, $categoria, $precio, $fecha, $id){
        $db = $this->createConexion();
        $query = $db->prepare('UPDATE juegos SET nombre=?, id_categoria=?, categoria=?, precio=?, fecha=? WHERE id=?');
        $query->execute([$nombre, $id_categoria, $categoria, $precio, $fecha, $id]);
    }

    function get($id){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT * FROM juegos WHERE id=?');
        $query->execute([$id]);
        $juego = $query->fetch(PDO::FETCH_OBJ);
        return $juego;
    }

    function getAllSorted($sort = null, $order= null){
        $db =  $this->createConexion();
        $sql = 'SELECT * FROM juegos';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $sql .= ' ORDER BY ' . $sort . ' ' . $order;
        $query = $db->prepare($sql);
        $query->execute();
        $juegos = $query->fetchAll(PDO::FETCH_OBJ);
        return $juegos;
    }

    function getPaginated($page = null, $limit = null){
        $db =  $this->createConexion();
        $offset = ($page - 1) * $limit; //el -1 sirve para que me cuente desde el primer valor de la página y no desde el último
        //por ejemplo, página(3) - 1 * limite(5) = 10
        //entonces, la db me devuelve a partir de la fila 10
        //Pagina 1 = desde 1 a 5, Página 2 = desde 5 a 10, Página 3 = desde 10 a 15...
        $sql = 'SELECT * FROM juegos LIMIT ' . $limit . ' OFFSET ' . $offset;
        $query = $db->prepare($sql);
        $query->execute();
        $juegos = $query->fetchAll(PDO::FETCH_OBJ);
        return $juegos;
    }

    function getFiltered($key = null, $value = null){
        $db = $this->createConexion();
        $sql = 'SELECT * FROM juegos WHERE ' . $key . ' = ?';
        $query = $db->prepare($sql);
        $query->execute([$value]);
        $juegos = $query->fetchAll(PDO::FETCH_OBJ);
        return $juegos;
    }
}