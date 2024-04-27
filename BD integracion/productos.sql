-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-04-2024 a las 00:09:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
(3, 'Naruto.', '<br>Dragon Ball super Volumen 1.<br>\r\nDragon Ball Super (ドラゴンボール超スーパー, Doragon Bōru Sūpā) es la serie animada de televisión sucesora de Dragon Ball Kai, estrenada el 5 de julio de 2015. Fue producida por Toei Animation y Fuji Television en colaboración con la revista V-Jump de Shueisha. La obra narra los acontecimientos posteriores inmediatos al Arco de Majin-Boo, basados en conceptos originales de historia y personajes del autor Akira Toriyama.', 30.00, 0, 1, 1),
(4, 'Kimetsu No Yaiba Volumen 1.', '<br>Kimetsu No Yaiba Volumen 1.<br>\r\n\r\nKimetsu no Yaiba (鬼滅の刃, La espada exterminadora de Demonios?), conocida en España como Kimetsu no Yaiba: Guardianes de la Noche y en inglés Demon Slayer: Kimetsu no Yaiba, es una serie de manga y anime a la que está enteramente dedicada esta wiki. Es una obra de Koyoharu Gotoge que se publicó semanalmente en las páginas de la revista Shūkan Shōnen Jump de la editorial japonesa Shueisha. La obra sigue las aventuras de Tanjirō Kamado, un adolescente cuya familia fue cruelmente asesinada por un Demonio el cual convirtió a su hermana Nezuko en una de estas criaturas, obligando a Tanjirō a emprender un viaje para cazar a estos seres y de paso ayudar a su hermana a recuperar su humanidad.', 20.00, 25, 1, 1),
(5, 'Boku No Kokoro vol1.', 'Boko No Kokoro vol1.\r\nIchikawa Kyotaro es un joven tímido en el escalafón final de los grupos sociales del instituto, pero cree que protagoniza un tortuoso thriller psicológico en su vida diaria. Se pasa los días pensando en como interrumpir las apacibles vidas de sus compañeros y en cortejar a Anna Yamada, la idol de la clase.', 20.00, 5, 1, 1),
(6, 'Shingeki No Kyojin Volumen1.', 'Shingeki no Kyojin (進撃の巨人? «Titán de ataque»), también conocida en países de habla hispana como Ataque a los titanes y Ataque de los titanes,2​n. 2​ es una serie de manga japonesa escrita e ilustrada por Hajime Isayama. El manga se publicó en septiembre de 2009 en la revista Bessatsu Shōnen Magazine de la editorial Kōdansha y fue difundida de forma mensual hasta abril de 2021 con un total de 139 capítulos. Su historia terminó después de casi doce años. En España, el manga es distribuido por Norma Editorial y en Hispanoamérica por la editorial Panini; salvo en Argentina, donde la encargada es la editorial Ovni Press.', 30.00, 0, 1, 1),
(7, 'Spy x Family', 'SPY X FAMILY es una serie de anime sobre un espía que debe formar rápidamente una familia central para mantener el equilibrio de poder entre dos naciones fronterizas, Westalis y Ostania, que disfrutan de un periodo de coexistencia pacífica tras años de guerra. A cada lado de la frontera aparecen complots para reanudar el conflicto, lo que requiere que Twilight (Takuya Aguchi/Alex Organ), un maestro espía de Westalis, los desbarate. Él toma cada trabajo con calma, pero su última misión, la Operación Strix, le exige que se haga pasar por padre de familia y enliste a un niño en la prestigiosa Academia Edén para infiltrarse en el círculo íntimo de uno de los líderes políticos pro-guerra de Ostania. Twilight, que opera bajo el alias de Loid Forger, adopta rápidamente a Anya (Atsumi Tanezaki/Megan Shipman), una niña inteligente que, sin saberlo, puede leer la mente. También se casa con Yor (Saori Hayami/Natalie Van Sistine) para crear la unidad doméstica perfecta. Pero mientras Loid trabaja en completar su misión, Yor esconde sus propios secretos. Ninguno de los dos sospecha que los otros no son quienes dicen ser, y ninguno revela lo que realmente está tramando, pero de alguna manera se las arreglan para vivir felizmente como una familia y amarse el uno al otro.', 21.00, 5, 1, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
