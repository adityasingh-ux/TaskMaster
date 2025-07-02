-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 01, 2025 at 09:04 PM
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
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `due_date`, `status`, `created_at`) VALUES
(1, 'Web Development', 'Complete the website in a week.', 1001, '2025-07-09', 'pending', '2025-07-01 18:55:49'),
(2, 'Web Development', 'Complete the website in a week.', 1002, '2025-07-09', 'pending', '2025-07-01 18:55:49'),
(3, 'Web Development', 'Complete the website in a week.', 1003, '2025-07-09', 'pending', '2025-07-01 18:55:49'),
(4, 'Web Development', 'Complete the website in a week.', 1004, '2025-07-09', 'pending', '2025-07-01 18:55:49'),
(5, 'Hindi', 'Learn Hindi', 1001, '2025-07-12', 'pending', '2025-07-01 18:57:25'),
(6, 'Hindi', 'Learn Hindi', 1002, '2025-07-12', 'pending', '2025-07-01 18:57:25'),
(7, 'Hindi', 'Learn Hindi', 1003, '2025-07-12', 'pending', '2025-07-01 18:57:25'),
(8, 'Hindi', 'Learn Hindi', 1004, '2025-07-12', 'pending', '2025-07-01 18:57:25');

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
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;