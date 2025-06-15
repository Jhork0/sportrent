-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql211.byethost31.com
-- Tiempo de generación: 15-06-2025 a las 15:10:38
-- Versión del servidor: 10.6.19-MariaDB
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
-- Base de datos: `b31_39215483_sportsRent`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administra`
--

CREATE TABLE `administra` (
  `id_admin` varchar(10) NOT NULL,
  `cedula_propietario` varchar(20) NOT NULL,
  `cod_cancha` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calfi_usuario`
--

CREATE TABLE `calfi_usuario` (
  `codig_aler` varchar(10) NOT NULL,
  `id_califi` varchar(10) NOT NULL,
  `cedula_cliente` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificacion`
--

CREATE TABLE `calificacion` (
  `id_calificacion` varchar(10) NOT NULL,
  `puntuacion` int(5) NOT NULL,
  `comentario` text NOT NULL,
  `id_reserva` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `cedula_calificador` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calif_cancha`
--

CREATE TABLE `calif_cancha` (
  `codigo_ale` varchar(10) NOT NULL,
  `id_calficacion` varchar(10) NOT NULL,
  `id_cancha` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancha`
--

CREATE TABLE `cancha` (
  `id_cancha` varchar(10) NOT NULL,
  `nombre_cancha` varchar(100) NOT NULL,
  `tipo_cancha` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `valor_hora` int(10) NOT NULL,
  `hora_apertura` varchar(15) NOT NULL,
  `hora_cierre` varchar(15) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `foto` mediumblob DEFAULT NULL,
  `direccion_cancha` varchar(240) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credencial`
--

CREATE TABLE `credencial` (
  `id_credencial` varchar(10) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` varchar(20) NOT NULL,
  `valor_aPagar` int(10) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha_emision` varchar(20) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `id_reserva` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `cedula_persona` varchar(20) NOT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `cedula_propietario` varchar(20) NOT NULL,
  `tipo_documento` varchar(100) NOT NULL,
  `id_credencial` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperacion`
--

CREATE TABLE `recuperacion` (
  `id_recuperacion` int(11) NOT NULL,
  `id_credencial` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `expiracion` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0,
  `creado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperacion_cuenta`
--

CREATE TABLE `recuperacion_cuenta` (
  `identificacion` varchar(50) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` varchar(20) NOT NULL,
  `fecha_reserva` date DEFAULT NULL,
  `hora_inicio` varchar(20) DEFAULT NULL,
  `hora_final` varchar(20) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `cedula_persona` varchar(20) DEFAULT NULL,
  `id_cancha` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cedula_persona` varchar(20) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `id_credencial` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calfi_usuario`
--
ALTER TABLE `calfi_usuario`
  ADD PRIMARY KEY (`codig_aler`),
  ADD KEY `cedula_cliente` (`cedula_cliente`),
  ADD KEY `id_califi` (`id_califi`);

--
-- Indices de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `cedula_calificador` (`cedula_calificador`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `calif_cancha`
--
ALTER TABLE `calif_cancha`
  ADD PRIMARY KEY (`codigo_ale`),
  ADD KEY `id_calficacion` (`id_calficacion`),
  ADD KEY `id_cancha` (`id_cancha`);

--
-- Indices de la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD PRIMARY KEY (`id_cancha`);

--
-- Indices de la tabla `credencial`
--
ALTER TABLE `credencial`
  ADD PRIMARY KEY (`id_credencial`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cedula_persona`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`cedula_propietario`),
  ADD KEY `id_credencial` (`id_credencial`);

--
-- Indices de la tabla `recuperacion`
--
ALTER TABLE `recuperacion`
  ADD PRIMARY KEY (`id_recuperacion`);

--
-- Indices de la tabla `recuperacion_cuenta`
--
ALTER TABLE `recuperacion_cuenta`
  ADD PRIMARY KEY (`identificacion`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `cedula_persona` (`cedula_persona`),
  ADD KEY `id_cancha` (`id_cancha`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cedula_persona`),
  ADD KEY `id_credencial` (`id_credencial`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `recuperacion`
--
ALTER TABLE `recuperacion`
  MODIFY `id_recuperacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calfi_usuario`
--
ALTER TABLE `calfi_usuario`
  ADD CONSTRAINT `calfi_usuario_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `usuario` (`cedula_persona`),
  ADD CONSTRAINT `calfi_usuario_ibfk_2` FOREIGN KEY (`id_califi`) REFERENCES `calificacion` (`id_calificacion`);

--
-- Filtros para la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD CONSTRAINT `calificacion_ibfk_1` FOREIGN KEY (`cedula_calificador`) REFERENCES `persona` (`cedula_persona`),
  ADD CONSTRAINT `calificacion_ibfk_2` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`);

--
-- Filtros para la tabla `calif_cancha`
--
ALTER TABLE `calif_cancha`
  ADD CONSTRAINT `calif_cancha_ibfk_1` FOREIGN KEY (`id_calficacion`) REFERENCES `calificacion` (`id_calificacion`),
  ADD CONSTRAINT `calif_cancha_ibfk_2` FOREIGN KEY (`id_cancha`) REFERENCES `cancha` (`id_cancha`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`cedula_propietario`) REFERENCES `persona` (`cedula_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proveedor_ibfk_2` FOREIGN KEY (`id_credencial`) REFERENCES `credencial` (`id_credencial`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`cedula_persona`) REFERENCES `usuario` (`cedula_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_cancha`) REFERENCES `cancha` (`id_cancha`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`cedula_persona`) REFERENCES `persona` (`cedula_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_credencial`) REFERENCES `credencial` (`id_credencial`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
