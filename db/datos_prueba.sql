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

/*Data for the table `cita` */

insert  into `cita`(`id_cita`,`vehiculo_matricula`,`mecanico_login`,`fecha`,`motivo`,`estado`,`horas_trabajadas`) values 
(1,'1234ABC','MC01','2026-10-05 09:00:00','Cambio de aceite y filtro','finalizada',1.50),
(2,'9012GHI','MC02','2026-10-07 10:30:00','Cambio de pastillas y discos de freno','finalizada',2.00),
(3,'7890MNP','MC03','2026-10-09 08:30:00','Sustitución de la correa de distribución','finalizada',4.00),
(4,'6789VWX','MC04','2026-10-13 12:00:00','Cambio de batería','finalizada',0.50),
(5,'0123YZB','MC02','2026-10-16 09:00:00','Cambio de bujías y filtro de aire','finalizada',1.00),
(6,'8901GHJ','MC03','2026-10-22 11:00:00','Cambio de embrague','en_reparacion',3.50),
(7,'3456JKL','MC01','2026-10-23 09:00:00','Cambio de escobillas y revisión general','en_reparacion',0.50),
(8,'5678DEF',NULL,'2026-11-02 16:00:00','Ruido en la suspensión','pendiente',0.00),
(9,'4567CDF',NULL,'2026-11-03 17:30:00','Revisión previa a la ITV','pendiente',0.00),
(10,'2468KLM','MC04','2026-11-04 09:00:00','Cambio de pastillas de freno','confirmada',0.00),
(11,'2345RST','MC01','2026-11-05 10:00:00','Revisión de los 60.000 km','confirmada',0.00),
(12,'1234ABC',NULL,'2026-11-06 12:30:00','Fallo del aire acondicionado','pendiente',0.00),
(13,'9012GHI','MC02','2026-10-02 09:30:00','Ruidos en el motor','cancelada',0.00),
(14,'7890MNP',NULL,'2026-11-10 09:00:00','Cambio de aceite','pendiente',0.00);

/*Data for the table `cita_repuesto` */

insert  into `cita_repuesto`(`id_cita`,`id_repuesto`,`cantidad`,`precio_unitario`) values 
(1,1,1,45.00),
(1,2,1,12.50),
(2,4,1,55.00),
(2,5,1,90.00),
(3,9,1,185.00),
(4,6,1,110.00),
(5,3,1,18.00),
(5,8,4,9.50),
(6,10,1,260.00),
(7,7,1,22.00);

/*Data for the table `cliente` */

insert  into `cliente`(`dni`,`usuario_login`,`nombre`,`apellidos`,`telefono`) values 
('12345678A','juan@correo.com','Juan','Martínez Pérez','600111222'),
('23456789B','maria@correo.com','María','López García','600333444'),
('34567890C',NULL,'Carlos','Navarro Díaz','600555666'),
('45678901D','ana@correo.com','Ana','Belmonte Sala','600777888'),
('56789012E','david@correo.com','David','Soler Mira','600999000'),
('67890123F','elena@correo.com','Elena','Prieto Lorca','611222333'),
('78901234G',NULL,'Antonio','Ruiz Hernández','611444555'),
('89012345H','pablo@correo.com','Pablo','Cano Vidal','611666777');

/*Data for the table `factura` */

insert  into `factura`(`numero_factura`,`id_cita`,`fecha`,`mano_obra`,`repuestos`,`subtotal`,`iva`,`total`) values 
(1,1,'2026-10-05',67.50,57.50,125.00,26.25,151.25),
(2,2,'2026-10-07',90.00,145.00,235.00,49.35,284.35),
(3,3,'2026-10-09',180.00,185.00,365.00,76.65,441.65),
(4,4,'2026-10-13',22.50,110.00,132.50,27.83,160.33),
(5,5,'2026-10-16',45.00,56.00,101.00,21.21,122.21);

/*Data for the table `repuesto` */

insert  into `repuesto`(`id_repuesto`,`referencia`,`nombre`,`precio`,`stock`) values 
(1,'ACE-5W30','Aceite motor 5W30 (5 L)',45.00,20),
(2,'FIL-ACE','Filtro de aceite',12.50,30),
(3,'FIL-AIRE','Filtro de aire',18.00,25),
(4,'PAST-DEL','Pastillas de freno delanteras',55.00,15),
(5,'DISC-DEL','Discos de freno delanteros',90.00,8),
(6,'BAT-70AH','Batería 70 Ah',110.00,6),
(7,'ESC-LIMP','Escobillas limpiaparabrisas',22.00,40),
(8,'BUJ-NGK','Bujía NGK',9.50,50),
(9,'KIT-DIST','Kit de distribución',185.00,4),
(10,'KIT-EMB','Kit de embrague',260.00,3);

/*Data for the table `usuario` */

insert  into `usuario`(`login`,`password`,`nombre`,`rol`,`auth_key`) values 
('AD01','provisional','Administrador','admin',NULL),
('ana@correo.com','provisional','Ana Belmonte','cliente',NULL),
('david@correo.com','provisional','David Soler','cliente',NULL),
('elena@correo.com','provisional','Elena Prieto','cliente',NULL),
('juan@correo.com','provisional','Juan Martínez','cliente',NULL),
('maria@correo.com','provisional','María López','cliente',NULL),
('MC01','provisional','Pedro Sánchez','mecanico',NULL),
('MC02','provisional','Andrés Ruiz','mecanico',NULL),
('MC03','provisional','Lucía Ferrer','mecanico',NULL),
('MC04','provisional','Javier Ortega','mecanico',NULL),
('OF01','provisional','Laura Gómez','oficina',NULL),
('OF02','provisional','Sergio Molina','oficina',NULL),
('pablo@correo.com','provisional','Pablo Cano','cliente',NULL);

/*Data for the table `vehiculo` */

insert  into `vehiculo`(`matricula`,`cliente_dni`,`marca`,`modelo`) values 
('0123YZB','67890123F','Citroën','C3'),
('1234ABC','12345678A','Seat','Ibiza'),
('2345RST','45678901D','Peugeot','208'),
('2468KLM','89012345H','Audi','A3'),
('3456JKL','34567890C','Toyota','Corolla'),
('4567CDF','78901234G','Fiat','Panda'),
('5678DEF','12345678A','Renault','Clio'),
('6789VWX','56789012E','Opel','Corsa'),
('7890MNP','45678901D','Volkswagen','Golf'),
('8901GHJ','89012345H','BMW','Serie 1'),
('9012GHI','23456789B','Ford','Focus');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
