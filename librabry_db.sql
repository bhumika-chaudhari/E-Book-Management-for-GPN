-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Nov 14, 2024 at 11:57 AM
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
-- Database: `librabry_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'admin123', '2024-10-19 09:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `year`, `title`, `description`, `pdf_path`, `department`) VALUES
(6, 3, 'beginning-php-and-mysql', NULL, 'books data/beginning-php-and-mysql.pdf', 'Computer'),
(7, 3, 'python programming modular approach', NULL, 'books data/python programming modular approach.pdf', 'Computer'),
(9, 2, '6th edition networking beginer guide', NULL, 'books data/6th edition networking beginer guide.pdf', 'Computer'),
(10, 2, 'Data Structures Using C Reema thareja', NULL, 'books data/Data Structures Using C Reema thareja.pdf', 'IT'),
(11, 1, 'Fundamentals of Computer Studies', NULL, 'books data/FundamentalsofComputerStudies.pdf', 'Computer'),
(12, 2, 'Database Management Systems 3Rd Edition', NULL, 'books data/Database Management Systems 3Rd Edition.pdf', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `dep_user`
--

CREATE TABLE `dep_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dep_user`
--

INSERT INTO `dep_user` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'computer', 'cm123', '2024-10-19 10:03:38');

-- --------------------------------------------------------

--
-- Table structure for table `notes_auto`
--

CREATE TABLE `notes_auto` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Automobile',
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes_civil`
--

CREATE TABLE `notes_civil` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Civil',
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes_civil`
--

INSERT INTO `notes_civil` (`id`, `year`, `title`, `subject`, `description`, `pdf_path`, `department`, `resource_type`) VALUES
(1, 1, 'Water Unit', 'Chemistry', 'Notes', 'departments/pdfs/unit 3 water.pdf', 'Civil', 'Notes'),
(2, 1, 'Unit-2 Rules of spelling', 'CMS', 'Notes for Spelling Rules', 'departments/pdfs/Unit-2 Rules of spelling.pdf', 'Civil', 'Notes');

-- --------------------------------------------------------

--
-- Table structure for table `notes_computer`
--

CREATE TABLE `notes_computer` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Computer',
  `resource_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes_computer`
--

INSERT INTO `notes_computer` (`id`, `year`, `title`, `subject`, `description`, `pdf_path`, `department`, `resource_type`) VALUES
(1, 3, 'Core Java', 'ADJ', 'This is the Core Java Notes', 'pdfs/core_java.pdf', 'computer', 'Notes'),
(2, 3, 'Advance Java', 'ADJ', 'This is the Advance Java Notes.', 'pdfs/adj.pdf', 'computer', 'Notes'),
(3, 3, 'PHP Syllabus', 'PHP', 'This is the Syllabus For 3rd Year Students with code 21534', 'pdfs/PHP syllabus.pdf', 'computer', 'Syllabus'),
(7, 3, 'Open Source', 'FOS', 'Introduction to Free and Open Source Software notes ', 'pdfs/fos-unit1.pdf', 'computer', 'Notes'),
(20, 3, 'Software Error Case Studies', 'STG', 'Based on Chp1 ', 'pdfs/Software Error Case Studies.pdf', 'Computer', 'Notes'),
(21, 3, 'UNIT-I Introduction and Syntax of Python Program', 'PIP', 'Notes on CHP1 ', 'pdfs/UNIT-I Introduction and Syntax of Python Program.pdf', 'Computer', 'Notes'),
(22, 3, 'Manual for STG', 'STG', 'This is the manual for STG 24-25', 'pdfs/STG Manual CM.pdf', 'Computer', 'Manual'),
(23, 2, 'Data Structure IMP Questions with Answers', 'DST', 'Data structures notes ', 'pdfs/Data Structure IMP Questions with Answers.pdf', 'Computer', 'Notes'),
(24, 2, 'DST Syllabus 23-24', 'DST', 'This is the syllabus for 23-24', 'pdfs/syllabus.pdf', 'Computer', 'Syllabus'),
(25, 2, '8086 Microprocessor notes', 'DTM', 'Notes on Microprocessor 8086', 'pdfs/Notes - UNIT II  8086 Microprocessor.pdf', 'Computer', 'Notes'),
(26, 2, 'CPP Unit 1', 'CPP', 'Notes on c++ basics', 'pdfs/UNIT1_oop.pdf', 'Computer', 'Notes'),
(27, 1, 'Units and Measurements', 'Physics', 'This is the notes on Units and measurement chapter of Physics', 'pdfs/1 units and measurments.pdf', 'Computer', 'Notes'),
(28, 1, 'CMS Vocabulary and Grammar', 'CMS', 'This notes Include basics of vocabulary and grammar', 'pdfs/2ND CHAPTER.pdf', 'Computer', 'Notes'),
(29, 3, 'Final QYP 23-24', 'All', 'This is Pdf including Prevois year Question Paper', 'pdfs/Final PYQ.pdf', 'Computer', 'Paper'),
(30, 2, 'Unit 1-SystemSoftware', 'SSW', 'Notes', 'pdfs/Unit 1-SystemSoftware.pdf', 'Computer', 'Notes'),
(31, 2, 'Unit 2-System Software', 'SSW', 'Notes on SSW', 'departments/pdfs/Unit 2-System Software.pdf', 'Computer', 'Notes');

-- --------------------------------------------------------

--
-- Table structure for table `notes_ddgm`
--

CREATE TABLE `notes_ddgm` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Dress Design',
  `subject` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes_electrical`
