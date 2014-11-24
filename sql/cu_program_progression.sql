-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2014 at 07:22 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sysc4504`
--

-- --------------------------------------------------------

--
-- Table structure for table `cu_program_progression`
--

CREATE TABLE IF NOT EXISTS `cu_program_progression` (
  `degree_name` enum('SE','BEE','CE','CSE') NOT NULL,
  `course_year` enum('1','2','3','4') NOT NULL,
  `course_semester` enum('fall','winter') NOT NULL,
  `course_name` varchar(20) NOT NULL,
  `course_year_status_weight` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cu_program_progression`
--

INSERT INTO `cu_program_progression` (`degree_name`, `course_year`, `course_semester`, `course_name`, `course_year_status_weight`) VALUES
('SE', '1', 'fall', 'ECOR1010', 1),
('SE', '1', 'fall', 'MATH1004', 1),
('SE', '1', 'fall', 'MATH1104', 1),
('SE', '1', 'fall', 'PHYS1003', 1),
('SE', '1', 'fall', 'SYSC1005', 1),
('SE', '1', 'winter', 'CHEM1101', 1),
('SE', '1', 'winter', 'ECOR1101', 1),
('SE', '1', 'winter', 'MATH1005', 1),
('SE', '1', 'winter', 'PHYS1004', 1),
('SE', '1', 'winter', 'SYSC2006', 1),
('CE', '1', 'fall', 'CHEM1101', 1),
('CE', '1', 'fall', 'C_ELECT_1', 0),
('CE', '1', 'fall', 'ECOR1010', 1),
('CE', '1', 'fall', 'MATH1004', 1),
('CE', '1', 'fall', 'MATH1104', 1),
('CE', '1', 'winter', 'C_ELECT_2', 0),
('CE', '1', 'winter', 'ECOR1101', 1),
('CE', '1', 'winter', 'ECOR1606', 1),
('CE', '1', 'winter', 'MATH1005', 1),
('CE', '1', 'winter', 'PHYS1004', 1),
('CE', '2', 'fall', 'CCDP2100', 0),
('CE', '2', 'fall', 'ELEC2501', 1),
('CE', '2', 'fall', 'MATH2004', 1),
('CE', '2', 'fall', 'SYSC2001', 1),
('CE', '2', 'fall', 'SYSC2006', 1),
('CE', '2', 'fall', 'S_ELECT_1', 0),
('CE', '2', 'winter', 'ELEC2507', 1),
('CE', '2', 'winter', 'ELEC2607', 1),
('CE', '2', 'winter', 'MATH3705', 1),
('CE', '2', 'winter', 'SYSC2003', 1),
('CE', '2', 'winter', 'SYSC2004', 1),
('CE', '3', 'fall', 'ELEC3500', 1),
('CE', '3', 'fall', 'ELEC3509', 1),
('CE', '3', 'fall', 'STAT2605', 1),
('CE', '3', 'fall', 'SYSC3500', 1),
('CE', '3', 'fall', 'SYSC4602', 1),
('CE', '3', 'winter', 'ECOR3800', 1),
('CE', '3', 'winter', 'ELEC3909', 1),
('CE', '3', 'winter', 'E_ELECT_D_1', 1),
('CE', '3', 'winter', 'SYSC3503', 1),
('CE', '3', 'winter', 'SYSC4502', 1),
('CE', '4', 'fall', 'E_ELECT_C_1', 1),
('CE', '4', 'fall', 'E_ELECT_D_2', 1),
('CE', '4', 'fall', 'SYSC4405', 1),
('CE', '4', 'fall', 'SYSC4504', 1),
('CE', '4', 'fall', 'SYSC4604', 1),
('CE', '4', 'fall', 'SYSC4937', 1),
('CE', '4', 'winter', 'C_ELECT_3', 1),
('CE', '4', 'winter', 'ECOR4995', 1),
('CE', '4', 'winter', 'E_ELECT_C_2', 1),
('CE', '4', 'winter', 'SYSC4700', 1),
('CE', '4', 'winter', 'SYSC4701', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cu_program_progression`
--
ALTER TABLE `cu_program_progression`
 ADD PRIMARY KEY (`degree_name`,`course_year`,`course_semester`,`course_name`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
