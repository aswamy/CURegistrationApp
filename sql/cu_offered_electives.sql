-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2014 at 02:30 AM
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
-- Table structure for table `cu_offered_electives`
--

CREATE TABLE IF NOT EXISTS `cu_offered_electives` (
  `degree_name` enum('SE','BEE','CE','CSE') NOT NULL DEFAULT 'CE',
  `elective_type` varchar(10) NOT NULL,
  `course_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cu_offered_electives`
--

INSERT INTO `cu_offered_electives` (`degree_name`, `elective_type`, `course_name`) VALUES
('CE', 'E_ELECT_D', 'ELEC3105'),
('CE', 'E_ELECT_D', 'ELEC3500'),
('CE', 'E_ELECT_D', 'ELEC3508'),
('CE', 'E_ELECT_D', 'ELEC3509'),
('CE', 'E_ELECT_D', 'ELEC3605'),
('CE', 'E_ELECT_D', 'ELEC3908'),
('CE', 'E_ELECT_D', 'ELEC3909'),
('CE', 'E_ELECT_D', 'ELEC3999'),
('CE', 'E_ELECT_D', 'ELEC4502'),
('CE', 'E_ELECT_D', 'ELEC4503'),
('CE', 'E_ELECT_D', 'ELEC4505'),
('CE', 'E_ELECT_D', 'ELEC4506'),
('CE', 'E_ELECT_D', 'ELEC4601'),
('CE', 'E_ELECT_D', 'ELEC4602'),
('CE', 'E_ELECT_D', 'ELEC4704'),
('CE', 'E_ELECT_D', 'ELEC4705'),
('CE', 'E_ELECT_D', 'ELEC4708'),
('CE', 'E_ELECT_D', 'ELEC4709'),
('CE', 'E_ELECT_D', 'SYSC3006'),
('CE', 'E_ELECT_D', 'SYSC3010'),
('CE', 'E_ELECT_D', 'SYSC3110'),
('CE', 'E_ELECT_D', 'SYSC3200'),
('CE', 'E_ELECT_D', 'SYSC3203'),
('CE', 'E_ELECT_D', 'SYSC3500'),
('CE', 'E_ELECT_D', 'SYSC3600'),
('CE', 'E_ELECT_D', 'SYSC3610'),
('CE', 'E_ELECT_D', 'SYSC3999'),
('CE', 'E_ELECT_D', 'SYSC4001'),
('CE', 'E_ELECT_D', 'SYSC4101'),
('CE', 'E_ELECT_D', 'SYSC4120'),
('CE', 'E_ELECT_D', 'SYSC4203'),
('CE', 'E_ELECT_D', 'SYSC4405'),
('CE', 'E_ELECT_D', 'SYSC4504'),
('CE', 'E_ELECT_D', 'SYSC4505'),
('CE', 'E_ELECT_D', 'SYSC4600'),
('CE', 'E_ELECT_D', 'SYSC4602'),
('CE', 'E_ELECT_D', 'SYSC4906'),
('CE', 'E_ELECT_D', 'ELEC3907'),
('CE', 'E_ELECT_D', 'ELEC4504'),
('CE', 'E_ELECT_D', 'ELEC4509'),
('CE', 'E_ELECT_D', 'ELEC4600'),
('CE', 'E_ELECT_D', 'ELEC4609'),
('CE', 'E_ELECT_D', 'ELEC4700'),
('CE', 'E_ELECT_D', 'ELEC4702'),
('CE', 'E_ELECT_D', 'ELEC4703'),
('CE', 'E_ELECT_D', 'ELEC4706'),
('CE', 'E_ELECT_D', 'ELEC4707'),
('CE', 'E_ELECT_D', 'ELEC4907'),
('CE', 'E_ELECT_D', 'ELEC4908'),
('CE', 'E_ELECT_D', 'SYSC3020'),
('CE', 'E_ELECT_D', 'SYSC3101'),
('CE', 'E_ELECT_D', 'SYSC3120'),
('CE', 'E_ELECT_D', 'SYSC3303'),
('CE', 'E_ELECT_D', 'SYSC3501'),
('CE', 'E_ELECT_D', 'SYSC3503'),
('CE', 'E_ELECT_D', 'SYSC3601'),
('CE', 'E_ELECT_D', 'SYSC4005'),
('CE', 'E_ELECT_D', 'SYSC4102'),
('CE', 'E_ELECT_D', 'SYSC4106'),
('CE', 'E_ELECT_D', 'SYSC4201'),
('CE', 'E_ELECT_D', 'SYSC4202'),
('CE', 'E_ELECT_D', 'SYSC4205'),
('CE', 'E_ELECT_D', 'SYSC4502'),
('CE', 'E_ELECT_D', 'SYSC4507'),
('CE', 'E_ELECT_D', 'SYSC4604'),
('CE', 'E_ELECT_D', 'SYSC4607'),
('CE', 'E_ELECT_D', 'SYSC4700'),
('CE', 'E_ELECT_D', 'SYSC4701'),
('CE', 'E_ELECT_D', 'SYSC4805'),
('CE', 'E_ELECT_D', 'SYSC4806'),
('CE', 'E_ELECT_D', 'SYSC4907'),
('CE', 'E_ELECT_D', 'SYSC4917'),
('CE', 'E_ELECT_D', 'SYSC4927'),
('CE', 'E_ELECT_D', 'SYSC4937'),
('CE', 'E_ELECT_C', 'ELEC4503'),
('CE', 'E_ELECT_C', 'ELEC4505'),
('CE', 'E_ELECT_C', 'ELEC4506'),
('CE', 'E_ELECT_C', 'ELEC4509'),
('CE', 'E_ELECT_C', 'ELEC4502'),
('CE', 'E_ELECT_C', 'SYSC4607'),
('CE', 'S_ELECT', 'BIOL1902'),
('CE', 'S_ELECT', 'BIOL2106'),
('CE', 'S_ELECT', 'CHEM1003'),
('CE', 'S_ELECT', 'CHEM1004'),
('CE', 'S_ELECT', 'ERTH2402'),
('CE', 'S_ELECT', 'ERTH2403'),
('CE', 'S_ELECT', 'ERTH2415'),
('CE', 'S_ELECT', 'ENSC2001'),
('CE', 'S_ELECT', 'FOOD1001'),
('CE', 'S_ELECT', 'NEUR1201'),
('CE', 'S_ELECT', 'PHYS1001'),
('CE', 'S_ELECT', 'PHYS1003'),
('CE', 'S_ELECT', 'PHYS1901'),
('CE', 'S_ELECT', 'PHYS1902'),
('CE', 'S_ELECT', 'PHYS2004'),
('CE', 'C_ELECT', 'AFRI1001'),
('CE', 'C_ELECT', 'AFRI1002'),
('CE', 'C_ELECT', 'AFRI2001'),
('CE', 'C_ELECT', 'BUSI2101'),
('CE', 'C_ELECT', 'ENST1001'),
('CE', 'C_ELECT', 'ENST1020');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
