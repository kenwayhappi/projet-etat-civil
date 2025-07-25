-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 25 juil. 2025 à 12:48
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `etat_civil`
--

-- --------------------------------------------------------

--
-- Structure de la table `acts`
--

DROP TABLE IF EXISTS `acts`;
CREATE TABLE IF NOT EXISTS `acts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('birth','marriage','death','divorce') NOT NULL,
  `details` json NOT NULL,
  `center_id` int NOT NULL,
  `created_by` int NOT NULL,
  `validated_by` int DEFAULT NULL,
  `status` enum('pending','validated','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `center_id` (`center_id`),
  KEY `created_by` (`created_by`),
  KEY `validated_by` (`validated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `acts`
--

INSERT INTO `acts` (`id`, `type`, `details`, `center_id`, `created_by`, `validated_by`, `status`, `created_at`, `updated_at`) VALUES
(23, 'death', '{\"death_date\": \"2025-07-25\", \"death_place\": \"DOUDOU\", \"deceased_name\": \"avina\"}', 3, 6, NULL, 'validated', '2025-07-25 09:34:59', '2025-07-25 10:46:49'),
(31, 'birth', '{\"birth_date\": \"2025-07-12\", \"child_name\": \"dsq\", \"birth_place\": \"dsq\", \"father_name\": \"dsq\", \"mother_name\": \"dsq\"}', 3, 6, NULL, 'validated', '2025-07-25 10:26:52', '2025-07-25 10:53:44'),
(26, 'marriage', '{\"spouse1_name\": \"maudit\", \"spouse2_name\": \"dsq\", \"marriage_date\": \"2025-07-03\", \"marriage_place\": \"dsq\"}', 3, 6, NULL, 'pending', '2025-07-25 10:09:51', '2025-07-25 10:23:36'),
(33, 'birth', '{\"birth_date\": \"2025-07-30\", \"child_name\": \"teste\", \"birth_place\": \"douala\", \"father_name\": \"dsq\", \"mother_name\": \"avina\"}', 3, 6, NULL, 'pending', '2025-07-25 11:06:44', '2025-07-25 11:44:21'),
(29, 'marriage', '{\"spouse1_name\": \"jean\", \"spouse2_name\": \"tekke,\", \"marriage_date\": \"2025-07-06\", \"marriage_place\": \"dsdsq\"}', 3, 6, NULL, 'pending', '2025-07-25 10:19:18', '2025-07-25 10:19:18');

-- --------------------------------------------------------

