-- Table structure for table `Biospecimens`
--

CREATE TABLE `Biospecimens_tracking` (
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
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CBC`
--

CREATE TABLE `CBC_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `count` float NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ClinicalCondition`
--

CREATE TABLE `ClinicalCondition_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ecog` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `karnofsky` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CMP`
--

CREATE TABLE `CMP_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `count` float NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Comorbid`
--

CREATE TABLE `Comorbid_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Death`
--

CREATE TABLE `Death_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DiagnosisNF1`
--

CREATE TABLE `DiagnosisNF1_tracking` (
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
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Diseases`
--

CREATE TABLE `Diseases_tracking` (
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
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Lab`
--

CREATE TABLE `Lab_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `height` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `weight` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `diastolic` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `systolic` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `LesionsNF1`
--

CREATE TABLE `LesionsNF1_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `evaluation` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `number` int NOT NULL,
  `location` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ManifestationsNF1`
--

CREATE TABLE `ManifestationsNF1_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `evaluation` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Medication`
--

CREATE TABLE `Medication_tracking` (
  `id` varbinary(1000) NOT NULL,
  `medication` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `start` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `stop` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `intent` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Mutation`
--

CREATE TABLE `Mutation_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `test` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Outcome`
--

CREATE TABLE `Outcome_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Patient`
--

CREATE TABLE `Patient_tracking` (
  `id` varbinary(1000) NOT NULL,
  `birth` varbinary(10000) NOT NULL,
  `gender` varbinary(10000) NOT NULL,
  `race` varbinary(10000) NOT NULL,
  `zip` varbinary(10000) NOT NULL,
  `institution` varbinary(10000) NOT NULL,
  `study` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `family` varbinary(10000) NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedigree`
--

CREATE TABLE `pedigree_tracking` (
  `id` varbinary(1000) NOT NULL,
  `dataset` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ProcedureNf1`
--

CREATE TABLE `ProcedureNf1_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Radiation`
--

CREATE TABLE `Radiation_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `site` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `intent` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Surgery`
--

CREATE TABLE `Surgery_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `site` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `intent` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracking`
--

CREATE TABLE IF NOT EXISTS `tracking` (
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

CREATE TABLE `Tumor_tracking` (
  `id` varbinary(1000) NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `test` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `result` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Variant`
--

CREATE TABLE `Variant_tracking` (
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
  `event` varbinary(20) NOT NULL,
  `tracking` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Biospecimens`
--
ALTER TABLE `Biospecimens_tracking`
  ADD PRIMARY KEY (`id`,`date`,`type`,`storage`,`bankingid`);

--
-- Indexes for table `CBC`
--
ALTER TABLE `CBC_tracking`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `ClinicalCondition`
--
ALTER TABLE `ClinicalCondition_tracking`
  ADD PRIMARY KEY (`id`,`date`,`ecog`,`karnofsky`);

--
-- Indexes for table `CMP`
--
ALTER TABLE `CMP_tracking`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `Comorbid`
--
ALTER TABLE `Comorbid_tracking`
  ADD PRIMARY KEY (`id`,`date`,`code`);

--
-- Indexes for table `Death`
--
ALTER TABLE `Death_tracking`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Indexes for table `Mutation`
--
ALTER TABLE `Mutation_tracking`
  ADD PRIMARY KEY (`id`,`date`,`test`);

--
-- Indexes for table `Outcome`
--
ALTER TABLE `Outcome_tracking`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Indexes for table `Patient`
--
ALTER TABLE `Patient_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedigree`
--
ALTER TABLE `pedigree_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ProcedureNf1`
--
ALTER TABLE `ProcedureNf1_tracking`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `Radiation`
--
ALTER TABLE `Radiation_tracking`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `Surgery`
--
ALTER TABLE `Surgery_tracking`
  ADD PRIMARY KEY (`id`,`date`,`type`);

--
-- Indexes for table `tracking`
--
ALTER TABLE `tracking_tracking`
  ADD PRIMARY KEY (`trackingid`);

--
-- Indexes for table `Variant`
--
ALTER TABLE `Variant_tracking`
  ADD PRIMARY KEY (`id`,`date`,`test`);