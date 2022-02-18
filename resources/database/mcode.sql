-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2022 at 06:52 PM
-- Server version: 8.0.21
-- PHP Version: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mcode`
--
CREATE DATABASE IF NOT EXISTS `mcode` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mcode`;

-- --------------------------------------------------------

--
-- Table structure for table `cancer_disease_status`
--

CREATE TABLE `cancer_disease_status` (
  `id` varchar(15) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `condition_primary`
--

CREATE TABLE `condition_primary` (
  `code` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `condition_secondary`
--

CREATE TABLE `condition_secondary` (
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cormorbid`
--

CREATE TABLE `cormorbid` (
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ecog`
--

CREATE TABLE `ecog` (
  `id` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gene`
--

CREATE TABLE `gene` (
  `id` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `histology`
--

CREATE TABLE `histology` (
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `karnofsky`
--

CREATE TABLE `karnofsky` (
  `id` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(40) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `norm` ###### Save the initialization vector here ######
--

CREATE TABLE `norm` (
  `riv` varbinary(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outcome`
--

CREATE TABLE `outcome` (
  `id` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `cancer_disease_status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_of_death` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `illness` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `birth` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  `race` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ethnicity` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `zip` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `cormorbid` varchar(70) COLLATE utf8mb4_general_ci NOT NULL,
  `clinical_status` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `ecog` varchar(70) COLLATE utf8mb4_general_ci NOT NULL,
  `karnofsky` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `radiation`
--

CREATE TABLE `radiation` (
  `name` varchar(300) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `radioprocedure`
--

CREATE TABLE `radioprocedure` (
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surgery`
--

CREATE TABLE `surgery` (
  `name` varchar(300) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `termination`
--

CREATE TABLE `termination` (
  `name` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_code`
--

CREATE TABLE `test_code` (
  `name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variant`
--

CREATE TABLE `variant` (
  `ind` int NOT NULL,
  `id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `hgvs` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cancer_disease_status`
--
ALTER TABLE `cancer_disease_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `condition_primary`
--
ALTER TABLE `condition_primary`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `condition_secondary`
--
ALTER TABLE `condition_secondary`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `cormorbid`
--
ALTER TABLE `cormorbid` ADD FULLTEXT KEY `description` (`description`);

--
-- Indexes for table `ecog`
--
ALTER TABLE `ecog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gene`
--
ALTER TABLE `gene`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `histology`
--
ALTER TABLE `histology`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `radiation`
--
ALTER TABLE `radiation`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `radioprocedure`
--
ALTER TABLE `radioprocedure`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `surgery`
--
ALTER TABLE `surgery`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `termination`
--
ALTER TABLE `termination`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `test_code`
--
ALTER TABLE `test_code`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `variant`
--
ALTER TABLE `variant`
  ADD PRIMARY KEY (`ind`,`id`,`hgvs`),
  ADD UNIQUE KEY `ind` (`ind`,`id`,`hgvs`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
