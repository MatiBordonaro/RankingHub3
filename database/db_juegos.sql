-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2024 a las 23:56:42
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_juegos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`nombre`, `descripcion`) VALUES
('Acción', 'Juegos que involucran acción y combate.'),
('Aventura', 'Juegos enfocados en la narrativa y la exploración del mundo.'),
('Battle Royale', 'Juegos de supervivencia donde varios jugadores compiten para ser el último en pie.'),
('Carreras', 'Juegos de competición de velocidad.'),
('Deportes', 'Juegos basados en deportes reales o ficticios.'),
('Estrategia', 'Juegos que requieren planificación y toma de decisiones.'),
('Puzzle', 'Juegos que desafían la lógica y resolución de problemas.'),
('Rol', 'Juegos donde los jugadores asumen roles de personajes.'),
('Sandbox', 'Juegos donde los jugadores tienen libertad para crear y explorar.'),
('Shooter', 'Juegos donde el principal enfoque es el disparo de armas.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `categoria` varchar(45) NOT NULL,
  `precio` float NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juegos`
--

INSERT INTO `juegos` (`id`, `nombre`, `categoria`, `precio`, `fecha`) VALUES
(2, 'Grand Theft Auto V', 'Aventura', 29.99, '2013-09-17'),
(3, 'Tetris', 'Puzzle', 4.99, '1984-06-06'),
(4, 'Wii Sports', 'Deportes', 19.99, '2006-11-19'),
(5, 'PUBG', 'Battle royale', 29.99, '2017-12-20'),
(6, 'Super Mario Bros', 'Aventura', 9.99, '1985-09-13'),
(7, 'Mario Kart 8 Deluxe', 'Carreras', 59.99, '2017-04-28'),
(8, 'Red Dead Redemption', 'Acción', 59.99, '2018-10-26'),
(9, 'Pokemon Primera Generacion', 'Carreras', 29.99, '1996-02-27'),
(10, 'Terraria', 'Sandbox', 14.99, '2011-05-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `rol`) VALUES
(1, 'matias', '090c36e3bb39377468363197afb3e91b', 'usuario'),
(2, 'facundo', '242ac92bff7cc0bf1f52de2f254b27a8', 'usuario'),
(3, 'messi', '1463ccd2104eeb36769180b8a0c86bb6', 'usuario'),
(4, 'kratos', '9df59d4c785363a0f8148f5d5e428354', 'usuario'),
(5, 'juan', 'a94652aa97c7211ba8954dd15a3cf838', 'usuario'),
(6, 'webadmin', '21232f297a57a5a743894a0e4a801fc3', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`nombre`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria` (`categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD CONSTRAINT `juegos_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`nombre`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
