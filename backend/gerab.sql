-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2022 at 06:34 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gerab`
--

-- --------------------------------------------------------

--
-- Table structure for table `extraction`
--

CREATE TABLE `extraction` (
  `extract_id` int(10) UNSIGNED NOT NULL,
  `pdf_id` int(10) UNSIGNED NOT NULL,
  `page` tinyint(4) NOT NULL,
  `data_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `json_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `processed` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `pdf`
--

CREATE TABLE `pdf` (
  `pdf_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `digest` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pages` tinyint(3) UNSIGNED DEFAULT NULL,
  `pages_parsed` tinyint(3) UNSIGNED DEFAULT NULL,
  `module` enum('tenaris','heng') COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded` datetime NOT NULL,
  `parsed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for table `extraction`
--
ALTER TABLE `extraction`
  ADD PRIMARY KEY (`extract_id`);

--
-- Indexes for table `pdf`
--
ALTER TABLE `pdf`
  ADD PRIMARY KEY (`pdf_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `extraction`
--
ALTER TABLE `extraction`
  MODIFY `extract_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `pdf`
--
ALTER TABLE `pdf`
  MODIFY `pdf_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
