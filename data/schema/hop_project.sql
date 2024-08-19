-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 29, 2024 at 05:34 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hop_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(320) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_verified_at` timestamp NOT NULL,
  `status_id` int NOT NULL,
  `list_id` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `first_name`, `last_name`, `email`, `email_verified_at`, `status_id`, `list_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Oldies', 'Mann', 'oldiesman@simplemachines.org', '2024-07-21 05:12:49', 0, 1, '2024-07-21 05:12:49', '2024-07-21 05:12:49'),
(2, 1, 'Kindred', '', 'kindred@simplemachines.org', '2024-07-21 05:12:49', 0, 1, '2024-07-21 05:12:49', '2024-07-21 05:12:49'),
(3, 1, 'Jeff', 'Lewis', 'jlewis@gmail.com', '2024-07-21 06:11:03', 0, 2, '2024-07-21 06:11:03', '2024-07-21 06:11:03'),
(4, 1, 'Mathew', 'O\'phinney', 'mwop@mezzio.com', '2024-07-21 06:12:03', 0, 3, '2024-07-21 06:12:03', '2024-07-21 06:12:03'),
(5, 1, 'Linus', 'Torvalds', 'linus@linux.io', '0000-00-00 00:00:00', 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 1, 'Linus', 'Torvalds', 'linus@linux.io', '0000-00-00 00:00:00', 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 1, 'Tracy', 'Debug', 'tracy@tracy.io', '0000-00-00 00:00:00', 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 1, 'Joey', 'Smith', 'jsmith@webinertia.net', '0000-00-00 00:00:00', 0, 38, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
CREATE TABLE IF NOT EXISTS `lists` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `list_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lists`
--

INSERT INTO `lists` (`id`, `list_name`) VALUES
(1, 'SimpleMachines'),
(2, 'Webinertia'),
(3, 'Mezzio'),
(35, 'SpaceX'),
(36, 'Tesla'),
(38, 'GE');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `status_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `team_id` bigint NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(320) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_verified_at` timestamp NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `team_id`, `first_name`, `last_name`, `email`, `role_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 0, 'Joey', 'Smith', 'jsmith@webinertia.net', 'Administrator', '2024-07-18 22:05:11', '$2y$10$buYOVRO7oURp1Ej3/mNBK.9c.Yo.LH49Iba2Q1l7F3Lmr6dRzAACq', '', '2024-07-18 22:05:11', '2024-07-18 22:05:11'),
(2, 0, 'Jeff', 'Lewis', 'jlewis.android@gmail.com', 'Administrator', '2024-07-19 07:15:42', '$2y$10$Yy99D0eVs8JXSwEeY9Bd3u94aZXgv2.2kZFWjXkyQHEexDwMm4m6W', '', '2024-07-19 07:15:42', '2024-07-19 07:15:42');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
