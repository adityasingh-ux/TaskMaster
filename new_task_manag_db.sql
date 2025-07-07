-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 07, 2025 at 08:49 AM
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
-- Database: `new_task_manag_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_info`
--

CREATE TABLE `admin_info` (
  `sno` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_info`
--

INSERT INTO `admin_info` (`sno`, `username`, `password`) VALUES
(1, 'harshit', 1234),
(2, 'ojas singh', 2345);

-- --------------------------------------------------------

--
-- Table structure for table `rollnos`
--

CREATE TABLE `rollnos` (
  `sno` int(100) NOT NULL,
  `rollno` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rollnos`
--

INSERT INTO `rollnos` (`sno`, `rollno`) VALUES
(1, 1001),
(2, 1002),
(3, 1003),
(4, 1004);

-- --------------------------------------------------------

--
-- Table structure for table `submitted_tasks`
--

CREATE TABLE `submitted_tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `submitted_by` varchar(50) NOT NULL,
  `submission_date` date DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submitted_tasks`
--

INSERT INTO `submitted_tasks` (`id`, `title`, `description`, `submitted_by`, `submission_date`, `file_path`) VALUES
(1, 'Web Development', 'Completed within a week', '1002', '2025-07-06', ''),
(2, 'Hindi', 'Completed this task', '1001', '2025-07-06', ''),
(3, ' image', 'image upload', '1001', '2025-07-06', 'tasks/uploads/686ab41daa49c.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `due_date`, `status`, `created_at`, `file_path`) VALUES
(1, 'Web Development', 'Complete the website in a week.', 1001, '2025-07-03', 'in_progress', '2025-07-01 18:55:49', NULL),
(2, 'Web Development', 'Complete the website in a week.', 1002, '2025-07-03', 'pending', '2025-07-01 18:55:49', NULL),
(3, 'Web Development', 'Complete the website in a week.', 1003, '2025-07-03', 'pending', '2025-07-01 18:55:49', NULL),
(4, 'Web Development', 'Complete the website in a week.', 1004, '2025-07-03', 'pending', '2025-07-01 18:55:49', NULL),
(5, 'Hindi', 'Learn Hindi', 1001, '2025-07-12', 'completed', '2025-07-01 18:57:25', NULL),
(6, 'Hindi', 'Learn Hindi', 1002, '2025-07-12', 'pending', '2025-07-01 18:57:25', NULL),
(7, 'Hindi', 'Learn Hindi', 1003, '2025-07-12', 'pending', '2025-07-01 18:57:25', NULL),
(8, 'Hindi', 'Learn Hindi', 1004, '2025-07-12', 'completed', '2025-07-01 18:57:25', NULL),
(28, ' image', 'chck img upload', 1001, '2025-08-02', 'completed', '2025-07-03 21:47:17', ''),
(29, ' image', 'chck img upload', 1002, '2025-08-02', 'pending', '2025-07-03 21:47:17', ''),
(30, ' image', 'chck img upload', 1003, '2025-08-02', 'pending', '2025-07-03 21:47:17', ''),
(31, ' image', 'chck img upload', 1004, '2025-08-02', 'pending', '2025-07-03 21:47:17', ''),
(32, 'New test', 'testimgggggggg', 1001, '2025-07-19', 'pending', '2025-07-03 22:46:26', 'uploads/686708427bfba.png'),
(33, 'New test', 'testimgggggggg', 1002, '2025-07-19', 'in_progress', '2025-07-03 22:46:26', 'uploads/686708427bfba.png'),
(34, 'New test', 'testimgggggggg', 1003, '2025-07-19', 'pending', '2025-07-03 22:46:26', 'uploads/686708427bfba.png'),
(35, 'New test', 'testimgggggggg', 1004, '2025-07-19', 'in_progress', '2025-07-03 22:46:26', 'uploads/686708427bfba.png'),
(36, 'New task ', 'pdf checking', 1001, '2025-07-27', 'in_progress', '2025-07-03 23:34:39', 'uploads/6867138f2e3eb.pdf'),
(37, 'New task ', 'pdf checking', 1002, '2025-07-27', 'completed', '2025-07-03 23:34:39', 'uploads/6867138f2e3eb.pdf'),
(38, 'New task ', 'pdf checking', 1003, '2025-07-27', 'pending', '2025-07-03 23:34:39', 'uploads/6867138f2e3eb.pdf'),
(39, 'New task ', 'pdf checking', 1004, '2025-07-27', 'pending', '2025-07-03 23:34:39', 'uploads/6867138f2e3eb.pdf'),
(40, 'World War Z', 'Complete the mission', 1001, '2025-07-25', 'pending', '2025-07-04 18:04:18', 'uploads/686817a23bf7a.pdf'),
(41, 'World War Z', 'Complete the mission', 1002, '2025-07-25', 'pending', '2025-07-04 18:04:18', 'uploads/686817a23bf7a.pdf'),
(42, 'World War Z', 'Complete the mission', 1003, '2025-07-25', 'pending', '2025-07-04 18:04:18', 'uploads/686817a23bf7a.pdf'),
(43, 'World War Z', 'Complete the mission', 1004, '2025-07-25', 'pending', '2025-07-04 18:04:18', 'uploads/686817a23bf7a.pdf'),
(44, 'Welcome task', 'Welcome everybody', 1001, '2025-07-24', 'pending', '2025-07-06 19:35:29', 'uploads/cat.jpg'),
(45, 'Welcome task', 'Welcome everybody', 1002, '2025-07-24', 'pending', '2025-07-06 19:35:29', 'uploads/cat.jpg'),
(46, 'Welcome task', 'Welcome everybody', 1003, '2025-07-24', 'pending', '2025-07-06 19:35:29', 'uploads/cat.jpg'),
(47, 'Welcome task', 'Welcome everybody', 1004, '2025-07-24', 'pending', '2025-07-06 19:35:29', 'uploads/cat.jpg'),
(48, 'Another check', 'checking', 1001, '2025-07-20', 'pending', '2025-07-06 19:37:14', 'uploads/cat.jpg'),
(49, 'Another check', 'checking', 1002, '2025-07-20', 'pending', '2025-07-06 19:37:14', 'uploads/cat.jpg'),
(50, 'Another check', 'checking', 1003, '2025-07-20', 'pending', '2025-07-06 19:37:14', 'uploads/cat.jpg'),
(51, 'Another check', 'checking', 1004, '2025-07-20', 'pending', '2025-07-06 19:37:14', 'uploads/cat.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `sno` int(11) NOT NULL,
  `username` varchar(11) NOT NULL,
  `password` varchar(23) NOT NULL,
  `dt` datetime NOT NULL DEFAULT current_timestamp(),
  `rollno` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`sno`, `username`, `password`, `dt`, `rollno`) VALUES
(1, 'Isaac', 'isaac', '2025-07-01 16:01:43', 1001),
(2, 'Harshit', 'harshit', '2025-07-01 23:37:39', 1002),
(3, 'Ojas', 'ojas', '2025-07-02 00:22:39', 1003),
(4, 'Aditya', 'aditya', '2025-07-02 00:23:11', 1004);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_info`
--
ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `rollnos`
--
ALTER TABLE `rollnos`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `submitted_tasks`
--
ALTER TABLE `submitted_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_info`
--
ALTER TABLE `admin_info`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rollnos`
--
ALTER TABLE `rollnos`
  MODIFY `sno` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `submitted_tasks`
--
ALTER TABLE `submitted_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;