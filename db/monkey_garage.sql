-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-10-2026 a las 13:09:40
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
-- Base de datos: `monkey_garage`
--
CREATE DATABASE IF NOT EXISTS `monkey_garage` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `monkey_garage`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

DROP TABLE IF EXISTS `cita`;
CREATE TABLE IF NOT EXISTS `cita` (
  `id_cita` int(11) NOT NULL AUTO_INCREMENT,
  `vehiculo_matricula` varchar(10) NOT NULL,
  `mecanico_login` varchar(100) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `estado` enum('pendiente','confirmada','en_reparacion','finalizada','cancelada') NOT NULL DEFAULT 'pendiente',
  `horas_trabajadas` decimal(5,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id_cita`),
  KEY `vehiculo_matricula` (`vehiculo_matricula`),
  KEY `mecanico_login` (`mecanico_login`)
) ;

--
-- Truncar tablas antes de insertar `cita`
--

TRUNCATE TABLE `cita`;
--
-- Volcado de datos para la tabla `cita`
--

INSERT DELAYED IGNORE INTO `cita` (`id_cita`, `vehiculo_matricula`, `mecanico_login`, `fecha`, `motivo`, `estado`, `horas_trabajadas`) VALUES
(1, '1234ABC', 'MC01', '2026-10-05 09:00:00', 'Cambio de aceite y filtro', 'finalizada', 1.50),
(2, '9012GHI', 'MC02', '2026-10-07 10:30:00', 'Cambio de pastillas y discos de freno', 'finalizada', 2.00),
(3, '7890MNP', 'MC03', '2026-10-09 08:30:00', 'Sustitución de la correa de distribución', 'finalizada', 4.00),
(4, '6789VWX', 'MC04', '2026-10-13 12:00:00', 'Cambio de batería', 'finalizada', 0.50),
(5, '0123YZB', 'MC02', '2026-10-16 09:00:00', 'Cambio de bujías y filtro de aire', 'finalizada', 1.00),
(6, '8901GHJ', 'MC03', '2026-10-22 11:00:00', 'Cambio de embrague', 'en_reparacion', 3.50),
(7, '3456JKL', 'MC01', '2026-10-23 09:00:00', 'Cambio de escobillas y revisión general', 'en_reparacion', 0.50),
(8, '5678DEF', NULL, '2026-11-02 16:00:00', 'Ruido en la suspensión', 'pendiente', 0.00),
(9, '4567CDF', NULL, '2026-11-03 17:30:00', 'Revisión previa a la ITV', 'pendiente', 0.00),
(10, '2468KLM', 'MC04', '2026-11-04 09:00:00', 'Cambio de pastillas de freno', 'confirmada', 0.00),
(11, '2345RST', 'MC01', '2026-11-05 10:00:00', 'Revisión de los 60.000 km', 'confirmada', 0.00),
(12, '1234ABC', NULL, '2026-11-06 12:30:00', 'Fallo del aire acondicionado', 'pendiente', 0.00),
(13, '9012GHI', 'MC02', '2026-10-02 09:30:00', 'Ruidos en el motor', 'cancelada', 0.00),
(14, '7890MNP', NULL, '2026-11-10 09:00:00', 'Cambio de aceite', 'pendiente', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita_repuesto`
--

DROP TABLE IF EXISTS `cita_repuesto`;
CREATE TABLE IF NOT EXISTS `cita_repuesto` (
  `id_cita` int(11) NOT NULL,
  `id_repuesto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_cita`,`id_repuesto`),
  KEY `id_repuesto` (`id_repuesto`)
) ;

--
-- Truncar tablas antes de insertar `cita_repuesto`
--

TRUNCATE TABLE `cita_repuesto`;
--
-- Volcado de datos para la tabla `cita_repuesto`
--

INSERT DELAYED IGNORE INTO `cita_repuesto` (`id_cita`, `id_repuesto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 45.00),
(1, 2, 1, 12.50),
(2, 4, 1, 55.00),
(2, 5, 1, 90.00),
(3, 9, 1, 185.00),
(4, 6, 1, 110.00),
(5, 3, 1, 18.00),
(5, 8, 4, 9.50),
(6, 10, 1, 260.00),
(7, 7, 1, 22.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `dni` varchar(9) NOT NULL,
  `usuario_login` varchar(100) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  PRIMARY KEY (`dni`),
  UNIQUE KEY `usuario_login` (`usuario_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncar tablas antes de insertar `cliente`
--

TRUNCATE TABLE `cliente`;
--
-- Volcado de datos para la tabla `cliente`
--

INSERT DELAYED IGNORE INTO `cliente` (`dni`, `usuario_login`, `nombre`, `apellidos`, `telefono`) VALUES
('12345678A', 'juan@correo.com', 'Juan', 'Martínez Pérez', '600111222'),
('23456789B', 'maria@correo.com', 'María', 'López García', '600333444'),
('34567890C', NULL, 'Carlos', 'Navarro Díaz', '600555666'),
('45678901D', 'ana@correo.com', 'Ana', 'Belmonte Sala', '600777888'),
('56789012E', 'david@correo.com', 'David', 'Soler Mira', '600999000'),
('67890123F', 'elena@correo.com', 'Elena', 'Prieto Lorca', '611222333'),
('78901234G', NULL, 'Antonio', 'Ruiz Hernández', '611444555'),
('89012345H', 'pablo@correo.com', 'Pablo', 'Cano Vidal', '611666777');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

DROP TABLE IF EXISTS `factura`;
CREATE TABLE IF NOT EXISTS `factura` (
  `numero_factura` int(11) NOT NULL AUTO_INCREMENT,
  `id_cita` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `mano_obra` decimal(10,2) NOT NULL,
  `repuestos` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`numero_factura`),
  UNIQUE KEY `id_cita` (`id_cita`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncar tablas antes de insertar `factura`
--

TRUNCATE TABLE `factura`;
--
-- Volcado de datos para la tabla `factura`
--

INSERT DELAYED IGNORE INTO `factura` (`numero_factura`, `id_cita`, `fecha`, `mano_obra`, `repuestos`, `subtotal`, `iva`, `total`) VALUES
(1, 1, '2026-10-05', 67.50, 57.50, 125.00, 26.25, 151.25),
(2, 2, '2026-10-07', 90.00, 145.00, 235.00, 49.35, 284.35),
(3, 3, '2026-10-09', 180.00, 185.00, 365.00, 76.65, 441.65),
(4, 4, '2026-10-13', 22.50, 110.00, 132.50, 27.83, 160.33),
(5, 5, '2026-10-16', 45.00, 56.00, 101.00, 21.21, 122.21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repuesto`
--

DROP TABLE IF EXISTS `repuesto`;
CREATE TABLE IF NOT EXISTS `repuesto` (
  `id_repuesto` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_repuesto`),
  UNIQUE KEY `referencia` (`referencia`)
) ;

--
-- Truncar tablas antes de insertar `repuesto`
--

TRUNCATE TABLE `repuesto`;
--
-- Volcado de datos para la tabla `repuesto`
--

INSERT DELAYED IGNORE INTO `repuesto` (`id_repuesto`, `referencia`, `nombre`, `precio`, `stock`) VALUES
(1, 'ACE-5W30', 'Aceite motor 5W30 (5 L)', 45.00, 20),
(2, 'FIL-ACE', 'Filtro de aceite', 12.50, 30),
(3, 'FIL-AIRE', 'Filtro de aire', 18.00, 25),
(4, 'PAST-DEL', 'Pastillas de freno delanteras', 55.00, 15),
(5, 'DISC-DEL', 'Discos de freno delanteros', 90.00, 8),
(6, 'BAT-70AH', 'Batería 70 Ah', 110.00, 6),
(7, 'ESC-LIMP', 'Escobillas limpiaparabrisas', 22.00, 40),
(8, 'BUJ-NGK', 'Bujía NGK', 9.50, 50),
(9, 'KIT-DIST', 'Kit de distribución', 185.00, 4),
(10, 'KIT-EMB', 'Kit de embrague', 260.00, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `login` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` enum('admin','oficina','mecanico','cliente') NOT NULL,
  `auth_key` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncar tablas antes de insertar `usuario`
--

TRUNCATE TABLE `usuario`;
--
-- Volcado de datos para la tabla `usuario`
--

INSERT DELAYED IGNORE INTO `usuario` (`login`, `password`, `nombre`, `rol`, `auth_key`) VALUES
('AD01', 'provisional', 'Administrador', 'admin', NULL),
('ana@correo.com', 'provisional', 'Ana Belmonte', 'cliente', NULL),
('david@correo.com', 'provisional', 'David Soler', 'cliente', NULL),
('elena@correo.com', 'provisional', 'Elena Prieto', 'cliente', NULL),
('juan@correo.com', 'provisional', 'Juan Martínez', 'cliente', NULL),
('maria@correo.com', 'provisional', 'María López', 'cliente', NULL),
('MC01', 'provisional', 'Pedro Sánchez', 'mecanico', NULL),
('MC02', 'provisional', 'Andrés Ruiz', 'mecanico', NULL),
('MC03', 'provisional', 'Lucía Ferrer', 'mecanico', NULL),
('MC04', 'provisional', 'Javier Ortega', 'mecanico', NULL),
('OF01', 'provisional', 'Laura Gómez', 'oficina', NULL),
('OF02', 'provisional', 'Sergio Molina', 'oficina', NULL),
('pablo@correo.com', 'provisional', 'Pablo Cano', 'cliente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

DROP TABLE IF EXISTS `vehiculo`;
CREATE TABLE IF NOT EXISTS `vehiculo` (
  `matricula` varchar(10) NOT NULL,
  `cliente_dni` varchar(9) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  PRIMARY KEY (`matricula`),
  KEY `cliente_dni` (`cliente_dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncar tablas antes de insertar `vehiculo`
--

TRUNCATE TABLE `vehiculo`;
--
-- Volcado de datos para la tabla `vehiculo`
--

INSERT DELAYED IGNORE INTO `vehiculo` (`matricula`, `cliente_dni`, `marca`, `modelo`) VALUES
('0123YZB', '67890123F', 'Citroën', 'C3'),
('1234ABC', '12345678A', 'Seat', 'Ibiza'),
('2345RST', '45678901D', 'Peugeot', '208'),
('2468KLM', '89012345H', 'Audi', 'A3'),
('3456JKL', '34567890C', 'Toyota', 'Corolla'),
('4567CDF', '78901234G', 'Fiat', 'Panda'),
('5678DEF', '12345678A', 'Renault', 'Clio'),
('6789VWX', '56789012E', 'Opel', 'Corsa'),
('7890MNP', '45678901D', 'Volkswagen', 'Golf'),
('8901GHJ', '89012345H', 'BMW', 'Serie 1'),
('9012GHI', '23456789B', 'Ford', 'Focus');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`vehiculo_matricula`) REFERENCES `vehiculo` (`matricula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`mecanico_login`) REFERENCES `usuario` (`login`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `cita_repuesto`
--
ALTER TABLE `cita_repuesto`
  ADD CONSTRAINT `cita_repuesto_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `cita` (`id_cita`) ON DELETE CASCADE,
  ADD CONSTRAINT `cita_repuesto_ibfk_2` FOREIGN KEY (`id_repuesto`) REFERENCES `repuesto` (`id_repuesto`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`usuario_login`) REFERENCES `usuario` (`login`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `cita` (`id_cita`);

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `vehiculo_ibfk_1` FOREIGN KEY (`cliente_dni`) REFERENCES `cliente` (`dni`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
