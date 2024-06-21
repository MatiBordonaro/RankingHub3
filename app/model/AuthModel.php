<?php
require_once('Model.php');

class AuthModel extends Model{
    
    function getUser($username){
        $db = $this->createConexion();
        $query = $db->prepare('SELECT * FROM usuarios WHERE username=?');
        $query->execute([$username]);
        $user = $query->fetch(PDO::FETCH_OBJ);
        return $user;
    }
}