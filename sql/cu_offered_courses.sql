-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2014 at 06:32 PM
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
-- Table structure for table `cu_offered_courses`
--

CREATE TABLE IF NOT EXISTS `cu_offered_courses` (
  `course_name` varchar(20) NOT NULL,
  `course_has_lab` tinyint(1) NOT NULL,
  `year_status_requirement` tinyint(4) NOT NULL DEFAULT '1',
  `course_size` enum('semester','year') NOT NULL,
  `course_prerequisite` text NOT NULL,
  `course_degree_requirement` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cu_offered_courses`
--

INSERT INTO `cu_offered_courses` (`course_name`, `course_has_lab`, `year_status_requirement`, `course_size`, `course_prerequisite`, `course_degree_requirement`) VALUES
('CCDP2100', 1, 1, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('CHEM1101', 1, 1, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('ECOR1010', 1, 1, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('ECOR1101', 1, 1, 'semester', '{"courses":[[{"name":"MATH1004","concurrent":false}],[{"name":"MATH1104","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('ECOR1606', 1, 1, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('ECOR3800', 1, 3, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('ECOR4995', 1, 4, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('ELEC2501', 1, 1, 'semester', '{"courses":[[{"name":"MATH1005","concurrent":false}],[{"name":"PHYS1004","concurrent":false},{"name":"PHYS1002","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('ELEC2507', 1, 1, 'semester', '{"courses":[[{"name":"ELEC2501","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('ELEC2607', 1, 1, 'semester', '{"courses":[[{"name":"PHYS1004","concurrent":false},{"name":"PHYS1002","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('ELEC3500', 1, 1, 'semester', '{"courses":[[{"name":"ELEC2507","concurrent":false}],[{"name":"ELEC2607","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('ELEC3509', 1, 1, 'semester', '{"courses":[[{"name":"ELEC2507","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('ELEC3909', 1, 3, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('MATH1004', 1, 1, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('MATH1005', 1, 1, 'semester', '{"courses":[[{"name":"MATH1004","concurrent":false}],[{"name":"MATH1104","concurrent":false},{"name":"MATH1107","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('MATH1104', 1, 1, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('MATH2004', 1, 1, 'semester', '{"courses":[[{"name":"MATH1005","concurrent":false},{"name":"MATH2007","concurrent":false}],[{"name":"MATH1104","concurrent":false},{"name":"MATH1107","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('MATH3705', 1, 1, 'semester', '{"courses":[[{"name":"MATH1005","concurrent":false},{"name":"MATH2404","concurrent":false}],[{"name":"MATH2004","concurrent":false},{"name":"MATH2008","concurrent":false},{"name":"MATH2009","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('PHYS1003', 1, 1, 'semester', '{"courses":[[{"name":"MATH1004","concurrent":true},{"name":"MATH1007","concurrent":true},{"name":"MATH1002","concurrent":true}]]}', '["SE","CE","CSE", "BEE"]'),
('PHYS1004', 1, 1, 'semester', '{"courses":[[{"name":"MATH1004","concurrent":false}],[{"name":"ECOR1101","concurrent":true}]]}', '["SE","CE","CSE", "BEE"]'),
('STAT2605', 1, 1, 'semester', '{"courses":[[{"name":"MATH1007","concurrent":false},{"name":"MATH1004","concurrent":false},{"name":"MATH1002","concurrent":false}],[{"name":"MATH1104","concurrent":false},{"name":"MATH1107","concurrent":false},{"name":"MATH1102","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC1005', 1, 1, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('SYSC2001', 1, 1, 'semester', '{"courses":[[{"name":"ECOR1606","concurrent":false},{"name":"SYSC1005","concurrent":true}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC2003', 1, 1, 'semester', '{"courses":[[{"name":"SYSC2001","concurrent":false}],[{"name":"SYSC2002","concurrent":false},{"name":"SYSC2006","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC2004', 1, 1, 'semester', '{"courses":[[{"name":"SYSC2002","concurrent":false},{"name":"SYSC2006","concurrent":true}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC2006', 1, 1, 'semester', '{"courses":[[{"name":"ECOR1606","concurrent":false},{"name":"SYSC1005","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC3500', 1, 1, 'semester', '{"courses":[[{"name":"MATH2004","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC3503', 1, 1, 'semester', '{"courses":[[{"name":"STAT2605","concurrent":false}],[{"name":"SYSC2500","concurrent":false},{"name":"SYSC3500","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC4405', 1, 1, 'semester', '{"courses":[[{"name":"SYSC3500","concurrent":false},{"name":"SYSC3600","concurrent":false},{"name":"SYSC3610","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC4502', 1, 3, 'semester', '{"courses":[[{"name":"SYSC4602","concurrent":false}],[{"name":"SYSC2004","concurrent":false},{"name":"SYSC2100","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC4504', 1, 1, 'semester', '{"courses":[[{"name":"SYSC2004","concurrent":false},{"name":"SYSC2100","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC4602', 1, 3, 'semester', '{"courses":[[{"name":"STAT2605","concurrent":true},{"name":"STAT3502","concurrent":true}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC4604', 1, 1, 'semester', '{"courses":[[{"name":"SYSC3503","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC4700', 1, 4, 'semester', '{"courses":[[{"name":"SYSC3501","concurrent":false},{"name":"SYSC3503","concurrent":false}]]}', '["SE","CE","CSE", "BEE"]'),
('SYSC4701', 1, 4, 'semester', '{"courses":[]}', '["SE","CE","CSE", "BEE"]'),
('SYSC4937', 1, 4, 'year', '{"courses":[[{"name":"ECOR4995","concurrent":true}]]}', '["SE","CE","CSE", "BEE"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cu_offered_courses`
--
ALTER TABLE `cu_offered_courses`
 ADD PRIMARY KEY (`course_name`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
