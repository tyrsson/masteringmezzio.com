-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 30, 2024 at 01:37 PM
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
-- Database: `mastering_mezzio`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `authorId` bigint NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateUpdated` datetime DEFAULT CURRENT_TIMESTAMP,
  `datePublished` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article_reactions`
--

DROP TABLE IF EXISTS `article_reactions`;
CREATE TABLE IF NOT EXISTS `article_reactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `articleId` bigint UNSIGNED NOT NULL,
  `userId` bigint UNSIGNED NOT NULL,
  `reactionId` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ownerId` int UNSIGNED NOT NULL COMMENT 'FK to user table id',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `parentId` int UNSIGNED DEFAULT NULL,
  `typeInterface` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `options` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(320) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `roleId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'User',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `dateVerified` datetime DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rememberToken` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateUpdated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verificationToken` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `roleId`, `verified`, `dateVerified`, `password`, `rememberToken`, `dateCreated`, `dateUpdated`, `verificationToken`) VALUES
(1, 'Joey', 'Smith', 'jsmith@webinertia.net', 'Administrator', 1, '2024-07-18 17:05:11', '$2y$10$buYOVRO7oURp1Ej3/mNBK.9c.Yo.LH49Iba2Q1l7F3Lmr6dRzAACq', '', '2024-07-18 17:05:11', '2024-07-18 17:05:11', NULL),
(2, 'Jeff', 'Lewis', 'jlewis.android@gmail.com', 'Administrator', 1, '2024-07-19 02:15:42', '$2y$10$Yy99D0eVs8JXSwEeY9Bd3u94aZXgv2.2kZFWjXkyQHEexDwMm4m6W', '', '2024-07-19 02:15:42', '2024-07-19 02:15:42', NULL),
(49, 'Joey', 'Smith', 'test@webinertia.net', 'User', 1, '2024-09-25 04:25:19', '$2y$10$xSr4YjDzGeG75HKohem6su2LWumDAiVXMCpg7nN1P6x83O/A3aJfW', '', '2024-09-24 23:24:55', '2024-09-25 04:25:19', NULL),
(50, 'Joey', 'Smith', 'devel1@webinertia.net', 'User', 1, '2024-09-25 04:35:05', '$2y$10$6EZD/cSx8NwFB2tgNeKAE.lhKlABHpnXtcGzkD0ImhKIptSHVG68u', '', '2024-09-24 23:33:56', '2024-09-25 04:35:05', NULL),
(51, 'Joey', 'Smith', 'devel2@webinertia.net', 'User', 0, NULL, '$2y$10$qKzACwWXgeAkYAdjHLRN0OkzWmB6nb8I5OadY1IAJDHDRHP9kCxce', '', '2024-09-24 23:37:56', '2024-09-24 23:37:56', '01922778-b8f6-725a-a7d1-b04fd4371e6b'),
(52, 'Joey', 'Smith', 'devel3@webinertia.net', 'User', 1, '2024-09-25 07:26:54', '$2y$10$SKBLl/JE5ZT5.H4plQpnQegmZqvBbVcUmKEwfsjqG7w9eBnrklRqa', '', '2024-09-25 02:05:16', '2024-09-25 07:26:54', '019231ef-73bc-7186-b58b-cef425b4065f');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
