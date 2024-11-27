-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 20, 2024 at 11:58 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`audit_id`, `staff_id`, `act`, `date_time`) VALUES
(1, 2, 'Succ_Log', 1732051053),
(2, 2, 'Succ_Log', 1732051188),
(3, 2, 'Succ_Log', 1732051724),
(4, 2, 'Succ_Log', 1732051816),
(5, 2, 'Succ_Log', 1732051903),
(6, 2, '40', 1732058658),
(7, 2, '40, users were added', 1732058693),
(8, 2, '40 users were added inserted', 1732058713),
(9, 1, 'Succ_Log', 1732092690),
(10, 1, '40 users were added inserted', 1732092952),
(11, 1, 'Fail_Log', 1732094099);

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
(1, 'TESCO'),
(2, 'CO-OP'),
(3, 'ADVIL'),
(4, 'AMOXIL'),
(5, 'GLUCOPHAGE'),
(6, 'NORVASC'),
(7, 'DELTASONE'),
(8, 'PRINIVIL'),
(9, 'ZITHROMAX'),
(10, 'CIPRO'),
(11, 'PRILOSEC'),
(12, 'ZYRTEC'),
(13, 'VENTOLIN'),
(14, 'EPIPEN'),
(15, 'STRIDES PHARMA'),
(16, 'EVOHALER'),
(17, 'GALPHARM'),
(18, 'PLIVA PHARMA'),
(19, 'NORVASC'),
(20, 'ZYRTEC'),
(21, 'ZITHROMAX'),
(22, 'CIPRO'),
(23, 'PRILOSEC'),
(24, 'GALPHARM'),
(25, 'VALIUM'),
(26, 'ZOCOR'),
(27, 'MICROZIDE'),
(28, 'LASIX'),
(29, 'BAYER'),
(30, 'ATIVAN'),
(31, 'XYZAL'),
(32, 'NEURONTIN'),
(33, 'VIBRAMYCIN'),
(34, 'DELTASONE'),
(35, 'PLAVIX'),
(36, 'PANADOL');


-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `log_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `notes` text NOT NULL,
  `date_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'PARACETAMOL'),
(2, 'HAYFEVER RELIEF'),
(3, 'IBUPROFEN'),
(4, 'AMOXICILLIN'),
(5, 'METFORMIN'),
(6, 'AMLODIPINE'),
(7, 'PREDNISONE'),
(8, 'LISINOPRIL'),
(9, 'AZITHROMYCIN'),
(10, 'CIPROFLOXACIN'),
(11, 'OMEPRAZOLE'),
(12, 'CETIRIZINE'),
(13, 'SALBUTAMOL'),
(14, 'ADRENALINE AUTO-INJECTOR'),
(15, 'HYDROCORTISONE CREAM'),
(16, 'SERETIDE INHALER'),
(17, 'LANZOPRAZOLE'),
(18, 'NAPROXEN'),
(19, 'AZITHROMYCIN'),
(20, 'SERETIDE INHALER'),
(21, 'DIAZEPAM'),
(22, 'SIMVASTATIN'),
(23, 'HYDROCHLOROTHIAZIDE'),
(24, 'FUROSEMIDE'),
(25, 'ASPIRIN'),
(26, 'LORAZEPAM'),
(27, 'LEVOCETIRIZINE'),
(28, 'GABAPENTIN'),
(29, 'DOXYCYCLINE'),
(30, 'CLOPIDOGREL');


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

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `first_name`, `last_name`, `email`, `password`, `group`) VALUES
(1, 'Failed', 'Events', 'failed.events@utcleeds.co.uk', 'failed.events', 'sys');

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
(1, 'JAKE', 'PERALTA', '13'),
(2, 'AMY', 'SANTIAGO', '11'),
(3, 'CHARLES', 'BOYLE', '10'),
(4, 'ROSA', 'DIAZ', '12'),
(5, 'GINA', 'LINETTI', '13'),
(6, 'RAYMOND', 'HOLT', '13'),
(7, 'LUCY', 'CHEN', '12'),
(8, 'TIM', 'BRADFORD', '11'),
(9, 'JOHN', 'NOLAN', '13'),
(10, 'JACKSON', 'WEST', '12'),
(11, 'BAILEY', 'NUNE', '10'),
(12, 'WESLEY', 'EVENS', '10'),
(13, 'ANGELA', 'LOPEZ', '11'),
(14, 'ROSALIND', 'DYER', '12'),
(15, 'NYLA', 'HARPER', '13'),
(16, 'AARON', 'THORSEN', '11'),
(17, 'TAILIA', 'BISHOP', '11'),
(18, 'ZOE', 'ANDERSON', '12'),
(19, 'TAMARA', 'COLLINS', '10'),
(20, 'CELINA', 'JUAREZ', '10'),
(21, 'ELIJA', 'STONE', '12'),
(22, 'MONICA', 'STEVENS', '13'),
(23, 'OSCAR', 'HUTCHINSON', '12'),
(24, 'WADE', 'GREY', '12'),
(25, 'EMMET', 'LANG', '13'),
(26, 'JAMES', 'MURRAY', '13'),
(27, 'MEREDITH', 'GREY', '11'),
(28, 'DEREK', 'SHEPARD', '12'),
(29, 'ALEX', 'KEREV', '10'),
(30, 'MARK', 'SLOAN', '11'),
(31, 'OWEN', 'HUNT', '13'),
(32, 'CRISTINA', 'YANG', '11'),
(33, 'MIRANDA', 'BAILEY', '12');

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
  `notes` text NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `takes`
