-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-09-2024 a las 01:50:00
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reparaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notebooks`
--

CREATE TABLE `notebooks` (
  `id` int(11) UNSIGNED NOT NULL,
  `modelo` varchar(255) NOT NULL,
  `problemas` text DEFAULT NULL,
  `estado` enum('nuevo','usado','reparado','dañado') NOT NULL,
  `numero` int(11) NOT NULL,
  `fecha_revision` date NOT NULL,
  `hora_revision` time NOT NULL,
  `codigo_serie` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `notebooks`
--

INSERT INTO `notebooks` (`id`, `modelo`, `problemas`, `estado`, `numero`, `fecha_revision`, `hora_revision`, `codigo_serie`) VALUES
(1, 'samsung ', 'bbrfbrbrr', 'usado', 1111, '1111-11-11', '11:01:00', 0),
(2, 'samsung ', 'ninguno', 'nuevo', 12, '1111-11-11', '11:01:00', 0),
(5, 'samsung ', '111', 'nuevo', 12, '1111-11-11', '11:11:00', 0),
(6, 'samsung ', '111', 'nuevo', 12, '1111-11-11', '11:11:00', 0),
(7, 'samsung ', 'errores de inicio', 'dañado', 12, '1111-11-11', '11:11:00', 1212121221),
(8, 'samsung ', 'error software', 'dañado', 12, '1000-02-12', '12:12:00', 0),
(9, 'qq', 'qqq', 'usado', 12, '2024-09-23', '20:41:00', 0),
(10, 'samsung', 'problemas de pantalla', 'dañado', 3, '2024-09-23', '20:46:00', 2147483647);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas`
--

CREATE TABLE `salidas` (
  `id` int(11) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `profesor_responsable` varchar(255) NOT NULL,
  `curso` varchar(50) NOT NULL,
  `numero_computadoras` int(11) NOT NULL,
  `numero_cargadores` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `salidas`
--

INSERT INTO `salidas` (`id`, `fecha`, `hora`, `profesor_responsable`, `curso`, `numero_computadoras`, `numero_cargadores`) VALUES
(9, '0011-11-11', '01:01:00', '111', '111', 111, 1),
(10, '0011-11-11', '01:01:00', '111', '111', 111, 1),
(13, '1111-11-11', '11:11:00', '1111', '5', 11, 11),
(14, '1111-11-11', '11:11:00', '1111', '5', 11, 11),
(15, '1111-11-11', '11:11:00', 'francisco', '111', 11, 11);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `notebooks`
--
ALTER TABLE `notebooks`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `notebooks`
--
ALTER TABLE `notebooks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
