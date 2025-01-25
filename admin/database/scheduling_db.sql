-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2020 at 11:01 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scheduling_db`
--

-- --------------------------------------------------------

-- First, drop tables if they exist (in reverse order of dependencies)
DROP TABLE IF EXISTS `class_schedule_info`;
DROP TABLE IF EXISTS `schedules`;
DROP TABLE IF EXISTS `rooms`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `subjects`;
DROP TABLE IF EXISTS `faculty`;
DROP TABLE IF EXISTS `users`;

-- Create tables in order of dependencies (referenced tables first)

-- Users table
CREATE TABLE `users` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin,2=Staff, 3= subscriber',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Courses table
CREATE TABLE `courses` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `course` varchar(200) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Subjects table
CREATE TABLE `subjects` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Faculty table
CREATE TABLE `faculty` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `id_no` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rooms table
CREATE TABLE `rooms` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(200) NOT NULL,
  `room_type` enum('Hall','Room') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Schedules table (depends on rooms and faculty)
CREATE TABLE `schedules` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `faculty_id` int(30) NOT NULL,
  `title` varchar(200) NOT NULL,
  `schedule_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1= class, 2= meeting,3=others',
  `description` text NOT NULL,
  `room_id` int(30) NOT NULL,
  `is_repeating` tinyint(1) NOT NULL DEFAULT 1,
  `repeating_data` text NOT NULL,
  `schedule_date` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`),
  FOREIGN KEY (`faculty_id`) REFERENCES `faculty`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Class Schedule Info table (depends on schedules, courses, and subjects)
CREATE TABLE `class_schedule_info` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(30) NOT NULL,
  `course_id` int(30) NOT NULL,
  `subject` int(30) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `class_schedule_info_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_schedule_info_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `class_schedule_info_ibfk_3` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add indexes separately after all tables are created
ALTER TABLE `class_schedule_info` 
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `subject` (`subject`);

-- Insert initial data
INSERT INTO `users` (`name`, `username`, `password`, `type`) VALUES
('Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 1);

INSERT INTO `rooms` (`room_name`, `room_type`) VALUES
('Main Hall', 'Hall'),
('Conference Hall', 'Hall'),
('Exhibition Hall', 'Hall'),
('Room 101', 'Room'),
('Room 102', 'Room');

-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course`, `description`) VALUES
(1, 'Information Technology', 'IT'),
(4, 'BSCS', 'Bachelor of Science in Computer Science'),
(5, 'BSIS', 'Bachelor of Science in Information Systems'),
(6, 'BSED', 'Bachelor in Secondary Education');

-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `id_no`, `firstname`, `middlename`, `lastname`, `contact`, `gender`, `address`, `email`) VALUES
(1, '06232014', 'John', 'C', 'Smith', '+18456-5455-55', 'Male', 'Sample Address', 'jsmith@sample.com'),
(2, '37362629', 'Claire', 'C', 'Blake', '+12345687923', 'Female', 'Sample Address', 'cblake@sample.com');

-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`faculty_id`, `title`, `schedule_type`, `description`, `room_id`, `is_repeating`, `repeating_data`, `schedule_date`, `time_from`, `time_to`, `date_created`) 
VALUES (2, 'Class 101 (M & Th)', 1, 'Sample Only', 1, 1, '{\"dow\":\"1,4\",\"start\":\"2020-10-01\",\"end\":\"2020-11-30\"}', '0000-00-00', '09:00:00', '12:00:00', '2020-10-20 15:51:01');

-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject`, `description`) VALUES
(1, 'DBMS', 'Database Management System'),
(2, 'Mathematics', 'Mathematics'),
(3, 'English', 'English'),
(4, 'Computer Hardware', 'Computer Hardware'),
(5, 'History', 'History');

-- AUTO_INCREMENT for dumped tables
--

-- AUTO_INCREMENT for table `class_schedule_info`
--
ALTER TABLE `class_schedule_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