--

CREATE TABLE `notes_electrical` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Electrical',
  `resource_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes_electrical`
--

INSERT INTO `notes_electrical` (`id`, `year`, `title`, `subject`, `description`, `pdf_path`, `department`, `resource_type`) VALUES
(1, 3, 'Microcontroller & Applications', 'MCA', 'This course enable us to use the micro controller ', 'pdfs/21432 MCA.pdf', 'Electrical', 'Syllabus'),
(6, 3, 'Switchgear and Protection', 'SGP', 'This is for 3rd year student intoduce to switchgear', 'pdfs/24958c57-b1df-4470-9c8d-6d4748d97084.pdf', 'Electrical', 'Syllabus');

-- --------------------------------------------------------

--
-- Table structure for table `notes_entc`
--

CREATE TABLE `notes_entc` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Entc',
  `subject` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes_entc`
--

INSERT INTO `notes_entc` (`id`, `year`, `title`, `description`, `pdf_path`, `department`, `subject`, `resource_type`) VALUES
(3, 2, 'Unit 1 Semiconductor Diodes', 'Notes inlcuding the detail about this chapter', 'pdfs/Unit No-1. Semiconductor diodes.ppt', 'Entc', 'EDC', 'Notes'),
(4, 1, 'Components of communication', 'notes based on the communication', 'pdfs/Components of communication.pdf', 'Entc', 'CMS', 'Notes'),
(5, 2, 'Notes Project Management PART1', 'notes for students on Projct management', 'pdfs/Notes Project Management PART1.pdf', 'Entc', 'IOM', 'Notes'),
(6, 1, 'Atomic structure, catalysis and Nano-chemistr', 'Notes on Chapter of CHY1', 'pdfs/UNIT 1.pdf', 'Entc', 'Chemistry', 'Notes'),
(7, 2, 'Recitifiers and Filters', 'Notes ', 'pdfs/Unit No-1. Semiconductor diodes.ppt', 'Entc', 'EDC', 'Notes'),
(8, 1, 'Components of communication', 'notes based on the communication', 'pdfs/Components of communication.pdf', 'Entc', 'CMS', 'Notes');

-- --------------------------------------------------------

--
-- Table structure for table `notes_idd`
--

CREATE TABLE `notes_idd` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Interior Design',
  `subject` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes_if`
--

CREATE TABLE `notes_if` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'IT',
  `subject` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes_if`
--

INSERT INTO `notes_if` (`id`, `year`, `title`, `description`, `pdf_path`, `department`, `subject`, `resource_type`) VALUES
(1, 3, 'Software Testing Chp2', 'Notes For Students To help them with Stg subject', 'pdfs/Unit 2 Testing methodologies.pdf', 'Information Technology', 'STG', 'Notes');

-- --------------------------------------------------------

--
-- Table structure for table `notes_mechanical`
--

CREATE TABLE `notes_mechanical` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Mechanical',
  `subject` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes_mect`
--

CREATE TABLE `notes_mect` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Mechatronics',
  `subject` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes_poly`
--

CREATE TABLE `notes_poly` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT 'Polymer',
  `subject` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dep_user`
--
ALTER TABLE `dep_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_auto`
--
ALTER TABLE `notes_auto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_civil`
--
ALTER TABLE `notes_civil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_computer`
--
ALTER TABLE `notes_computer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_ddgm`
--
ALTER TABLE `notes_ddgm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_electrical`
--
ALTER TABLE `notes_electrical`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_entc`
--
ALTER TABLE `notes_entc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_idd`
--
ALTER TABLE `notes_idd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_if`
--
ALTER TABLE `notes_if`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_mechanical`
--
ALTER TABLE `notes_mechanical`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_mect`
--
ALTER TABLE `notes_mect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes_poly`
--
ALTER TABLE `notes_poly`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `dep_user`
--
ALTER TABLE `dep_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notes_auto`
--
ALTER TABLE `notes_auto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes_civil`
--
ALTER TABLE `notes_civil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notes_computer`
--
ALTER TABLE `notes_computer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notes_ddgm`
--
ALTER TABLE `notes_ddgm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes_electrical`
--
ALTER TABLE `notes_electrical`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notes_entc`
--
ALTER TABLE `notes_entc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notes_idd`
--
ALTER TABLE `notes_idd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes_if`
--
ALTER TABLE `notes_if`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notes_mechanical`
--
ALTER TABLE `notes_mechanical`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes_mect`
--
ALTER TABLE `notes_mect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes_poly`
--
ALTER TABLE `notes_poly`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
