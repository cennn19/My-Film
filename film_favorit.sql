-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Membuat Database `film` jika belum ada
--
CREATE DATABASE IF NOT EXISTS `film`;
USE `film`;

-- --------------------------------------------------------

--
-- Table structure for table `film_favorit`
--

CREATE TABLE IF NOT EXISTS `film_favorit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `rating` varchar(10) DEFAULT NULL,
  `tanggal_simpan` timestamp NOT NULL DEFAULT current_timestamp(),
  `komentar` text DEFAULT NULL,
  `link_trailer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `film_favorit`
--

INSERT INTO `film_favorit` (`id`, `tmdb_id`, `judul`, `gambar`, `rating`, `tanggal_simpan`, `komentar`, `link_trailer`) VALUES
(2, 840464, 'Greenland 2: Migration', '/1mF4othta76CEXcL1YFInYudQ7K.jpg', '6.521', '2026-02-03 05:36:11', 'Tester', 'https://youtu.be/hiD3zk0ZRFg?si=mYKr0LXKPjZLTD9Y');

--
-- Penyesuaian AUTO_INCREMENT
--
ALTER TABLE `film_favorit` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

