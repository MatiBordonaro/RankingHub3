<?php
require_once('config.php');

class Model
{
    protected $conexion;

    public function __construct()
    {
        $this->conexion = $this->createConexion();
        $this->deploy();
    }

    function createConexion()
    {
        try {
            $db = new PDO('mysql:host=' . MYSQL_HOST . ';charset=utf8', MYSQL_USER, MYSQL_PASS);
            $this->createOrUseDatabase($db);
        } catch (Exception $e) {
            die("Error al conectar a la base de datos: " . $e->getMessage());
        }
        return $db;
    }

    private function createOrUseDatabase($db)
    {
        $query = $db->query("SHOW DATABASES LIKE '" . MYSQL_DB . "'");
        $databaseExists = $query->rowCount() > 0;

        if (!$databaseExists) {
            $db->query("CREATE DATABASE " . MYSQL_DB . "");
        }

        $db->query("USE " . MYSQL_DB . "");
    }

    private function deploy()
    {
        $this->createTables();
    }

    private function createTables()
    {
        $sqlCategorias =
            "CREATE TABLE IF NOT EXISTS categorias (
                id_categorias int(11) NOT NULL AUTO_INCREMENT,
                nombre varchar(45) NOT NULL,
                descripcion varchar(100) NOT NULL,
                PRIMARY KEY (id_categorias)
            );";

        $sqlInsertCategorias =  "INSERT INTO categorias (nombre, descripcion) VALUES
            ('Acción', 'Juegos que involucran acción y combate.'),
            ('Aventura', 'Juegos enfocados en la narrativa y la exploración del mundo.'),
            ('Battle Royale', 'Juegos de supervivencia donde varios jugadores compiten para ser el último en pie.'),
            ('Carreras', 'Juegos de competición de velocidad.'),
            ('Deportes', 'Juegos basados en deportes reales o ficticios.'),
            ('Estrategia', 'Juegos que requieren planificación y toma de decisiones.'),
            ('Puzzle', 'Juegos que desafían la lógica y resolución de problemas.'),
            ('Rol', 'Juegos donde los jugadores asumen roles de personajes.'),
            ('Sandbox', 'Juegos donde los jugadores tienen libertad para crear y explorar.'),
            ('Shooter', 'Juegos donde el principal enfoque es el disparo de armas.');";




        $sqlJuegos =
            "CREATE TABLE IF NOT EXISTS juegos (
                id int(11) NOT NULL AUTO_INCREMENT,
                nombre varchar(45) NOT NULL,
                id_categoria int(11) NOT NULL,
                categoria varchar(45) NOT NULL,
                precio float NOT NULL,
                fecha date NOT NULL,
                PRIMARY KEY (id),
                KEY id_categorias (id_categoria),
                CONSTRAINT juegos_ibfk_1 FOREIGN KEY (id_categoria) REFERENCES categorias (id_categorias)
                ON UPDATE CASCADE 
                ON DELETE CASCADE
            );";

        $sqlInsertJuegos = "INSERT IGNORE INTO `juegos` (`id`, `nombre`, `id_categoria`,`categoria`, `precio`, `fecha`) VALUES
            (1, 'Minecraft',                 9, 'Sandbox',      29.99, '2011-11-18'),
            (2, 'Grand Theft Auto V',        2, 'Aventura',     29.99, '2013-09-17'),
            (3, 'Tetris',                    7, 'Puzzle',       4.99, '1984-06-06'),
            (4, 'Wii Sports',                5, 'Deportes',     19.99, '2006-11-19'),
            (5, 'PUBG',                      3, 'Battle royale',29.99, '2017-12-20'),
            (6, 'Super Mario Bros',          2, 'Aventura',     9.99, '1985-09-13'),
            (7, 'Mario Kart 8 Deluxe',       4, 'Carreras',     59.99, '2017-04-28'),
            (8, 'Red Dead Redemption',       1, 'Acción',       59.99, '2018-10-26'),
            (9, 'Pokemon Primera Generacion',8, 'Rol',          29.99, '1996-02-27'),
            (10, 'Terraria',                 2, 'Aventura',     14.99, '2011-05-16');";




        $sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
            id int(11) NOT NULL AUTO_INCREMENT,
            username varchar(50) NOT NULL,
            password varchar(50) NOT NULL,
            rol varchar(20) NOT NULL,
            PRIMARY KEY (id)
        );";


        $userInsert = "INSERT IGNORE INTO usuarios (id, username, password, rol) VALUES
            (1, 'matias', '090c36e3bb39377468363197afb3e91b', 'usuario'), 
            (2, 'facundo', '242ac92bff7cc0bf1f52de2f254b27a8', 'usuario'), 
            (3, 'messi', '1463ccd2104eeb36769180b8a0c86bb6', 'usuario'), 
            (4, 'kratos', '9df59d4c785363a0f8148f5d5e428354', 'usuario'),
            (5, 'juan', 'a94652aa97c7211ba8954dd15a3cf838', 'usuario'),
            (6, 'webadmin', '21232f297a57a5a743894a0e4a801fc3', 'admin');";



        $this->conexion->query($sqlCategorias);
        $countCategorias = $this->conexion->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
        if ($countCategorias == 0) {
            $this->conexion->query($sqlInsertCategorias);
        }

        $this->conexion->query($sqlJuegos);
        $this->conexion->query($sqlInsertJuegos);

        $this->conexion->query($sqlUsuarios);
        $this->conexion->query($userInsert);
        
        
    }
}
