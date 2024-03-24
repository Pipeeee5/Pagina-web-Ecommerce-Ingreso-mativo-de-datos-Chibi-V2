-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-03-2024 a las 01:59:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_online`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descuento` tinyint(3) NOT NULL DEFAULT 0,
  `id_categoria` int(11) NOT NULL,
  `activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `descuento`, `id_categoria`, `activo`) VALUES
(1, 'One Piece Volumen 3.', 'El capitán Buggy \"El payaso\" y Luffy, tienen una espectacular pelea como solo podría suceder con dos poseedores de las habilidades de las frutas del diablo. Durante la pelea aprendemos más del pasado en común de Shanks y Buggy. Ahora que Nami y Zoro se han unido a la tripulación de Luffy es momento de continuar su camino hacia la \"Grand Line\". Han desembarcado en una villa para buscar un barco más resistente y algo de comida. Ahí se encuentran con el orgulloso pero mentiroso Usopp y es gracias a él que conocen a Kaya, la persona más rica de la villa, a quien le piden un barco. Sin embargo, el mayordomo Kurahadol se entromete y Luffy tiene que renunciar trágicamente a su petición.', 30.00, 10, 1, 1),
(2, 'Jujutsu Volumen 1.', 'jujutsu volumen 1', 30.00, 0, 1, 1),
(3, 'Dragon Ball super Volumen 1.', 'Dragon Ball super Volumen 1.', 35.00, 0, 1, 1),
(4, 'Kimetsu No Yaiba Volumen 1.', 'Kimetsu No Yaiba Volumen 1.', 20.00, 0, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
