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

-- Dumping structure for table rayane.absenses
CREATE TABLE IF NOT EXISTS `absenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `day_part` enum('morning','evening') COLLATE utf8mb4_general_ci NOT NULL,
  `justify` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employee_matricule` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_absenses_employees_2` (`employee_matricule`),
  CONSTRAINT `FK_absenses_employees_2` FOREIGN KEY (`employee_matricule`) REFERENCES `employees` (`matricule`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.absenses: ~88 rows (approximately)
DELETE FROM `absenses`;
INSERT INTO `absenses` (`id`, `date`, `day_part`, `justify`, `employee_matricule`) VALUES
	(1, '2024-12-31 23:00:00', 'morning', 'no', 153),
	(2, '2024-12-31 23:00:00', 'evening', 'no', 153),
	(3, '2025-01-01 23:00:00', 'morning', 'no', 153),
	(4, '2025-01-01 23:00:00', 'evening', 'no', 153),
	(5, '2025-01-04 23:00:00', 'morning', 'no', 153),
	(6, '2025-01-04 23:00:00', 'evening', 'no', 153),
	(7, '2025-01-05 23:00:00', 'morning', 'no', 153),
	(8, '2025-01-05 23:00:00', 'evening', 'no', 153),
	(9, '2025-01-06 23:00:00', 'morning', 'no', 153),
	(10, '2025-01-06 23:00:00', 'evening', 'no', 153),
	(11, '2025-01-07 23:00:00', 'morning', 'no', 153),
	(12, '2025-01-07 23:00:00', 'evening', 'no', 153),
	(13, '2025-01-08 23:00:00', 'morning', 'no', 153),
	(14, '2025-01-08 23:00:00', 'evening', 'no', 153),
	(15, '2025-01-11 23:00:00', 'morning', 'no', 153),
	(16, '2025-01-11 23:00:00', 'evening', 'no', 153),
	(17, '2025-01-12 23:00:00', 'morning', 'no', 153),
	(18, '2025-01-12 23:00:00', 'evening', 'no', 153),
	(19, '2025-01-13 23:00:00', 'morning', 'no', 153),
	(20, '2025-01-13 23:00:00', 'evening', 'no', 153),
	(21, '2025-01-14 23:00:00', 'morning', 'no', 153),
	(22, '2025-01-14 23:00:00', 'evening', 'no', 153),
	(23, '2025-01-15 23:00:00', 'morning', 'no', 153),
	(24, '2025-01-15 23:00:00', 'evening', 'no', 153),
	(25, '2025-01-18 23:00:00', 'morning', 'no', 153),
	(26, '2025-01-18 23:00:00', 'evening', 'no', 153),
	(27, '2025-01-19 23:00:00', 'morning', 'no', 153),
	(28, '2025-01-19 23:00:00', 'evening', 'no', 153),
	(29, '2025-01-20 23:00:00', 'morning', 'no', 153),
	(30, '2025-01-20 23:00:00', 'evening', 'no', 153),
	(31, '2025-01-21 23:00:00', 'morning', 'no', 153),
	(32, '2025-01-21 23:00:00', 'evening', 'no', 153),
	(33, '2025-01-22 23:00:00', 'morning', 'no', 153),
	(34, '2025-01-22 23:00:00', 'evening', 'no', 153),
	(35, '2025-01-25 23:00:00', 'morning', 'no', 153),
	(36, '2025-01-25 23:00:00', 'evening', 'no', 153),
	(37, '2025-01-26 23:00:00', 'morning', 'no', 153),
	(38, '2025-01-26 23:00:00', 'evening', 'no', 153),
	(39, '2025-01-27 23:00:00', 'morning', 'no', 153),
	(40, '2025-01-27 23:00:00', 'evening', 'no', 153),
	(41, '2025-01-28 23:00:00', 'morning', 'no', 153),
	(42, '2025-01-28 23:00:00', 'evening', 'no', 153),
	(43, '2025-01-29 23:00:00', 'morning', 'no', 153),
	(44, '2025-01-29 23:00:00', 'evening', 'no', 153),
	(45, '2024-12-31 23:00:00', 'morning', 'no', 17),
	(46, '2024-12-31 23:00:00', 'evening', 'no', 17),
	(47, '2025-01-01 23:00:00', 'morning', 'no', 17),
	(48, '2025-01-01 23:00:00', 'evening', 'no', 17),
	(49, '2025-01-04 23:00:00', 'morning', 'conge_rc', 17),
	(50, '2025-01-04 23:00:00', 'evening', 'conge_rc', 17),
	(51, '2025-01-05 23:00:00', 'morning', 'conge_rc', 17),
	(52, '2025-01-05 23:00:00', 'evening', 'conge_rc', 17),
	(53, '2025-01-06 23:00:00', 'morning', 'conge_rc', 17),
	(54, '2025-01-06 23:00:00', 'evening', 'conge_rc', 17),
	(55, '2025-01-07 23:00:00', 'morning', 'conge_rc', 17),
	(56, '2025-01-07 23:00:00', 'evening', 'conge_rc', 17),
	(57, '2025-01-08 23:00:00', 'morning', 'conge_rc', 17),
	(58, '2025-01-08 23:00:00', 'evening', 'conge_rc', 17),
	(59, '2025-01-11 23:00:00', 'morning', 'conge_annual', 17),
	(60, '2025-01-11 23:00:00', 'evening', 'conge_annual', 17),
	(61, '2025-01-12 23:00:00', 'morning', 'conge_annual', 17),
	(62, '2025-01-12 23:00:00', 'evening', 'conge_annual', 17),
	(63, '2025-01-13 23:00:00', 'morning', 'conge_annual', 17),
	(64, '2025-01-13 23:00:00', 'evening', 'conge_annual', 17),
	(65, '2025-01-14 23:00:00', 'morning', 'conge_annual', 17),
	(66, '2025-01-14 23:00:00', 'evening', 'conge_annual', 17),
	(67, '2025-01-15 23:00:00', 'morning', 'conge_annual', 17),
	(68, '2025-01-15 23:00:00', 'evening', 'conge_annual', 17),
	(69, '2025-01-18 23:00:00', 'morning', 'conge_annual', 17),
	(70, '2025-01-18 23:00:00', 'evening', 'conge_annual', 17),
	(71, '2025-01-19 23:00:00', 'morning', 'conge_annual', 17),
	(72, '2025-01-19 23:00:00', 'evening', 'conge_annual', 17),
	(73, '2025-01-20 23:00:00', 'morning', 'conge_annual', 17),
	(74, '2025-01-20 23:00:00', 'evening', 'conge_annual', 17),
	(75, '2025-01-21 23:00:00', 'morning', 'conge_annual', 17),
	(76, '2025-01-21 23:00:00', 'evening', 'conge_annual', 17),
	(77, '2025-01-22 23:00:00', 'morning', 'conge_annual', 17),
	(78, '2025-01-22 23:00:00', 'evening', 'conge_annual', 17),
	(79, '2025-01-25 23:00:00', 'morning', 'conge_annual', 17),
	(80, '2025-01-25 23:00:00', 'evening', 'conge_annual', 17),
	(81, '2025-01-26 23:00:00', 'morning', 'conge_annual', 17),
	(82, '2025-01-26 23:00:00', 'evening', 'conge_annual', 17),
	(83, '2025-01-27 23:00:00', 'morning', 'conge_annual', 17),
	(84, '2025-01-27 23:00:00', 'evening', 'conge_annual', 17),
	(85, '2025-01-28 23:00:00', 'morning', 'conge_annual', 17),
	(86, '2025-01-28 23:00:00', 'evening', 'conge_annual', 17),
	(87, '2025-01-29 23:00:00', 'morning', 'conge_annual', 17),
	(88, '2025-01-29 23:00:00', 'evening', 'conge_annual', 17);

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.appointment_files: ~3 rows (approximately)
DELETE FROM `appointment_files`;
INSERT INTO `appointment_files` (`id`, `admin_id`, `file_name`, `original_name`, `file_path`, `created_at`) VALUES
	(4, 1, 'pointage_2025-05-26_21-09-40.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-05-26 21:09:41'),
	(5, 1, 'pointage_2025-05-27_00-11-05.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-05-27 00:11:06'),
	(6, 1, 'pointage_2025-05-27_00-12-43.xlsx', 'modified_état de présence mois de janvier 2025 (8).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (8).xlsx', '2025-05-27 00:12:44'),
	(7, 1, 'pointage_2025-05-28_22-56-42.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 22:56:57'),
	(8, 1, 'pointage_2025-05-28_22-58-48.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 22:59:03'),
	(9, 1, 'pointage_2025-05-28_23-00-43.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 23:00:59'),
	(10, 1, 'pointage_2025-05-28_23-07-31.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 23:07:45'),
	(11, 1, 'pointage_2025-05-28_23-09-25.xlsx', 'modified_état de présence mois de janvier 2025 (9).xlsx', 'files/modified_modified_état de présence mois de janvier 2025 (9).xlsx', '2025-05-28 23:09:43');

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

-- Dumping data for table rayane.demands: ~6 rows (approximately)
DELETE FROM `demands`;
INSERT INTO `demands` (`id`, `employee_id`, `type`, `duree`, `description`, `date_debut`, `date_fin`, `info`, `date_depose`, `status`) VALUES
	(1, 1, 'conge_annual', 15, 'Dicta ad omnis quisq', '2025-05-26', '2025-06-26', '{"type":"text","content":"Eum quas explicabo "}', '2025-05-26 21:17:19', 'accepted'),
	(2, 2, 'conge_annual', 5, 'Quae et dignissimos ', '2025-01-10', '2025-02-01', '{"type":"text","content":"Deleniti autem lorem"}', '2025-05-26 22:49:21', 'accepted'),
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
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`),
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `FK_employees_employees` FOREIGN KEY (`superior_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.employees: ~3 rows (approximately)
DELETE FROM `employees`;
INSERT INTO `employees` (`id`, `superior_id`, `base_payment`, `matricule`, `prenom`, `nom`, `email_professionnel`, `phone`, `start_date`, `password`, `departement_id`, `service_id`, `role_id`, `compte_valid`, `profile_photo`, `birth_day`, `birth_place`, `etat_civil`) VALUES
	(1, NULL, NULL, 153, 'Rayan', 'Bougueffroune', 'rayan@gmail.com', '+213655766709', '2025-05-06', 'password123', 1, 2, 5, '1', NULL, '2003-02-23', 'Guelma', 'celibataire'),
	(2, 1, NULL, 17, 'Maiores provident q', 'Perspiciatis conseq', 'karimkimakimo@gmail.com', '0655766709', NULL, 'password123', 4, 11, 3, '1', NULL, '2003-02-23', 'Guelma', 'celibataire'),
	(3, 1, 50000, 2, 'Soundous', 'Raiout', 'soundous@gmail.com', '0655766709', '2025-05-27', 'password123', 3, 12, 6, '1', NULL, '2003-02-23', 'Guelma', 'celibataire');

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
	(1, 2, 1, 'creation status', 'la decision de votre demands de conge a ete deposer', 'http://localhost:8000/dashboard/demands/list.php'),
	(2, 1, 1, 'creation demand', 'il y a une nouvelle demande de creation de conge', 'http://localhost:8000/dashboard/demands/consulte.php');

-- Dumping structure for table rayane.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rayane.roles: ~4 rows (approximately)
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
