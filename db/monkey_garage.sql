/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - monkey_garage
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`monkey_garage` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `monkey_garage`;

/*Table structure for table `cita` */

DROP TABLE IF EXISTS `cita`;

CREATE TABLE `cita` (
  `id_cita` int(11) NOT NULL AUTO_INCREMENT,
  `vehiculo_matricula` varchar(10) NOT NULL,
  `mecanico_login` varchar(100) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `estado` enum('pendiente','confirmada','en_reparacion','finalizada','cancelada') NOT NULL DEFAULT 'pendiente',
  `horas_trabajadas` decimal(5,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id_cita`),
  KEY `vehiculo_matricula` (`vehiculo_matricula`),
  KEY `mecanico_login` (`mecanico_login`),
  CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`vehiculo_matricula`) REFERENCES `vehiculo` (`matricula`) ON UPDATE CASCADE,
  CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`mecanico_login`) REFERENCES `usuario` (`login`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chk_cita_horas` CHECK (`horas_trabajadas` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `cita_repuesto` */

DROP TABLE IF EXISTS `cita_repuesto`;

CREATE TABLE `cita_repuesto` (
  `id_cita` int(11) NOT NULL,
  `id_repuesto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_cita`,`id_repuesto`),
  KEY `id_repuesto` (`id_repuesto`),
  CONSTRAINT `cita_repuesto_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `cita` (`id_cita`) ON DELETE CASCADE,
  CONSTRAINT `cita_repuesto_ibfk_2` FOREIGN KEY (`id_repuesto`) REFERENCES `repuesto` (`id_repuesto`),
  CONSTRAINT `chk_cita_repuesto_cantidad` CHECK (`cantidad` > 0),
  CONSTRAINT `chk_cita_repuesto_precio` CHECK (`precio_unitario` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `cliente` */

DROP TABLE IF EXISTS `cliente`;

CREATE TABLE `cliente` (
  `dni` varchar(9) NOT NULL,
  `usuario_login` varchar(100) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  PRIMARY KEY (`dni`),
  UNIQUE KEY `usuario_login` (`usuario_login`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`usuario_login`) REFERENCES `usuario` (`login`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `factura` */

DROP TABLE IF EXISTS `factura`;

CREATE TABLE `factura` (
  `numero_factura` int(11) NOT NULL AUTO_INCREMENT,
  `id_cita` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `mano_obra` decimal(10,2) NOT NULL,
  `repuestos` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`numero_factura`),
  UNIQUE KEY `id_cita` (`id_cita`),
  CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `cita` (`id_cita`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `repuesto` */

DROP TABLE IF EXISTS `repuesto`;

CREATE TABLE `repuesto` (
  `id_repuesto` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_repuesto`),
  UNIQUE KEY `referencia` (`referencia`),
  CONSTRAINT `chk_repuesto_stock` CHECK (`stock` >= 0),
  CONSTRAINT `chk_repuesto_precio` CHECK (`precio` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `login` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` enum('admin','oficina','mecanico','cliente') NOT NULL,
  `auth_key` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `vehiculo` */

DROP TABLE IF EXISTS `vehiculo`;

CREATE TABLE `vehiculo` (
  `matricula` varchar(10) NOT NULL,
  `cliente_dni` varchar(9) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  PRIMARY KEY (`matricula`),
  KEY `cliente_dni` (`cliente_dni`),
  CONSTRAINT `vehiculo_ibfk_1` FOREIGN KEY (`cliente_dni`) REFERENCES `cliente` (`dni`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
