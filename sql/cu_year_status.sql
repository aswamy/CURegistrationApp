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
-- Table structure for table `cu_year_status`
--

CREATE TABLE IF NOT EXISTS `cu_year_status` (
  `degree_name` enum('SE','BEE','CE','CSE') NOT NULL,
  `degree_year` int(11) NOT NULL,
  `course_credit` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cu_year_status`
--

INSERT INTO `cu_year_status` (`degree_name`, `degree_year`, `course_credit`) VALUES
('CE', 2, 4),
('CE', 3, 4),
('CE', 4, 3.5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cu_year_status`
--
ALTER TABLE `cu_year_status`
 ADD PRIMARY KEY (`degree_name`,`degree_year`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
