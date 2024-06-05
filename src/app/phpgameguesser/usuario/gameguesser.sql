-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-05-2024 a las 08:52:36
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
-- Base de datos: `gameguesser`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `biblioteca_juego`
--

CREATE TABLE `biblioteca_juego` (
  `id_biblioteca_juego` int(3) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `fecha` int(4) NOT NULL,
  `nota` int(2) NOT NULL,
  `companion` varchar(60) NOT NULL,
  `nivel` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `biblioteca_juego`
--

INSERT INTO `biblioteca_juego` (`id_biblioteca_juego`, `nombre`, `fecha`, `nota`, `companion`, `nivel`) VALUES
(1, 'Dark Souls 3', 2016, 89, 'FromSoftware', 1),
(2, 'The Legend of Zelda: Breath of the Wild', 2017, 97, 'Nintendo', 1),
(3, 'Minecraft', 2011, 93, 'Mojang Studios', 1),
(4, 'League of Legends', 2009, 78, 'Riot Games', 1),
(5, 'Grand Theft Auto V', 2013, 97, 'Rockstar Games', 1),
(6, 'Red Dead Redemption 2', 2018, 97, 'Rockstar Games', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(3) NOT NULL,
  `genero` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `genero`) VALUES
(1, 'Accion'),
(2, 'Rol'),
(3, 'Aventura'),
(4, 'Accion y aventura'),
(5, 'RPG '),
(6, 'Mundo abierto'),
(7, 'Construcción'),
(8, 'supervivencia'),
(9, 'Sandbox'),
(10, 'MOBA'),
(11, 'RTS'),
(12, 'deporte electronico'),
(13, 'crimen y delincuencia'),
(14, 'Western');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero_juego`
--

CREATE TABLE `genero_juego` (
  `id_genero` int(3) NOT NULL,
  `id_biblioteca_juego` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero_juego`
--

INSERT INTO `genero_juego` (`id_genero`, `id_biblioteca_juego`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(4, 5),
(5, 2),
(5, 6),
(6, 2),
(6, 5),
(6, 6),
(7, 3),
(8, 3),
(9, 3),
(10, 4),
(11, 4),
(12, 4),
(13, 5),
(14, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen`
--

CREATE TABLE `imagen` (
  `id_imagen` int(3) NOT NULL,
  `id_biblioteca_juego` int(3) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagen`
--

INSERT INTO `imagen` (`id_imagen`, `id_biblioteca_juego`, `nombre`) VALUES
(1, 1, '../assets/images/gameimg/ds3(1).png'),
(2, 1, '../assets/images/gameimg/ds3(2).png'),
(3, 1, '../assets/images/gameimg/ds3(3).png'),
(4, 1, '../assets/images/gameimg/ds3(4).png'),
(5, 1, '../assets/images/gameimg/ds3(5).png'),
(6, 2, '../assets/images/gameimg/zelda_botw(1).png'),
(7, 2, '../assets/images/gameimg/zelda_botw(2).png'),
(8, 2, '../assets/images/gameimg/zelda_botw(3).png'),
(9, 2, '../assets/images/gameimg/zelda_botw(4).png'),
(10, 2, '../assets/images/gameimg/zelda_botw(5).png'),
(11, 3, '../assets/images/gameimg/minecraft(1).png'),
(12, 3, '../assets/images/gameimg/minecraft(2).png'),
(13, 3, '../assets/images/gameimg/minecraft(3).png'),
(14, 3, '../assets/images/gameimg/minecraft(4).png'),
(15, 3, '../assets/images/gameimg/minecraft(5).png'),
(16, 4, '../assets/images/gameimg/lol(1).png'),
(17, 4, '../assets/images/gameimg/lol(2).png'),
(18, 4, '../assets/images/gameimg/lol(3).png'),
(19, 4, '../assets/images/gameimg/lol(4).png'),
(20, 4, '../assets/images/gameimg/lol(5).png'),
(21, 5, '../assets/images/gameimg/gtav(1).png'),
(22, 5, '../assets/images/gameimg/gtav(2).png'),
(23, 5, '../assets/images/gameimg/gtav(3).png'),
(24, 5, '../assets/images/gameimg/gtav(4).png'),
(25, 5, '../assets/images/gameimg/gtav(5).png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `musica`
--

CREATE TABLE `musica` (
  `id_musica` int(3) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `id_biblioteca_juego` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plataforma`
--

CREATE TABLE `plataforma` (
  `id_plataforma` int(3) NOT NULL,
  `nombre` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plataforma`
--

INSERT INTO `plataforma` (`id_plataforma`, `nombre`) VALUES
(1, 'PlayStation 4'),
(2, 'Xbox One'),
(3, 'PC'),
(4, 'PlayStation 5'),
(5, 'Xbox Series X/S'),
(6, 'Nintendo Switch'),
(7, 'Nintendo 3DS'),
(8, 'PlayStation Vita'),
(9, 'Wii U'),
(10, 'Xbox 360'),
(11, 'PlayStation 3'),
(12, 'Wii'),
(13, 'PlayStation 2'),
(14, 'Xbox'),
(15, 'PSP'),
(16, 'Nintendo DS'),
(17, 'Game Boy Advance'),
(18, 'Nintendo 64'),
(19, 'PlayStation '),
(20, 'Mega Drive'),
(21, 'Game Boy'),
(22, 'Sega Dreamcast');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plataforma_juego`
--

CREATE TABLE `plataforma_juego` (
  `id_plataforma` int(3) NOT NULL,
  `id_biblioteca_juego` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plataforma_juego`
--

INSERT INTO `plataforma_juego` (`id_plataforma`, `id_biblioteca_juego`) VALUES
(1, 1),
(1, 6),
(2, 1),
(2, 3),
(2, 6),
(3, 1),
(3, 3),
(3, 4),
(3, 6),
(6, 2),
(6, 3),
(9, 2),
(10, 5),
(11, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ranking`
--

CREATE TABLE `ranking` (
  `id_ranking` int(1) NOT NULL,
  `id` int(3) NOT NULL,
  `puntos` int(3) DEFAULT NULL,
  `dificultad` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ranking`
--

INSERT INTO `ranking` (`id_ranking`, `id`, `puntos`, `dificultad`) VALUES
(1, 1, 30, NULL),
(2, 15, 25, NULL),
(3, 2, 23, NULL),
(6, 14, 30, NULL),
(9, 19, 18, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(3) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `clave` varchar(60) NOT NULL,
  `admin` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `email`, `clave`, `admin`) VALUES
(1, 'manu', 'manu@gmail.com', '1234', '1'),
(2, 'rubenn', 'ruben@gmail.com', '1234', '0'),
(14, 'fer', 'fer@gmail.com', '1234', '0'),
(15, 'jose', 'jose@gmail.com', '1234', '0'),
(19, 'Franchesco Bergol', 'rayomak@gmail.com', 'fia', '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `biblioteca_juego`
--
ALTER TABLE `biblioteca_juego`
  ADD PRIMARY KEY (`id_biblioteca_juego`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `genero_juego`
--
ALTER TABLE `genero_juego`
  ADD PRIMARY KEY (`id_genero`,`id_biblioteca_juego`),
  ADD KEY `id_biblioteca_juego` (`id_biblioteca_juego`);

--
-- Indices de la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `fk_imagen_biblioteca_juego` (`id_biblioteca_juego`);

--
-- Indices de la tabla `musica`
--
ALTER TABLE `musica`
  ADD PRIMARY KEY (`id_musica`),
  ADD KEY `id_biblioteca_juego` (`id_biblioteca_juego`);

--
-- Indices de la tabla `plataforma`
--
ALTER TABLE `plataforma`
  ADD PRIMARY KEY (`id_plataforma`);

--
-- Indices de la tabla `plataforma_juego`
--
ALTER TABLE `plataforma_juego`
  ADD PRIMARY KEY (`id_plataforma`,`id_biblioteca_juego`),
  ADD KEY `id_biblioteca_juego` (`id_biblioteca_juego`);

--
-- Indices de la tabla `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id_ranking`),
  ADD KEY `fk_ranking_usuario` (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `biblioteca_juego`
--
ALTER TABLE `biblioteca_juego`
  MODIFY `id_biblioteca_juego` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `imagen`
--
ALTER TABLE `imagen`
  MODIFY `id_imagen` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `musica`
--
ALTER TABLE `musica`
  MODIFY `id_musica` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plataforma`
--
ALTER TABLE `plataforma`
  MODIFY `id_plataforma` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `ranking`
--
ALTER TABLE `ranking`
  MODIFY `id_ranking` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `genero_juego`
--
ALTER TABLE `genero_juego`
  ADD CONSTRAINT `genero_juego_ibfk_1` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`),
  ADD CONSTRAINT `genero_juego_ibfk_2` FOREIGN KEY (`id_biblioteca_juego`) REFERENCES `biblioteca_juego` (`id_biblioteca_juego`);

--
-- Filtros para la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD CONSTRAINT `fk_imagen_biblioteca_juego` FOREIGN KEY (`id_biblioteca_juego`) REFERENCES `biblioteca_juego` (`id_biblioteca_juego`);

--
-- Filtros para la tabla `musica`
--
ALTER TABLE `musica`
  ADD CONSTRAINT `musica_ibfk_1` FOREIGN KEY (`id_biblioteca_juego`) REFERENCES `biblioteca_juego` (`id_biblioteca_juego`);

--
-- Filtros para la tabla `plataforma_juego`
--
ALTER TABLE `plataforma_juego`
  ADD CONSTRAINT `plataforma_juego_ibfk_1` FOREIGN KEY (`id_plataforma`) REFERENCES `plataforma` (`id_plataforma`),
  ADD CONSTRAINT `plataforma_juego_ibfk_2` FOREIGN KEY (`id_biblioteca_juego`) REFERENCES `biblioteca_juego` (`id_biblioteca_juego`);

--
-- Filtros para la tabla `ranking`
--
ALTER TABLE `ranking`
  ADD CONSTRAINT `fk_ranking_usuario` FOREIGN KEY (`id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
