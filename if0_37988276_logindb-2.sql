-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: sql310.infinityfree.com
-- Üretim Zamanı: 24 Oca 2025, 18:09:43
-- Sunucu sürümü: 10.6.19-MariaDB
-- PHP Sürümü: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `if0_37988276_logindb`
--
CREATE DATABASE IF NOT EXISTS `if0_37988276_logindb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `if0_37988276_logindb`;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `blogger`
--

DROP TABLE IF EXISTS `blogger`;
CREATE TABLE IF NOT EXISTS `blogger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `blog_post`
--

DROP TABLE IF EXISTS `blog_post`;
CREATE TABLE IF NOT EXISTS `blog_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` varchar(50) NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_blog_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `blog_post`
--

INSERT INTO `blog_post` (`id`, `user_id`, `title`, `content`, `created_at`, `updated_at`, `status`, `category_id`) VALUES(14, 3, 'Savaş uçağı', 'türkiyede ilk 5.nesil savaş uçağı üretildi adı Kaan', '2024-12-27 15:00:41', NULL, 'draft', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES(1, 'Technology', '2024-12-27 10:56:24', '2024-12-27 10:56:24');
INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES(2, 'Health', '2024-12-27 10:56:24', '2024-12-27 10:56:24');
INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES(3, 'Education', '2024-12-27 10:56:24', '2024-12-27 10:56:24');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_2` (`email`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `name`, `created_at`) VALUES(1, 'rümeysa', 'rumeysa.sakaoglu@gmail.com', '$2y$10$o6kjgwkCaUg1u/0W9BriJuBeIgRdukIMds.YQ.H5EER09LVHK4y8K', '', '2024-12-21 21:15:20');
INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `name`, `created_at`) VALUES(2, 'sümeyye', 'rumeysa.sakaoglu@ogr.dpu.edu.tr', '$2y$10$jA7Dp9pDwDGPVPqYplVSk.08WDOdUKPGop6j2tbplrK15XltKE8Ni', '', '2024-12-21 21:15:20');
INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `name`, `created_at`) VALUES(3, 'rümeysa', 'rumeysasakaoglu02@gmail.com', '$2y$10$8GMr70U7dGF4NCWWaoKmjO14seKqq18Ec73d7zqkopBOO8URTnnc6', '', '2024-12-21 21:15:20');
INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `name`, `created_at`) VALUES(4, 'rümeysa ', 'rumeysasakaogluu02@gmail.com', '$2y$10$A8b5/5jU2AFLhDWNLTH8Iu5ANHVtRKBjRnWngU6uVqMQDGwPsga7q', '', '2024-12-21 21:15:20');

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `blogger`
--
ALTER TABLE `blogger`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `blog_post`
--
ALTER TABLE `blog_post`
  ADD CONSTRAINT `blog_post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_blog_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
