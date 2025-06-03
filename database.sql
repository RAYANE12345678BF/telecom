-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for rayane
CREATE DATABASE IF NOT EXISTS `rayane` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `rayane`;

-- Dumping structure for table rayane.addresses
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `wilaya` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address_line` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cite` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.addresses: ~0 rows (approximately)
DELETE FROM `addresses`;
INSERT INTO `addresses` (`id`, `wilaya`, `address_line`, `cite`) VALUES
	(1, 'guelma', 'hiahem abdelhamid', 'hiliopolis');

-- Dumping structure for table rayane.appointments
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_matricule` int DEFAULT NULL,
  `date` timestamp NOT NULL,
  `day_part` enum('morning','evening') COLLATE utf8mb4_general_ci NOT NULL,
  `is_absent` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `justification` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `late_hours` int unsigned DEFAULT NULL,
  `on_duty` time NOT NULL,
  `off_duty` time NOT NULL,
  `clock_in` time DEFAULT NULL,
  `clock_out` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_absenses_employees_2` (`employee_matricule`),
  CONSTRAINT `FK_absenses_employees_2` FOREIGN KEY (`employee_matricule`) REFERENCES `employees` (`matricule`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.appointments: ~132 rows (approximately)
DELETE FROM `appointments`;
INSERT INTO `appointments` (`id`, `employee_matricule`, `date`, `day_part`, `is_absent`, `justification`, `late_hours`, `on_duty`, `off_duty`, `clock_in`, `clock_out`) VALUES
	(1, 153, '2024-12-31 23:00:00', 'morning', '1', 'conge_annual', NULL, '08:00:00', '12:00:00', NULL, NULL),
	(2, 153, '2024-12-31 23:00:00', 'evening', '1', 'conge_annual', NULL, '13:00:00', '16:30:00', NULL, NULL),
	(3, 153, '2025-01-01 23:00:00', 'morning', '0', NULL, 2, '08:00:00', '12:00:00', '09:50:00', '11:00:00'),
	(4, 153, '2025-01-01 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:53:00', '18:30:00'),
	(5, 153, '2025-01-04 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', NULL, '12:01:00'),
	(6, 153, '2025-01-04 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:56:00', '17:14:00'),
	(7, 153, '2025-01-05 23:00:00', 'morning', '1', NULL, 4, '08:00:00', '12:00:00', NULL, NULL),
	(8, 153, '2025-01-05 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', '14:07:00', '17:27:00'),
	(9, 153, '2025-01-06 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:56:00', '11:51:00'),
	(10, 153, '2025-01-06 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', NULL, '17:08:00'),
	(11, 153, '2025-01-07 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:57:00', '12:07:00'),
	(12, 153, '2025-01-07 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:51:00', '17:08:00'),
	(13, 153, '2025-01-08 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', NULL, '12:00:00'),
	(14, 153, '2025-01-08 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:59:00', '17:30:00'),
	(15, 153, '2025-01-11 23:00:00', 'morning', '1', NULL, 4, '08:00:00', '12:00:00', NULL, NULL),
	(16, 153, '2025-01-11 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(17, 153, '2025-01-12 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:50:00', '12:19:00'),
	(18, 153, '2025-01-12 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:54:00', '18:02:00'),
	(19, 153, '2025-01-13 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:52:00', '12:02:00'),
	(20, 153, '2025-01-13 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:00:00', '17:12:00'),
	(21, 153, '2025-01-14 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:54:00', '12:05:00'),
	(22, 153, '2025-01-14 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:53:00', '17:17:00'),
	(23, 153, '2025-01-15 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:57:00', '12:01:00'),
	(24, 153, '2025-01-15 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:59:00', '16:37:00'),
	(25, 153, '2025-01-18 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:57:00', NULL),
	(26, 153, '2025-01-18 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:06:00', '16:33:00'),
	(27, 153, '2025-01-19 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:55:00', '12:40:00'),
	(28, 153, '2025-01-19 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:03:00', '16:48:00'),
	(29, 153, '2025-01-20 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', '12:00:00'),
	(30, 153, '2025-01-20 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:55:00', '17:34:00'),
	(31, 153, '2025-01-21 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:52:00', NULL),
	(32, 153, '2025-01-21 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:12:00', '16:51:00'),
	(33, 153, '2025-01-22 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:54:00', '11:53:00'),
	(34, 153, '2025-01-22 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:55:00', '17:01:00'),
	(35, 153, '2025-01-25 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:57:00', '12:17:00'),
	(36, 153, '2025-01-25 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:53:00', '16:33:00'),
	(37, 153, '2025-01-26 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:53:00', '12:12:00'),
	(38, 153, '2025-01-26 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:04:00', '16:52:00'),
	(39, 153, '2025-01-27 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', '12:05:00'),
	(40, 153, '2025-01-27 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:58:00', '16:53:00'),
	(41, 153, '2025-01-28 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:57:00', '11:59:00'),
	(42, 153, '2025-01-28 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:58:00', '16:44:00'),
	(43, 153, '2025-01-29 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:52:00', '11:54:00'),
	(44, 153, '2025-01-29 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:52:00', '19:27:00'),
	(45, 2, '2024-12-31 23:00:00', 'morning', '1', NULL, 4, '08:00:00', '12:00:00', NULL, NULL),
	(46, 2, '2024-12-31 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(47, 2, '2025-01-01 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', NULL),
	(48, 2, '2025-01-01 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(49, 2, '2025-01-04 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:08:00', NULL),
	(50, 2, '2025-01-04 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(51, 2, '2025-01-05 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', NULL),
	(52, 2, '2025-01-05 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(53, 2, '2025-01-06 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:02:00', NULL),
	(54, 2, '2025-01-06 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(55, 2, '2025-01-07 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:58:00', NULL),
	(56, 2, '2025-01-07 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(57, 2, '2025-01-08 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:04:00', NULL),
	(58, 2, '2025-01-08 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(59, 2, '2025-01-11 23:00:00', 'morning', '1', NULL, 4, '08:00:00', '12:00:00', NULL, NULL),
	(60, 2, '2025-01-11 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(61, 2, '2025-01-12 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:11:00', NULL),
	(62, 2, '2025-01-12 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(63, 2, '2025-01-13 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:07:00', NULL),
	(64, 2, '2025-01-13 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(65, 2, '2025-01-14 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:11:00', '12:09:00'),
	(66, 2, '2025-01-14 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:51:00', NULL),
	(67, 2, '2025-01-15 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:08:00', NULL),
	(68, 2, '2025-01-15 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(69, 2, '2025-01-18 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:04:00', NULL),
	(70, 2, '2025-01-18 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(71, 2, '2025-01-19 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:00:00', NULL),
	(72, 2, '2025-01-19 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(73, 2, '2025-01-20 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:04:00', NULL),
	(74, 2, '2025-01-20 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(75, 2, '2025-01-21 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:07:00', '11:46:00'),
	(76, 2, '2025-01-21 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:01:00', '16:30:00'),
	(77, 2, '2025-01-22 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', '12:02:00'),
	(78, 2, '2025-01-22 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:00:00', '16:30:00'),
	(79, 2, '2025-01-25 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:06:00', '11:53:00'),
	(80, 2, '2025-01-25 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:01:00', '16:30:00'),
	(81, 2, '2025-01-26 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:08:00', '11:45:00'),
	(82, 2, '2025-01-26 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:01:00', '16:30:00'),
	(83, 2, '2025-01-27 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', NULL),
	(84, 2, '2025-01-27 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(85, 2, '2025-01-28 23:00:00', 'morning', '1', NULL, 4, '08:00:00', '12:00:00', NULL, NULL),
	(86, 2, '2025-01-28 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(87, 2, '2025-01-29 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:58:00', '11:46:00'),
	(88, 2, '2025-01-29 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:00:00', '16:30:00'),
	(89, 17, '2024-12-31 23:00:00', 'morning', '1', NULL, 4, '08:00:00', '12:00:00', NULL, NULL),
	(90, 17, '2024-12-31 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(91, 17, '2025-01-01 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:06:00', '12:05:00'),
	(92, 17, '2025-01-01 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:59:00', '16:30:00'),
	(93, 17, '2025-01-04 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:06:00', '11:53:00'),
	(94, 17, '2025-01-04 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:08:00', '16:34:00'),
	(95, 17, '2025-01-05 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:04:00', '11:55:00'),
	(96, 17, '2025-01-05 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:53:00', '16:34:00'),
	(97, 17, '2025-01-06 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:00:00', '12:02:00'),
	(98, 17, '2025-01-06 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:58:00', '16:33:00'),
	(99, 17, '2025-01-07 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:56:00', '11:57:00'),
	(100, 17, '2025-01-07 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:11:00', '16:33:00'),
	(101, 17, '2025-01-08 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:03:00', '12:18:00'),
	(102, 17, '2025-01-08 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:56:00', '16:30:00'),
	(103, 17, '2025-01-11 23:00:00', 'morning', '1', NULL, 4, '08:00:00', '12:00:00', NULL, NULL),
	(104, 17, '2025-01-11 23:00:00', 'evening', '1', NULL, 3, '13:00:00', '16:30:00', NULL, NULL),
	(105, 17, '2025-01-12 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', '11:59:00'),
	(106, 17, '2025-01-12 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:02:00', '16:33:00'),
	(107, 17, '2025-01-13 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:07:00', '12:00:00'),
	(108, 17, '2025-01-13 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:03:00', '16:31:00'),
	(109, 17, '2025-01-14 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:05:00', '11:54:00'),
	(110, 17, '2025-01-14 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:51:00', '16:32:00'),
	(111, 17, '2025-01-15 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:13:00', '11:57:00'),
	(112, 17, '2025-01-15 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:56:00', '16:31:00'),
	(113, 17, '2025-01-18 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:00:00', '11:49:00'),
	(114, 17, '2025-01-18 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:05:00', '16:36:00'),
	(115, 17, '2025-01-19 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:57:00', '12:43:00'),
	(116, 17, '2025-01-19 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:59:00', '16:30:00'),
	(117, 17, '2025-01-20 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:07:00', '11:51:00'),
	(118, 17, '2025-01-20 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:58:00', '16:31:00'),
	(119, 17, '2025-01-21 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:57:00', '11:52:00'),
	(120, 17, '2025-01-21 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:10:00', '16:40:00'),
	(121, 17, '2025-01-22 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', '11:54:00'),
	(122, 17, '2025-01-22 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:51:00', '16:30:00'),
	(123, 17, '2025-01-25 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:00:00', '11:57:00'),
	(124, 17, '2025-01-25 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:05:00', '16:30:00'),
	(125, 17, '2025-01-26 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:00:00', '11:51:00'),
	(126, 17, '2025-01-26 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:07:00', '16:43:00'),
	(127, 17, '2025-01-27 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:05:00', '12:12:00'),
	(128, 17, '2025-01-27 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:13:00', '16:31:00'),
	(129, 17, '2025-01-28 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '08:03:00', '12:00:00'),
	(130, 17, '2025-01-28 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '13:07:00', '16:31:00'),
	(131, 17, '2025-01-29 23:00:00', 'morning', '0', NULL, 0, '08:00:00', '12:00:00', '07:59:00', '11:53:00'),
	(132, 17, '2025-01-29 23:00:00', 'evening', '0', NULL, 0, '13:00:00', '16:30:00', '12:58:00', '16:30:00');

-- Dumping structure for table rayane.appointment_files
CREATE TABLE IF NOT EXISTS `appointment_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `appointment_files_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.appointment_files: ~23 rows (approximately)
DELETE FROM `appointment_files`;
INSERT INTO `appointment_files` (`id`, `admin_id`, `file_name`, `original_name`, `file_path`, `created_at`) VALUES
	(4, 1, 'pointage_2025-05-26_21-09-40.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-05-26 21:09:41'),
	(5, 1, 'pointage_2025-05-27_00-11-05.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-05-27 00:11:06'),
	(6, 1, 'pointage_2025-05-27_00-12-43.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-05-27 00:12:44'),
	(7, 1, 'pointage_2025-05-28_22-56-42.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 22:56:57'),
	(8, 1, 'pointage_2025-05-28_22-58-48.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 22:59:03'),
	(9, 1, 'pointage_2025-05-28_23-00-43.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 23:00:59'),
	(10, 1, 'pointage_2025-05-28_23-07-31.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 23:07:45'),
	(11, 1, 'pointage_2025-05-28_23-09-25.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 23:09:43'),
	(12, 1, 'pointage_2025-05-31_18-59-59.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-31 19:00:13'),
	(13, 1, 'pointage_2025-05-31_19-03-40.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-05-31 19:03:41'),
	(14, 1, 'pointage_2025-06-01_13-08-36.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:08:41'),
	(15, 1, 'pointage_2025-06-01_13-13-06.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:13:09'),
	(16, 1, 'pointage_2025-06-01_13-16-32.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:16:35'),
	(17, 1, 'pointage_2025-06-01_13-21-23.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:21:25'),
	(18, 1, 'pointage_2025-06-01_13-26-07.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:26:09'),
	(19, 1, 'pointage_2025-06-01_13-45-56.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:45:58'),
	(20, 1, 'pointage_2025-06-01_13-48-23.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:48:26'),
	(21, 1, 'pointage_2025-06-01_13-56-01.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:56:03'),
	(22, 1, 'pointage_2025-06-01_13-58-07.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 13:58:09'),
	(23, 1, 'pointage_2025-06-01_14-00-53.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 14:00:56'),
	(24, 1, 'pointage_2025-06-01_14-06-42.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 14:06:44'),
	(25, 1, 'pointage_2025-06-01_14-09-06.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 14:09:08'),
	(26, 1, 'pointage_2025-06-01_14-10-30.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-01 14:10:32'),
	(27, 1, 'pointage_2025-06-03_08-00-08.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:00:10'),
	(28, 1, 'pointage_2025-06-03_08-02-16.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:02:18'),
	(29, 1, 'pointage_2025-06-03_08-03-52.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:03:54'),
	(30, 1, 'pointage_2025-06-03_08-13-54.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:13:56'),
	(31, 1, 'pointage_2025-06-03_08-22-28.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:22:30'),
	(32, 1, 'pointage_2025-06-03_08-30-01.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:30:03'),
	(33, 1, 'pointage_2025-06-03_08-30-44.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:30:47'),
	(34, 1, 'pointage_2025-06-03_08-31-56.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:31:58'),
	(35, 1, 'pointage_2025-06-03_08-34-15.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:34:17'),
	(36, 1, 'pointage_2025-06-03_08-34-47.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:34:49'),
	(37, 1, 'pointage_2025-06-03_08-36-57.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:36:58'),
	(38, 1, 'pointage_2025-06-03_08-38-24.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:38:26'),
	(39, 1, 'pointage_2025-06-03_08-40-05.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:40:07'),
	(40, 1, 'pointage_2025-06-03_08-44-40.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:44:42'),
	(41, 1, 'pointage_2025-06-03_08-45-51.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:45:53'),
	(42, 1, 'pointage_2025-06-03_08-47-20.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:47:22'),
	(43, 1, 'pointage_2025-06-03_08-51-48.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:51:49'),
	(44, 1, 'pointage_2025-06-03_08-52-38.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:52:39'),
	(45, 1, 'pointage_2025-06-03_08-54-14.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:54:15'),
	(46, 1, 'pointage_2025-06-03_08-54-58.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:55:00'),
	(47, 1, 'pointage_2025-06-03_08-58-34.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 08:58:36'),
	(48, 1, 'pointage_2025-06-03_09-02-06.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-06-03 09:02:08');

-- Dumping structure for table rayane.compte_rendus
CREATE TABLE IF NOT EXISTS `compte_rendus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `demand_id` int NOT NULL DEFAULT '0',
  `info` tinytext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.compte_rendus: ~0 rows (approximately)
DELETE FROM `compte_rendus`;

-- Dumping structure for table rayane.demands
CREATE TABLE IF NOT EXISTS `demands` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `duree` int unsigned NOT NULL DEFAULT '30',
  `description` tinytext COLLATE utf8mb4_general_ci,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `info` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `date_depose` timestamp NULL DEFAULT NULL,
  `status` enum('accepted','rejected','waiting') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_demands_employees` (`employee_id`),
  CONSTRAINT `FK_demands_employees` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.demands: ~4 rows (approximately)
DELETE FROM `demands`;
INSERT INTO `demands` (`id`, `employee_id`, `type`, `duree`, `description`, `date_debut`, `date_fin`, `info`, `date_depose`, `status`) VALUES
	(1, 1, 'conge_annual', 15, 'Dicta ad omnis quisq', '2025-05-26', '2025-06-26', '{"type":"text","content":"Eum quas explicabo "}', '2025-05-26 21:17:19', 'accepted'),
	(2, 1, 'conge_annual', 5, 'Quae et dignissimos ', '2025-01-01', '2025-01-05', '{"type":"text","content":"Deleniti autem lorem"}', '2025-05-26 22:49:21', 'accepted'),
	(3, 2, 'mission', 4, NULL, '2025-05-26', '2025-05-27', '{"type":"keys","content":{"destination":"Laborum tempor culpa","leave date":"2025-05-26","leave hour":"05:15","come date":"2025-05-27","come hour":"12:31","motif":"Excepteur autem debi"}}', '2025-05-26 23:07:39', 'accepted'),
	(7, 2, 'conge_annual', 30, 'Deserunt impedit ve', '2025-05-27', '2025-06-27', '{"type":"text","content":"Rerum sit ex mollit "}', '2025-05-27 07:33:22', 'waiting'),
	(8, 2, 'conge_annual', 30, 'Deserunt impedit ve', '2025-05-27', '2025-06-27', '{"type":"text","content":"Rerum sit ex mollit "}', '2025-05-27 07:34:03', 'waiting'),
	(9, 2, 'conge_rc', 4, 'sdadaf', '2025-05-05', '2025-05-09', '{"type": "text", "content": "dsqdqdqsdqsd}', '2025-05-28 23:06:46', 'accepted');

-- Dumping structure for table rayane.demand_lifecycle
CREATE TABLE IF NOT EXISTS `demand_lifecycle` (
  `demand_id` int NOT NULL DEFAULT '1',
  `decision` enum('accepted','rejected','waiting') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'waiting',
  `superior_id` int NOT NULL DEFAULT '0',
  `took_at` timestamp NULL DEFAULT NULL,
  KEY `FK__demands` (`demand_id`),
  KEY `FK_demand_lifecycle_employees` (`superior_id`),
  CONSTRAINT `FK_demand_lifecycle_employees` FOREIGN KEY (`superior_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.demand_lifecycle: ~6 rows (approximately)
DELETE FROM `demand_lifecycle`;
INSERT INTO `demand_lifecycle` (`demand_id`, `decision`, `superior_id`, `took_at`) VALUES
	(2, 'accepted', 1, '2025-05-27 07:28:23'),
	(3, 'waiting', 1, '2025-05-26 23:07:39'),
	(4, 'waiting', 1, '2025-05-27 07:29:09'),
	(5, 'waiting', 1, '2025-05-27 07:30:54'),
	(7, 'waiting', 1, '2025-05-27 07:33:22'),
	(8, 'waiting', 1, '2025-05-27 07:34:03');

-- Dumping structure for table rayane.departements
CREATE TABLE IF NOT EXISTS `departements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.departements: ~4 rows (approximately)
DELETE FROM `departements`;
INSERT INTO `departements` (`id`, `nom`, `code`) VALUES
	(1, 'Direction Opérationnelle', 'direction_op'),
	(2, 'Sous-Direction Technique', 'sous_direction_tech'),
	(3, 'Sous-Direction Commerciale', 'sous_direction_com'),
	(4, 'Sous-Direction Fonctions', 'sous_direction_fonctions');

-- Dumping structure for table rayane.departements_services
CREATE TABLE IF NOT EXISTS `departements_services` (
  `service_id` int DEFAULT NULL,
  `departement_id` int DEFAULT NULL,
  KEY `departement_id` (`departement_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `departements_services_ibfk_1` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`),
  CONSTRAINT `departements_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.departements_services: ~0 rows (approximately)
DELETE FROM `departements_services`;

-- Dumping structure for table rayane.email_jobs
CREATE TABLE IF NOT EXISTS `email_jobs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','sent','failed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sent_at` timestamp NULL DEFAULT NULL,
  `error` text COLLATE utf8mb4_general_ci,
  `attachment_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `email_jobs_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.email_jobs: ~4 rows (approximately)
DELETE FROM `email_jobs`;
INSERT INTO `email_jobs` (`id`, `employee_id`, `subject`, `content`, `status`, `created_at`, `sent_at`, `error`, `attachment_path`) VALUES
	(1, 1, 'cxwcxwc', 'wxcwxcwcx', 'sent', '2025-05-28 00:17:30', '2025-05-28 00:18:36', NULL, NULL),
	(2, 2, 'cxwcxwc', 'wxcwxcwcx', 'sent', '2025-05-28 00:17:30', '2025-05-28 00:18:37', NULL, NULL),
	(3, 1, 'xccvxcvcx', 'vcxvcxvxc', 'sent', '2025-05-28 00:28:39', '2025-05-28 00:29:25', NULL, 'C:\\Users\\Karim Aouaouda\\Desktop\\projects\\telecom\\actions/../storage/email_attachments/1748392119_carbon (8).png'),
	(4, 2, 'xccvxcvcx', 'vcxvcxvxc', 'sent', '2025-05-28 00:28:39', '2025-05-28 00:29:45', NULL, 'C:\\Users\\Karim Aouaouda\\Desktop\\projects\\telecom\\actions/../storage/email_attachments/1748392119_carbon (8).png');

-- Dumping structure for table rayane.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `substitute_id` int DEFAULT NULL,
  `superior_id` int DEFAULT NULL,
  `base_payment` int unsigned DEFAULT NULL,
  `matricule` int DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email_professionnel` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `departement_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `compte_valid` enum('1','0','waiting') COLLATE utf8mb4_general_ci DEFAULT 'waiting',
  `profile_photo` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birth_day` date DEFAULT NULL,
  `birth_place` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `etat_civil` enum('celibataire','marie','divorce','veuf','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_professionnel` (`email_professionnel`),
  UNIQUE KEY `matricule` (`matricule`),
  KEY `departement_id` (`departement_id`),
  KEY `service_id` (`service_id`),
  KEY `role_id` (`role_id`),
  KEY `FK_employees_employees` (`superior_id`),
  KEY `FK_employees_employees_2` (`substitute_id`) USING BTREE,
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`),
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `FK_employees_employees` FOREIGN KEY (`superior_id`) REFERENCES `employees` (`id`),
  CONSTRAINT `FK_employees_employees_2` FOREIGN KEY (`substitute_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.employees: ~4 rows (approximately)
DELETE FROM `employees`;
INSERT INTO `employees` (`id`, `substitute_id`, `superior_id`, `base_payment`, `matricule`, `prenom`, `nom`, `email_professionnel`, `phone`, `start_date`, `password`, `departement_id`, `service_id`, `role_id`, `compte_valid`, `profile_photo`, `birth_day`, `birth_place`, `etat_civil`) VALUES
	(1, 2, NULL, NULL, 153, 'Rayan', 'Bougueffroune', 'rayan@gmail.com', '+213655766709', '2025-05-06', 'password123', 1, 2, 5, '1', NULL, '2003-02-23', 'Guelma', 'celibataire'),
	(2, NULL, 1, NULL, 17, 'Maiores provident q', 'Perspiciatis conseq', 'karimkimakimo@gmail.com', '0655766709', NULL, 'password123', 4, 11, 3, '1', NULL, '2003-02-23', 'Guelma', 'celibataire'),
	(3, NULL, 1, 50000, 2, 'Soundous', 'Raiout', 'soundous@gmail.com', '0655766709', '2025-05-27', 'password123', 3, 12, 6, '1', NULL, '2003-02-23', 'Guelma', 'celibataire'),
	(4, NULL, 1, NULL, NULL, 'demo', 'user', 'demo@gmai.com', '+213565587965', '2025-05-31', 'password123', 4, 5, 1, '1', NULL, NULL, NULL, NULL);

-- Dumping structure for table rayane.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `read_state` int NOT NULL DEFAULT '0',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'notification title',
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'notification url',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `FK__employees` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.notifications: ~2 rows (approximately)
DELETE FROM `notifications`;
INSERT INTO `notifications` (`id`, `employee_id`, `read_state`, `title`, `description`, `url`) VALUES
	(1, 2, 0, 'creation status', 'la decision de votre demands de conge a ete deposer', 'http://localhost:8000/dashboard/demands/list.php'),
	(2, 1, 0, 'creation demand', 'il y a une nouvelle demande de creation de conge', 'http://localhost:8000/dashboard/demands/consulte.php');

-- Dumping structure for table rayane.planifications
CREATE TABLE IF NOT EXISTS `planifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `accepted_from_employee` int DEFAULT NULL,
  `conge_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `note` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `destination` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `motif` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__employees_plans` (`employee_id`),
  KEY `FK_planifications_employees` (`accepted_from_employee`),
  CONSTRAINT `FK__employees_plans` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_planifications_employees` FOREIGN KEY (`accepted_from_employee`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.planifications: ~1 rows (approximately)
DELETE FROM `planifications`;
INSERT INTO `planifications` (`id`, `employee_id`, `accepted_from_employee`, `conge_type`, `note`, `file`, `destination`, `motif`, `contact`, `start_date`, `end_date`) VALUES
	(1, 1, NULL, 'conge_annual', 'dafaefe', NULL, 'dazfeaf', 'dafaefe', '0655766709', '2025-06-25', '2025-06-26');

-- Dumping structure for table rayane.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.roles: ~6 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `nom`) VALUES
	(1, 'Employé'),
	(2, 'Chef de Service'),
	(3, 'Chef de Département'),
	(4, 'Sous-Directeur'),
	(5, 'Directeur'),
	(6, 'GRH');

-- Dumping structure for table rayane.services
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.services: ~14 rows (approximately)
DELETE FROM `services`;
INSERT INTO `services` (`id`, `nom`) VALUES
	(1, 'Service Sûreté'),
	(2, 'Chargé de la communication'),
	(3, 'Écoles Régionales Télécommunications'),
	(4, 'Établissements Communaux des Systèmes d’Information'),
	(5, 'Département Planification et Suivi'),
	(6, 'Département Réseau d’Accès'),
	(7, 'Département Réseau de Transport'),
	(8, 'Département Vente Grand Public'),
	(9, 'Département Corporate'),
	(10, 'Département Support Commercial'),
	(11, 'Département Achats et Logistique'),
	(12, 'Département Finance et Comptabilité'),
	(13, 'Département RH'),
	(14, 'Département Patrimoine et Moyens'),
	(15, 'Service Juridique", "Service Support SI');

-- Dumping structure for table rayane.support
CREATE TABLE IF NOT EXISTS `support` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `date_depose` timestamp NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_support_employees` (`employee_id`),
  CONSTRAINT `FK_support_employees` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.support: ~0 rows (approximately)
DELETE FROM `support`;

-- Dumping structure for table rayane.work_days
CREATE TABLE IF NOT EXISTS `work_days` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `benefited` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_work_days_employees` (`employee_id`),
  CONSTRAINT `FK_work_days_employees` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.work_days: ~0 rows (approximately)
DELETE FROM `work_days`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
