-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 12:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
  `adminster_id` int(11) NOT NULL,
  `takes_id` int(11) NOT NULL,
  `staff_code` text NOT NULL,
  `date_time` int(11) NOT NULL,
  `dose_given` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `audit_id` int(10) UNSIGNED NOT NULL,
  `staff_id` int(11) NOT NULL,
  `act` text NOT NULL,
  `date_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(10) UNSIGNED NOT NULL,
  `brand_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `brand_name`) VALUES
(1, 'Tesco'),
(2, 'co-op'),
(3, 'Advil'),
(4, 'Amoxil'),
(5, 'Glucophage'),
(6, 'Norvasc'),
(7, 'Deltasone'),
(8, 'Prinivil'),
(9, 'Zithromax'),
(10, 'Cipro'),
(11, 'Prilosec'),
(12, 'Zyrtec'),
(13, 'ventolin'),
(14, 'EpiPen'),
(15, 'Strides Pharma'),
(16, 'Evohaler'),
(17, 'galpharm'),
(18, 'PLIVA Pharma'),
(19, 'Norvasc'),
(20, 'Zyrtec'),
(21, 'Zithromax'),
(22, 'Cipro'),
(23, 'Prilosec'),
(24, 'galpharm'),
(25, 'Valium'),
(26, 'Zocor'),
(27, 'Microzide'),
(28, 'Lasix'),
(29, 'Bayer'),
(30, 'Ativan'),
(31, 'Xyzal'),
(32, 'Neurontin'),
(33, 'Vibramycin'),
(34, 'Deltasone'),
(35, 'Plavix'),
(36, 'Panadol');

-- --------------------------------------------------------

--
-- Table structure for table `med`
--

