-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2025 at 07:08 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `utcl-mts`
--
CREATE DATABASE IF NOT EXISTS `utcl-mts` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `utcl-mts`;

-- --------------------------------------------------------

--
-- Table structure for table `administer`
--

CREATE TABLE `administer` (
  `adminster_id` int NOT NULL,
  `takes_id` int NOT NULL,
  `staff_code` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_time` int NOT NULL,
  `dose_given` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `audit_id` int UNSIGNED NOT NULL,
  `staff_id` int NOT NULL,
  `act` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_time` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int UNSIGNED NOT NULL,
  `brand_name` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `brand`
--
DELIMITER $$
CREATE TRIGGER `before_insert_brand` BEFORE INSERT ON `brand` FOR EACH ROW BEGIN
    SET NEW.brand_name = UPPER(NEW.brand_name);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_brand` BEFORE UPDATE ON `brand` FOR EACH ROW BEGIN
    SET NEW.brand_name = UPPER(NEW.brand_name);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `med`
--

CREATE TABLE `med` (
  `med_id` int UNSIGNED NOT NULL,
  `med_name` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `med`
--
DELIMITER $$
CREATE TRIGGER `before_insert_med` BEFORE INSERT ON `med` FOR EACH ROW BEGIN
    SET NEW.med_name = UPPER(NEW.med_name);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_med` BEFORE UPDATE ON `med` FOR EACH ROW BEGIN
    SET NEW.med_name = UPPER(NEW.med_name);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `note_id` int NOT NULL,
  `takes_id` int NOT NULL,
  `staff_code` text COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int UNSIGNED NOT NULL,
  `first_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` text COLLATE utf8mb4_general_ci NOT NULL,
  `staff_code` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `group` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int UNSIGNED NOT NULL,
  `first_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `year` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `students`
--
DELIMITER $$
CREATE TRIGGER `before_insert_students` BEFORE INSERT ON `students` FOR EACH ROW BEGIN
    SET NEW.first_name = UPPER(NEW.first_name);
    SET NEW.last_name = UPPER(NEW.last_name);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_students` BEFORE UPDATE ON `students` FOR EACH ROW BEGIN
    SET NEW.first_name = UPPER(NEW.first_name);
    SET NEW.last_name = UPPER(NEW.last_name);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `takes`
--

CREATE TABLE `takes` (
  `takes_id` int UNSIGNED NOT NULL,
  `student_id` int NOT NULL,
  `med_id` int NOT NULL,
  `brand_id` int NOT NULL,
  `exp_date` int NOT NULL,
  `current_dose` int NOT NULL,
  `min_dose` int NOT NULL,
  `max_dose` int NOT NULL,
  `strength` text COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `archived` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whole_log`
--

CREATE TABLE `whole_log` (
  `whole_log_id` int NOT NULL,
  `whole_school_id` int NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_time` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whole_school`
--

CREATE TABLE `whole_school` (
  `whole_school_id` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `exp_date` int NOT NULL,
  `amount_left` int NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci NOT NULL,
  `archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administer`
--
ALTER TABLE `administer`
  ADD PRIMARY KEY (`adminster_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `med`
--
ALTER TABLE `med`
  ADD PRIMARY KEY (`med_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `takes`
--
ALTER TABLE `takes`
  ADD PRIMARY KEY (`takes_id`);

--
-- Indexes for table `whole_log`
--
ALTER TABLE `whole_log`
  ADD PRIMARY KEY (`whole_log_id`);

--
-- Indexes for table `whole_school`
--
ALTER TABLE `whole_school`
  ADD PRIMARY KEY (`whole_school_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administer`
--
ALTER TABLE `administer`
  MODIFY `adminster_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `audit_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `med`
--
ALTER TABLE `med`
  MODIFY `med_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `takes`
--
ALTER TABLE `takes`
  MODIFY `takes_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whole_log`
--
ALTER TABLE `whole_log`
  MODIFY `whole_log_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whole_school`
--
ALTER TABLE `whole_school`
  MODIFY `whole_school_id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