--

INSERT INTO `takes` (`takes_id`, `student_id`, `med_id`, `brand_id`, `exp_date`, `current_dose`, `min_dose`, `max_dose`, `strength`, `notes`, `archived`) VALUES
(103, 1, 1, 1, 1735862400, 2, 4, 8, '100mg', '', 0),
(104, 2, 2, 2, 1707004800, 4, 1, 30, '50mg', '', 1),
(105, 2, 3, 2, 1733702400, 32, 6, 32, '200mg', '', 0),
(106, 3, 3, 4, 1760659200, 6, 6, 49, '200 mg', '', 0),
(107, 3, 4, 5, 1747008000, 30, 6, 31, '500 mg', '', 0),
(108, 3, 5, 5, 1745366400, 26, 2, 50, '850 mg', '', 0),
(109, 3, 6, 6, 1738713600, 9, 5, 46, '5 mg', '', 0),
(110, 4, 7, 7, 1743292800, 15, 2, 31, '10 mg', '', 0),
(111, 4, 8, 8, 1743206400, 3, 9, 14, '20 mg', '', 0),
(112, 4, 9, 9, 1742515200, 27, 5, 50, '250 mg', '', 0),
(113, 4, 10, 10, 1755907200, 27, 1, 40, '500 mg', '', 0),
(114, 5, 11, 11, 1749772800, 26, 2, 42, '40 mg', '', 0),
(115, 5, 12, 12, 1742860800, 3, 10, 39, '10 mg', '', 0),
(116, 6, 13, 13, 1693526400, 21, 3, 60, '200mg', '', 1),
(117, 7, 14, 14, 1751328000, 1, 2, 1, '0.3mg', '', 0),
(118, 7, 14, 14, 1751328000, 1, 8, 1, '0.3mg', '', 0),
(119, 8, 15, 15, 1734134400, 5, 9, 7, '30g', '', 0),
(120, 8, 20, 16, 1769904000, 40, 10, 60, '125mg', '', 0),
(121, 9, 1, 17, 1759276800, 4, 6, 8, '500mg', '', 0),
(122, 9, 18, 18, 1732838400, 6, 2, 7, '30mg', '', 1),
(123, 10, 18, 19, 1783296000, 4, 7, 7, '500mg', '', 0),
(124, 11, 14, 14, 1678838400, 1, 2, 1, '0.3mg', '', 1),
(125, 11, 14, 14, 1654560000, 1, 6, 1, '0.3mg', '', 1),
(126, 12, 15, 2, 1726963200, 2, 3, 30, '50mg', '', 1),
(127, 13, 12, 20, 1739404800, 2, 2, 40, '10 mg', '', 0),
(128, 14, 13, 13, 1668729600, 2, 2, 60, '200mg', '', 1),
(129, 15, 19, 21, 1722643200, 27, 1, 15, '250 mg', '', 1),
(130, 15, 10, 22, 1652054400, 27, 1, 24, '500 mg', '', 1),
(131, 16, 11, 23, 1690329600, 26, 4, 42, '40 mg', '', 1),
(132, 17, 14, 14, 1760140800, 1, 6, 1, '0.3mg', '', 0),
(133, 17, 14, 14, 1775001600, 1, 8, 1, '0.3mg', '', 0),
(134, 18, 2, 2, 1716249600, 4, 3, 30, '50mg', '', 1),
(135, 19, 3, 2, 1693353600, 32, 4, 32, '200mg', '', 1),
(136, 20, 1, 1, 1799884800, 2, 3, 8, '100mg', '', 0),
(137, 21, 16, 16, 1751414400, 40, 9, 60, '125mg', '', 0),
(138, 22, 1, 18, 1709942400, 4, 6, 8, '500mg', '', 1),
(139, 23, 21, 26, 1797465600, 25, 1, 40, '5 mg', '', 0),
(140, 24, 22, 26, 1643932800, 40, 3, 60, '20 mg', '', 1),
(141, 25, 23, 27, 1747958400, 30, 9, 50, '25 mg', '', 0),
(142, 26, 24, 28, 1696550400, 10, 6, 30, '40 mg', '', 1),
(143, 27, 25, 29, 1821312000, 60, 9, 100, '81 mg', '', 0),
(144, 27, 26, 30, 1775952000, 15, 3, 20, '1 mg', '', 0),
(145, 28, 27, 31, 1669507200, 35, 3, 50, '5 mg', 'uasihdajsdkjasdasdjasojasd', 0),
(146, 28, 28, 32, 1707091200, 12, 2, 30, '300 mg', '', 0),
(147, 29, 29, 33, 1732406400, 20, 7, 30, '100 mg', 'asdfg', 0),
(148, 29, 12, 20, 1696723200, 40, 10, 50, '10 mg', '', 0),
(149, 30, 7, 34, 1800835200, 10, 4, 20, '5 mg', '', 0),
(150, 31, 30, 35, 1710374400, 60, 10, 90, '75 mg', '', 0),
(151, 31, 1, 36, 1755734400, 20, 2, 30, '500 mg', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `whole_log`
--

CREATE TABLE `whole_log` (
  `whole_log_id` int(11) NOT NULL,
  `whole_school_id` int(11) NOT NULL,
  `notes` text NOT NULL,
  `date_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whole_school`
--

CREATE TABLE `whole_school` (
  `whole_school_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `exp_date` int(11) NOT NULL,
  `amount_left` int(11) NOT NULL,
  `notes` text NOT NULL
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
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`log_id`);

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
  MODIFY `adminster_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `audit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `med`
--
ALTER TABLE `med`
  MODIFY `med_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=794;

--
-- AUTO_INCREMENT for table `takes`
--
ALTER TABLE `takes`
  MODIFY `takes_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `whole_log`
--
ALTER TABLE `whole_log`
  MODIFY `whole_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whole_school`
--
ALTER TABLE `whole_school`
  MODIFY `whole_school_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

DELIMITER $$

-- Trigger for `students` table
CREATE TRIGGER `before_insert_students`
BEFORE INSERT ON `students`
FOR EACH ROW
BEGIN
    SET NEW.first_name = UPPER(NEW.first_name);
    SET NEW.last_name = UPPER(NEW.last_name);
END$$

CREATE TRIGGER `before_update_students`
BEFORE UPDATE ON `students`
FOR EACH ROW
BEGIN
    SET NEW.first_name = UPPER(NEW.first_name);
    SET NEW.last_name = UPPER(NEW.last_name);
END$$

-- Trigger for `brand` table
CREATE TRIGGER `before_insert_brand`
BEFORE INSERT ON `brand`
FOR EACH ROW
BEGIN
    SET NEW.brand_name = UPPER(NEW.brand_name);
END$$

CREATE TRIGGER `before_update_brand`
BEFORE UPDATE ON `brand`
FOR EACH ROW
BEGIN
    SET NEW.brand_name = UPPER(NEW.brand_name);
END$$

-- Trigger for `med` table
CREATE TRIGGER `before_insert_med`
BEFORE INSERT ON `med`
FOR EACH ROW
BEGIN
    SET NEW.med_name = UPPER(NEW.med_name);
END$$

CREATE TRIGGER `before_update_med`
BEFORE UPDATE ON `med`
FOR EACH ROW
BEGIN
    SET NEW.med_name = UPPER(NEW.med_name);
END$$

DELIMITER ;