--
-- Structure de la table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `act_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `act_id` (`act_id`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `act_id`, `created_at`, `updated_at`) VALUES
(1, 6, 'Création d\'un acte de birth', 5, '2025-07-25 08:47:26', '2025-07-25 08:47:26'),
(2, 6, 'Création d\'un acte de birth', 6, '2025-07-25 08:47:29', '2025-07-25 08:47:29'),
(3, 6, 'Création d\'un acte de birth', 7, '2025-07-25 08:47:30', '2025-07-25 08:47:30'),
(4, 6, 'Création d\'un acte de birth', 8, '2025-07-25 08:47:30', '2025-07-25 08:47:30'),
(5, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 08:47:38', '2025-07-25 08:47:38'),
(6, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 08:47:44', '2025-07-25 08:47:44'),
(7, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 08:47:53', '2025-07-25 08:47:53'),
(8, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 08:48:53', '2025-07-25 08:48:53'),
(9, 6, 'Création d\'un acte de marriage', 9, '2025-07-25 08:50:06', '2025-07-25 08:50:06'),
(10, 6, 'Création d\'un acte de marriage', 10, '2025-07-25 08:50:07', '2025-07-25 08:50:07'),
(11, 6, 'Création d\'un acte de marriage', 11, '2025-07-25 08:50:07', '2025-07-25 08:50:07'),
(12, 6, 'Création d\'un acte de marriage', 12, '2025-07-25 08:50:07', '2025-07-25 08:50:07'),
(13, 6, 'Création d\'un acte de marriage', 13, '2025-07-25 08:50:07', '2025-07-25 08:50:07'),
(14, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 08:50:11', '2025-07-25 08:50:11'),
(15, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 08:50:15', '2025-07-25 08:50:15'),
(16, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 08:50:18', '2025-07-25 08:50:18'),
(17, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 08:50:22', '2025-07-25 08:50:22'),
(18, 6, 'Création d\'un acte de birth', 14, '2025-07-25 08:51:34', '2025-07-25 08:51:34'),
(19, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 08:57:52', '2025-07-25 08:57:52'),
(20, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 08:57:58', '2025-07-25 08:57:58'),
(21, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 08:58:01', '2025-07-25 08:58:01'),
(22, 6, 'Création d\'un acte de birth', 15, '2025-07-25 08:58:11', '2025-07-25 08:58:11'),
(23, 6, 'Création d\'un acte de birth', 16, '2025-07-25 09:03:49', '2025-07-25 09:03:49'),
(24, 6, 'Création d\'un acte de birth', 17, '2025-07-25 09:05:14', '2025-07-25 09:05:14'),
(25, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 09:08:09', '2025-07-25 09:08:09'),
(26, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 09:13:15', '2025-07-25 09:13:15'),
(27, 6, 'Création d\'un acte de birth', 18, '2025-07-25 09:14:06', '2025-07-25 09:14:06'),
(28, 6, 'Création d\'un acte de birth', 19, '2025-07-25 09:16:37', '2025-07-25 09:16:37'),
(29, 6, 'Création d\'un acte de birth', 20, '2025-07-25 09:21:29', '2025-07-25 09:21:29'),
(30, 6, 'Création d\'un acte de birth', 21, '2025-07-25 09:22:29', '2025-07-25 09:22:29'),
(31, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 09:22:36', '2025-07-25 09:22:36'),
(32, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 09:22:38', '2025-07-25 09:22:38'),
(33, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 09:22:40', '2025-07-25 09:22:40'),
(34, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 09:22:43', '2025-07-25 09:22:43'),
(35, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 09:23:07', '2025-07-25 09:23:07'),
(36, 6, 'Création d\'un acte de marriage', 22, '2025-07-25 09:33:58', '2025-07-25 09:33:58'),
(37, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 09:34:10', '2025-07-25 09:34:10'),
(38, 6, 'Création d\'un acte de death', 23, '2025-07-25 09:34:59', '2025-07-25 09:34:59'),
(39, 6, 'Création d\'un acte de divorce', 24, '2025-07-25 09:37:16', '2025-07-25 09:37:16'),
(40, 6, 'Suppression d\'un acte de divorce', NULL, '2025-07-25 09:37:24', '2025-07-25 09:37:24'),
(41, 6, 'Modification des paramètres du compte', NULL, '2025-07-25 10:03:25', '2025-07-25 10:03:25'),
(42, 6, 'Modification des paramètres du compte', NULL, '2025-07-25 10:03:31', '2025-07-25 10:03:31'),
(43, 6, 'Création d\'un acte de marriage', 25, '2025-07-25 10:05:23', '2025-07-25 10:05:23'),
(44, 6, 'Création d\'un acte de marriage', 26, '2025-07-25 10:09:51', '2025-07-25 10:09:51'),
(45, 6, 'Modification d\'un acte de marriage', 25, '2025-07-25 10:15:38', '2025-07-25 10:15:38'),
(46, 6, 'Création d\'un acte de marriage', 27, '2025-07-25 10:15:50', '2025-07-25 10:15:50'),
(47, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 10:15:56', '2025-07-25 10:15:56'),
(48, 6, 'Modification d\'un acte de marriage', 25, '2025-07-25 10:16:07', '2025-07-25 10:16:07'),
(49, 6, 'Modification d\'un acte de marriage', 25, '2025-07-25 10:17:49', '2025-07-25 10:17:49'),
(50, 6, 'Modification d\'un acte de marriage', 25, '2025-07-25 10:18:00', '2025-07-25 10:18:00'),
(51, 6, 'Modification d\'un acte de marriage', 26, '2025-07-25 10:18:09', '2025-07-25 10:18:09'),
(52, 6, 'Modification d\'un acte de marriage', 26, '2025-07-25 10:18:18', '2025-07-25 10:18:18'),
(53, 6, 'Création d\'un acte de marriage', 28, '2025-07-25 10:18:30', '2025-07-25 10:18:30'),
(54, 6, 'Création d\'un acte de marriage', 29, '2025-07-25 10:19:18', '2025-07-25 10:19:18'),
(55, 6, 'Création d\'un acte de marriage', 30, '2025-07-25 10:22:06', '2025-07-25 10:22:06'),
(56, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 10:23:24', '2025-07-25 10:23:24'),
(57, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 10:23:27', '2025-07-25 10:23:27'),
(58, 6, 'Suppression d\'un acte de marriage', NULL, '2025-07-25 10:23:29', '2025-07-25 10:23:29'),
(59, 6, 'Modification d\'un acte de marriage', 26, '2025-07-25 10:23:36', '2025-07-25 10:23:36'),
(60, 6, 'Modification d\'un acte de birth', 16, '2025-07-25 10:26:41', '2025-07-25 10:26:41'),
(61, 6, 'Création d\'un acte de birth', 31, '2025-07-25 10:26:52', '2025-07-25 10:26:52'),
(62, 6, 'Suppression d\'un acte de birth', NULL, '2025-07-25 10:26:57', '2025-07-25 10:26:57'),
(63, 6, 'Création d\'un acte de death', 32, '2025-07-25 10:28:11', '2025-07-25 10:28:11'),
(64, 6, 'Suppression d\'un acte de death', NULL, '2025-07-25 10:28:17', '2025-07-25 10:28:17'),
(65, 6, 'Modification d\'un acte de death', 23, '2025-07-25 10:29:05', '2025-07-25 10:29:05'),
(66, 6, 'Création d\'un acte de birth', 33, '2025-07-25 11:06:44', '2025-07-25 11:06:44'),
(67, 6, 'Modification d\'un acte de birth', 33, '2025-07-25 11:41:00', '2025-07-25 11:41:00'),
(68, 6, 'Modification d\'un acte de birth', 33, '2025-07-25 11:44:12', '2025-07-25 11:44:12'),
(69, 6, 'Modification d\'un acte de birth', 33, '2025-07-25 11:44:21', '2025-07-25 11:44:21');

-- --------------------------------------------------------

--
-- Structure de la table `centers`
--

DROP TABLE IF EXISTS `centers`;
CREATE TABLE IF NOT EXISTS `centers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `department_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `centers`
--

INSERT INTO `centers` (`id`, `name`, `department_id`, `created_at`, `updated_at`) VALUES
(3, 'pauline', 2, '2025-07-23 14:42:56', '2025-07-23 14:47:41');

-- --------------------------------------------------------

--
-- Structure de la table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `region_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `departments`
--

