-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 04:45 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `role_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `username`, `action`, `status`, `ip_address`, `created_at`) VALUES
(1, 1, 'admin', 'login', 'success', '::1', '2025-06-22 20:23:41'),
(2, 1, 'admin', 'login', 'success', '::1', '2025-06-22 20:37:18'),
(3, 2, 'user1', 'login', 'success', '::1', '2025-06-22 23:06:47'),
(4, 2, 'user1', 'login', 'success', '::1', '2025-06-22 23:06:55'),
(5, 2, 'user1', 'login', 'success', '::1', '2025-06-22 23:07:01'),
(6, 2, 'user1', 'login', 'success', '::1', '2025-06-22 23:07:30'),
(7, 1, 'admin', 'login', 'success', '::1', '2025-06-22 23:10:33'),
(8, 2, 'user1', 'login', 'success', '::1', '2025-06-22 23:11:56'),
(9, 1, 'admin', 'login', 'success', '::1', '2025-06-22 23:12:59'),
(10, 2, 'user1', 'login', 'success', '::1', '2025-06-22 23:21:10'),
(11, 1, 'admin', 'login', 'success', '::1', '2025-06-22 23:21:27'),
(12, 5, 'user2', 'login', 'success', '::1', '2025-06-22 23:22:40'),
(13, 1, 'admin', 'login', 'success', '::1', '2025-06-22 23:25:38'),
(14, 1, 'admin', 'login', 'success', '::1', '2025-06-22 23:26:45'),
(15, 1, 'admin', 'login', 'success', '::1', '2025-06-22 23:27:18'),
(16, 1, 'admin', 'login', 'success', '::1', '2025-06-22 23:39:14'),
(17, 6, 'ako', 'login', 'fail', '::1', '2025-06-23 00:34:53'),
(18, 6, 'ako', 'login', 'fail', '::1', '2025-06-23 00:35:09'),
(19, 6, 'ako', 'login', 'fail', '::1', '2025-06-23 00:35:31'),
(20, 6, 'ako', 'login', 'fail', '::1', '2025-06-23 00:35:54'),
(21, 6, 'ako', 'login', 'fail', '::1', '2025-06-23 00:36:09'),
(22, 1, 'admin', 'login', 'success', '::1', '2025-06-23 08:34:18'),
(23, 1, 'admin', 'login', 'success', '::1', '2025-06-23 08:35:42'),
(24, 7, 'user3', 'login', 'success', '::1', '2025-06-23 08:47:14'),
(25, 1, 'admin', 'login', 'success', '::1', '2025-06-23 08:47:29'),
(26, 7, 'user3', 'login', 'success', '::1', '2025-06-23 09:56:20'),
(27, 7, 'user3', 'login', 'success', '::1', '2025-06-23 09:57:59'),
(28, 1, 'admin', 'login', 'success', '::1', '2025-06-23 09:58:26'),
(29, 2, 'user1', 'login', 'success', '::1', '2025-06-23 10:00:42'),
(30, 2, 'user1', 'login', 'success', '::1', '2025-06-23 10:01:06'),
(31, 2, 'user1', 'login', 'success', '::1', '2025-06-23 10:01:50'),
(32, 2, 'user1', 'login', 'success', '::1', '2025-06-23 10:02:04'),
(33, 2, 'user1', 'login', 'success', '::1', '2025-06-23 10:04:11'),
(34, 1, 'admin', 'login', 'success', '::1', '2025-06-23 10:08:38'),
(35, 2, 'user1', 'login', 'success', '::1', '2025-06-23 10:09:13'),
(36, 7, 'user3', 'login', 'fail', '::1', '2025-06-23 10:11:28'),
(37, 7, 'user3', 'login', 'fail', '::1', '2025-06-23 10:11:59'),
(38, 1, 'admin', 'login', 'success', '::1', '2025-06-23 10:12:33'),
(39, 2, 'user1', 'login', 'success', '::1', '2025-06-23 10:13:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `can_create` tinyint(1) DEFAULT 0,
  `can_read` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `can_create`, `can_read`, `can_edit`, `can_delete`) VALUES
(1, 'hehehe', 0, 1, 1, 1),
(4, 'chaychay', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `can_create` tinyint(1) DEFAULT 0,
  `can_read` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_attempt` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `status`, `created_at`, `can_create`, `can_read`, `can_edit`, `can_delete`, `failed_attempts`, `last_failed_attempt`, `last_login`) VALUES
(1, 'admin', 'admin123', 'admin', 'active', '2025-06-22 13:06:41', 1, 1, 1, 1, 0, NULL, '2025-06-23 10:12:33'),
(2, 'user1', 'user123', 'user', 'active', '2025-06-22 13:06:41', 0, 0, 1, 1, 0, NULL, '2025-06-23 10:13:04'),
(3, 'tank', 'admin123', 'user', 'active', '2025-06-22 13:29:06', 0, 1, 1, 0, 0, NULL, NULL),
(5, 'user2', 'user123', 'user', 'active', '2025-06-22 15:22:01', 0, 1, 1, 0, 0, NULL, NULL),
(6, 'ako', 'admin123', 'user', 'active', '2025-06-22 16:34:24', 1, 1, 1, 0, 5, '2025-06-23 00:36:09', NULL),
(7, 'user3', 'admin123', 'user', 'active', '2025-06-23 00:46:46', 1, 1, 0, 0, 2, '2025-06-23 10:11:59', '2025-06-23 09:57:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