CREATE TABLE `med` (
  `med_id` int(10) UNSIGNED NOT NULL,
  `med_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `med`
--

INSERT INTO `med` (`med_id`, `med_name`) VALUES
(1, 'Paracetamol'),
(2, 'Hayfever releif'),
(3, 'Ibuprofen'),
(4, 'Amoxicillin'),
(5, 'Metformin'),
(6, 'Amlodipine'),
(7, 'Prednisone'),
(8, 'Lisinopril'),
(9, 'Azithromycin'),
(10, 'Ciprofloxacin'),
(11, 'Omeprazole'),
(12, 'Cetirizine'),
(13, 'salbutamol'),
(14, 'Adrenaline Auto-Injector'),
(15, 'Hydrocortisone Cream'),
(16, 'Seretide inhaler'),
(17, 'lanzoprazole'),
(18, 'naproxen'),
(19, 'Azithromycin'),
(20, 'Seretide inhaler'),
(21, 'Diazepam'),
(22, 'Simvastatin'),
(23, 'Hydrochlorothiazide'),
(24, 'Furosemide'),
(25, 'Aspirin'),
(26, 'Lorazepam'),
(27, 'Levocetirizine'),
(28, 'Gabapentin'),
(29, 'Doxycycline'),
(30, 'Clopidogrel');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(10) UNSIGNED NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `group` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(10) UNSIGNED NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `year` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `year`) VALUES
(1, 'Jake', 'Peralta', '13'),
(2, 'Amy', 'Santiago', '11'),
(3, 'Charles', 'Boyle', '10'),
(4, 'Rosa', 'Diaz', '12'),
(5, 'Gina ', 'Linetti', '13'),
(6, 'Raymond', 'Holt', '13'),
(7, 'Lucy', 'Chen', '12'),
(8, 'Tim ', 'Bradford', '11'),
(9, 'John ', 'Nolan', '13'),
(10, 'Jackson', 'West', '12'),
(11, 'Bailey', 'Nune', '10'),
(12, 'Wesley', 'Evens', '10'),
(13, 'Angela', 'Lopez', '11'),
(14, 'Rosalind', 'Dyer', '12'),
(15, 'Nyla', 'Harper', '13'),
(16, 'Aaron', 'Thorsen', '11'),
(17, 'Tailia', 'Bishop', '11'),
(18, 'Zoe ', 'Anderson', '12'),
(19, 'Tamara', 'Collins', '10'),
(20, 'Celina', 'Juarez', '10'),
(21, 'Elija', 'Stone', '12'),
(22, 'Monica', 'Stevens', '13'),
(23, 'Oscar', 'Hutchinson', '12'),
(24, 'Wade', 'Grey', '12'),
(25, 'Emmet', 'Lang', '13'),
(26, 'James', 'Murray', '13'),
(27, 'Meredith', 'Grey', '11'),
(28, 'Derek', 'Shepard', '12'),
(29, 'Alex', 'Kerev', '10'),
(30, 'Mark', 'Sloan', '11'),
(31, 'Owen ', 'Hunt', '13'),
(32, 'Cristina ', 'Yang', '11'),
(33, 'Miranda', 'Bailey', '12');

-- --------------------------------------------------------

--
-- Table structure for table `takes`
--

CREATE TABLE `takes` (
  `takes_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(11) NOT NULL,
  `med_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `exp_date` int(11) NOT NULL,
  `current_dose` int(11) NOT NULL,
  `min_dose` int(11) NOT NULL,
  `max_dose` int(11) NOT NULL,
  `strength` text NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `takes`
--

INSERT INTO `takes` (`takes_id`, `student_id`, `med_id`, `brand_id`, `exp_date`, `current_dose`, `min_dose`, `max_dose`, `strength`, `active`) VALUES
(103, 1, 1, 1, 1735862400, 2, 4, 8, '100mg', 1),
(104, 2, 2, 2, 1707004800, 4, 1, 30, '50mg', 1),
(105, 2, 3, 2, 1746316800, 32, 6, 32, '200mg', 1),
(106, 3, 3, 4, 1760659200, 6, 6, 49, '200 mg', 1),
(107, 3, 4, 5, 1747008000, 30, 6, 31, '500 mg', 1),
(108, 3, 5, 5, 1745366400, 26, 2, 50, '850 mg', 1),
(109, 3, 6, 6, 1738713600, 9, 5, 46, '5 mg', 1),
(110, 4, 7, 7, 1743292800, 15, 2, 31, '10 mg', 1),
(111, 4, 8, 8, 1743206400, 3, 9, 14, '20 mg', 1),
(112, 4, 9, 9, 1742515200, 27, 5, 50, '250 mg', 1),
(113, 4, 10, 10, 1755907200, 27, 1, 40, '500 mg', 1),
(114, 5, 11, 11, 1749772800, 26, 2, 42, '40 mg', 1),
(115, 5, 12, 12, 1742860800, 3, 10, 39, '10 mg', 1),
(116, 6, 13, 13, 1693526400, 21, 3, 60, '200mg', 1),
(117, 7, 14, 14, 1751328000, 1, 2, 1, '0.3mg', 1),
(118, 7, 14, 14, 1751328000, 1, 8, 1, '0.3mg', 1),
(119, 8, 15, 15, 1864598400, 5, 9, 7, '30g', 1),
(120, 8, 20, 16, 1769904000, 40, 10, 60, '125mg', 1),
(121, 9, 1, 17, 1759276800, 4, 6, 8, '500mg', 1),
(122, 9, 18, 18, 1780272000, 6, 2, 7, '30mg', 1),
(123, 10, 18, 19, 1783296000, 4, 7, 7, '500mg', 1),
(124, 11, 14, 14, 1678838400, 1, 2, 1, '0.3mg', 1),
(125, 11, 14, 14, 1654560000, 1, 6, 1, '0.3mg', 1),
(126, 12, 15, 2, 1726963200, 2, 3, 30, '50mg', 1),
(127, 13, 12, 20, 1739404800, 2, 2, 40, '10 mg', 1),
(128, 14, 13, 13, 1668729600, 2, 2, 60, '200mg', 1),
(129, 15, 19, 21, 1722643200, 27, 1, 15, '250 mg', 1),
(130, 15, 10, 22, 1652054400, 27, 1, 24, '500 mg', 1),
(131, 16, 11, 23, 1690329600, 26, 4, 42, '40 mg', 1),
(132, 17, 14, 14, 1760140800, 1, 6, 1, '0.3mg', 1),
(133, 17, 14, 14, 1775001600, 1, 8, 1, '0.3mg', 1),
(134, 18, 2, 2, 1716249600, 4, 3, 30, '50mg', 1),
(135, 19, 3, 2, 1693353600, 32, 4, 32, '200mg', 1),
(136, 20, 1, 1, 1799884800, 2, 3, 8, '100mg', 1),
(137, 21, 16, 16, 1751414400, 40, 9, 60, '125mg', 1),
(138, 22, 1, 18, 1709942400, 4, 6, 8, '500mg', 1),
(139, 23, 21, 26, 1797465600, 25, 1, 40, '5 mg', 1),
(140, 24, 22, 26, 1643932800, 40, 3, 60, '20 mg', 1),
(141, 25, 23, 27, 1747958400, 30, 9, 50, '25 mg', 1),
(142, 26, 24, 28, 1696550400, 10, 6, 30, '40 mg', 1),
(143, 27, 25, 29, 1821312000, 60, 9, 100, '81 mg', 1),
(144, 27, 26, 30, 1775952000, 15, 3, 20, '1 mg', 1),
(145, 28, 27, 31, 1669507200, 35, 3, 50, '5 mg', 1),
(146, 28, 28, 32, 1707091200, 12, 2, 30, '300 mg', 1),
(147, 29, 29, 33, 1750032000, 20, 7, 30, '100 mg', 1),
(148, 29, 12, 20, 1696723200, 40, 10, 50, '10 mg', 1),
(149, 30, 7, 34, 1800835200, 10, 4, 20, '5 mg', 1),
(150, 31, 30, 35, 1710374400, 60, 10, 90, '75 mg', 1),
(151, 31, 1, 36, 1755734400, 20, 2, 30, '500 mg', 1);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administer`
--
ALTER TABLE `administer`
  MODIFY `adminster_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `audit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `med`
--
ALTER TABLE `med`
  MODIFY `med_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `takes`
--
ALTER TABLE `takes`
  MODIFY `takes_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