INSERT INTO `departments` (`id`, `name`, `region_id`, `created_at`, `updated_at`) VALUES
(1, 'Djerem', 1, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(2, 'Faro-et-Déo', 1, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(3, 'Mayo-Banyo', 1, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(4, 'Haute-Sanaga', 2, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(5, 'Lekié', 2, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(6, 'Mbam-et-Inoubou', 2, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(7, 'Haut-Nyong', 3, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(8, 'Kadey', 3, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(9, 'Lom-et-Djerem', 3, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(10, 'Diamaré', 4, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(11, 'Mayo-Danay', 4, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(12, 'Mayo-Kani', 4, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(13, 'Wouri', 5, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(14, 'Sanaga-Maritime', 5, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(15, 'Nkam', 5, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(16, 'Bénoué', 6, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(17, 'Faro', 6, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(18, 'Mayo-Louti', 6, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(19, 'Boyo', 7, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(20, 'Donga-Mantung', 7, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(21, 'Menchum', 7, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(22, 'Bamboutos', 8, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(23, 'Haut-Nkam', 8, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(24, 'Mifi', 8, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(25, 'Dja-et-Lobo', 9, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(26, 'Mvila', 9, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(27, 'Océan', 9, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(28, 'Fako', 10, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(29, 'Koupé-Manengouba', 10, '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(30, 'Lebialem', 10, '2025-07-23 14:51:51', '2025-07-23 14:51:51');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `regions`
--

INSERT INTO `regions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Adamaoua', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(2, 'Centre', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(3, 'Est', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(4, 'Extrême-Nord', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(5, 'Littoral', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(6, 'Nord', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(7, 'Nord-Ouest', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(8, 'Ouest', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(9, 'Sud', '2025-07-23 14:51:51', '2025-07-23 14:51:51'),
(10, 'Sud-Ouest', '2025-07-23 14:51:51', '2025-07-23 14:51:51');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','supervisor','agent') NOT NULL,
  `center_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `center_id` (`center_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `center_id`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur National', 'admin@gmail.com', '$2y$12$ezoPQzGQ0i0J2GKBf9nnNuWO9yIM8J6ouVAPjtHSxoYTxj0WjfqQS', 'admin', NULL, '2025-07-23 09:12:30', '2025-07-23 12:46:21'),
(2, 'avina', 'avina@gmail.com', '$2y$12$kFzKToDOSdqxO9KCeHb0auHXVXVjvKmz9FM6b6jrHmp.fH7wKcQsy', 'supervisor', 3, '2025-07-23 14:57:06', '2025-07-23 14:57:06'),
(3, 'teste', 'teste@gmail.com', '$2y$12$u8GuEycK8ZVIaCMR0/9p1eF/PeVrZao5hkXtNUtvmB5ADn9xX1Uxq', 'agent', 3, '2025-07-23 14:58:47', '2025-07-23 14:58:47'),
(4, 'papa', 'happi@gmail.com', '$2y$12$LBK5IMuk2J7ucFvP/ZI3AOvYcCGbvLicK9oyr462gjQiDIiiMRxz.', 'supervisor', 3, '2025-07-25 07:42:23', '2025-07-25 09:49:31'),
(6, 'oui', 'oui@gmail.com', '$2y$12$yVc2NTdWlUyqwwrX.Eamku8Nfh16nOTCjsvmb8NltmvfbMFIYDx4S', 'agent', 3, '2025-07-25 08:25:57', '2025-07-25 10:03:31');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
