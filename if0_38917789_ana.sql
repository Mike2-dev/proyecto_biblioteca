-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql102.infinityfree.com
-- Tiempo de generación: 10-07-2025 a las 17:45:49
-- Versión del servidor: 11.4.7-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_38917789_ana`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estantes`
--

CREATE TABLE `estantes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `estantes`
--

INSERT INTO `estantes` (`id`, `nombre`, `usuario_id`) VALUES
(12, 'Halo', 15),
(6, 'ClÃ¡sicos', 9),
(10, 'Fantasia', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) DEFAULT NULL,
  `fecha_finalizacion` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `nota` text DEFAULT NULL,
  `calificacion` decimal(2,1) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `estante_id` int(11) DEFAULT NULL,
  `portada` varchar(255) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor`, `fecha_finalizacion`, `descripcion`, `nota`, `calificacion`, `tipo`, `estado`, `estante_id`, `portada`, `fecha_inicio`, `usuario_id`) VALUES
(31, 'Halo: La caida de Reach', 'Eric Nylund', NULL, 'Sinopsis de Halo: La caÃ­da de Reach\r\nMientras la sangrienta guerra entre los seres humanos y el Covenant lo arrasa todo en Halo, el destino de la humanidad podrÃ­a descansar en un solo guerrero, el Ãºltimo Spartan superviviente de una batalla legendaria... la desesperada lucha que conducirÃ­a a la humanidad hasta Halo: la caÃ­da del planeta Reach. He aquÃ­ la historia completa de ese glorioso y malhadado conflicto.', 'esta chido leanlo.', NULL, 'fisico', 'deseo', 12, 'https://pdlibrosmex.cdnstatics2.com/usuaris/libros/thumbs/efd8976f-13bf-45e4-b3d5-fdf4fe001912/d_360_620/portada_halo-la-caida-de-reach_eric-nylund_201602232348.webp', NULL, 15),
(30, 'Harry Potter', 'J K Rowling', NULL, '', '', NULL, 'digital', 'deseo', 10, 'imagenes/portadas686c3167e3f28_icono_ejemplo.jpg', NULL, 9),
(21, 'El prÃ­ncipe cautivo', 'S.A', NULL, 'Ninguna', '', NULL, 'fisico', 'deseo', NULL, 'imagenes/portadas/685629f0b8348_1000032499.jpg', NULL, 10),
(24, 'Cumbres borrascosas', 'Emily Bronte', NULL, '', '', NULL, 'fisico', 'deseo', 6, 'https://m.media-amazon.com/images/I/71qdlA1lNqL._UF894,1000_QL80_.jpg', NULL, 9),
(23, 'orgullo y prejuicio', 'jane austen', '2025-06-16', 'muy bueno', 'es interesante', '3.0', 'fisico', 'leido', 6, 'https://images.cdn3.buscalibre.com/fit-in/360x360/49/6c/496cc2d26070c4f19d7f8e93a09274ac.jpg', '2025-06-01', 9),
(25, 'Mujercitas', 'Jane Austen', '2025-06-23', '', 'muy bueno', '2.5', 'fisico', 'leido', 6, 'https://images.cdn1.buscalibre.com/fit-in/360x360/11/e5/11e528dde9f63691353797fe7efe9b5e.jpg', '2025-06-02', 9),
(26, 'Viaje al centro de la tierra', 'Julio Verne', '2025-06-08', 'es bueno', '', '2.5', 'fisico', 'abandonado', 6, 'https://static.s123-cdn-static-c.com/uploads/1887095/2000_5ea3c7e3496d9.jpg', '2025-06-01', 9),
(27, 'Harry Potter', 'J K Rowling', NULL, '', '', NULL, 'digital', 'deseo', NULL, 'https://basadoenhechosreales.com.ar/wp-content/uploads/como-saber-que-libro-de-harry-potter-es-original.webp', NULL, 9),
(28, 'Genesis', 'desconocido', NULL, '', '', NULL, 'fisico', 'pendiente', NULL, '', NULL, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `usuario`, `clave`, `foto_perfil`) VALUES
(16, 'grosendo@gmail.com', 'Goyis', '$2y$10$Eh82XSxY348tfDCNJWZTp.ojhpO2sqitUKGZVuqqxeSy/1nISogTS', 'imagenes/iconos/icono_perfil.webp'),
(9, 'lunafg712@gmail.com', 'Karo', '$2y$10$tQjRVb3uO7eMjy4qq9zlQut0GvYBQQ.kYaGRMh39u6L3UVvyXOjKe', 'https://i.pinimg.com/236x/21/85/45/218545fed0fc0be53db37b02e20311bb.jpg'),
(10, 'km2281567@gmail.com', 'Karla', '$2y$10$WHvAZ1uQDW//9NLeIanD4OPnUUELaT3gQ.kDFojPfAh9flEOzUpDu', NULL),
(11, 'anakarenrosendo273@gmail.com', 'Ana', '$2y$10$ihXMltJS0fl5GCYcoAaba.jEOm5Dhpjlr8fnlfvJ5fkmxI./Mc8v2', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQObVx36WvIsBCiuOpp486gRcjo9c9TgkI3pWjm9HNyrwC_Ae5s2ixO9AqcWj8z26myPkc&usqp=CAU'),
(15, 'ancientbeyder@gmail.com', 'StopAmister595', '$2y$10$/gvTxMFHd6nyJVmrMIkKveRl7GfFS9PfbUdi3zAE.B8QlNWSsNoj2', 'ðŸ‘¤');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estantes`
--
ALTER TABLE `estantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estantes_usuario` (`usuario_id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estante_id` (`estante_id`),
  ADD KEY `fk_libros_usuario` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estantes`
--
ALTER TABLE `estantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
