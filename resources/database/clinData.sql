-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2022 at 11:03 AM
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
-- Database: `clinData`
--

-- --------------------------------------------------------

--
-- Table structure for table `Biospecimens`
--

CREATE TABLE `Biospecimens` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `cellularity` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `collection` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `storage` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `bankingid` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `paired` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `imaging` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CBC`
--

CREATE TABLE `CBC` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `count` float NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ClinicalCondition`
--

CREATE TABLE `ClinicalCondition` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ecog` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `karnofsky` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CMP`
--

CREATE TABLE `CMP` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `count` float NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Comorbid`
--

CREATE TABLE `Comorbid` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Death`
--

CREATE TABLE `Death` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DiagnosisNF1`
--

CREATE TABLE `DiagnosisNF1` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `diagnosis` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL,
  `mode` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `criteria` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `severity` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `visibility` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `age` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `circumference` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Diseases`
--

CREATE TABLE `Diseases` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `histology` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `side` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `oncotree` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `clinicalsg` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `clinicalss` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `pathologicsg` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `pathologicss` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `comments` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Lab`
--

CREATE TABLE `Lab` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `height` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `weight` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `diastolic` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `systolic` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `LesionsNF1`
--

CREATE TABLE `LesionsNF1` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `evaluation` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `number` int NOT NULL,
  `location` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ManifestationsNF1`
--

CREATE TABLE `ManifestationsNF1` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `evaluation` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Medication`
--

CREATE TABLE `Medication` (
  `id` varbinary(1000) NOT NULL,
  `medication` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `start` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `stop` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `intent` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Mutation`
--

CREATE TABLE `Mutation` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `test` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Outcome`
--

CREATE TABLE `Outcome` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Patient`
--

CREATE TABLE `Patient` (
  `id` varbinary(1000) NOT NULL,
  `birth` varbinary(10000) NOT NULL,
  `gender` varbinary(10000) NOT NULL,
  `race` varbinary(10000) NOT NULL,
  `zip` varbinary(10000) NOT NULL,
  `institution` varbinary(10000) NOT NULL,
  `study` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `family` varbinary(10000) NOT NULL,
  `tracking` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedigree`
--

CREATE TABLE `pedigree` (
  `id` varbinary(1000) NOT NULL,
  `dataset` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ProcedureNf1`
--

CREATE TABLE `ProcedureNf1` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Radiation`
--

CREATE TABLE `Radiation` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `site` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `intent` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Surgery`
--

CREATE TABLE `Surgery` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `site` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `intent` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracking`
--

CREATE TABLE `tracking` (
  `trackingid` varchar(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `roles` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Tumor`
--

CREATE TABLE `Tumor` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `test` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `result` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Variant`
--

CREATE TABLE `Variant` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `test` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `gene` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `cdna` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `protein` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `variantid` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `varianthgvs` varchar(3000) COLLATE utf8mb4_general_ci NOT NULL,
  `interpretation` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `source` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Biospecimens`
--
ALTER TABLE `Biospecimens`
  ADD PRIMARY KEY (`id`,`date`,`type`,`storage`,`bankingid`);

--
-- Indexes for table `CBC`
--
ALTER TABLE `CBC`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `ClinicalCondition`
--
ALTER TABLE `ClinicalCondition`
  ADD PRIMARY KEY (`id`,`date`,`ecog`,`karnofsky`);

--
-- Indexes for table `CMP`
--
ALTER TABLE `CMP`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `Comorbid`
--
ALTER TABLE `Comorbid`
  ADD PRIMARY KEY (`id`,`date`,`code`);

--
-- Indexes for table `Death`
--
ALTER TABLE `Death`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Indexes for table `Mutation`
--
ALTER TABLE `Mutation`
  ADD PRIMARY KEY (`id`,`date`,`test`);

--
-- Indexes for table `Outcome`
--
ALTER TABLE `Outcome`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Indexes for table `Patient`
--
ALTER TABLE `Patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedigree`
--
ALTER TABLE `pedigree`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ProcedureNf1`
--
ALTER TABLE `ProcedureNf1`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `Radiation`
--
ALTER TABLE `Radiation`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `Surgery`
--
ALTER TABLE `Surgery`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `tracking`
--
ALTER TABLE `tracking`
  ADD PRIMARY KEY (`trackingid`);

--
-- Indexes for table `Variant`
--
ALTER TABLE `Variant`
  ADD PRIMARY KEY (`id`,`date`,`test`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
