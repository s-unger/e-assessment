-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 11, 2021 at 03:34 PM
-- Server version: 10.5.9-MariaDB-1
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-assessment_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `questionId` int(11) NOT NULL,
  `correctness` tinyint(1) NOT NULL,
  `solved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `misconception` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `userId`, `questionId`, `correctness`, `solved_at`, `misconception`) VALUES
(1, 1, 0, 1, '2021-06-26 11:10:18', NULL),
(2, 1, 1, 1, '2021-06-26 11:10:18', NULL),
(3, 1, 2, 1, '2021-06-26 11:10:18', NULL),
(4, 1, 3, 1, '2021-06-26 11:10:18', NULL),
(5, 1, 0, 1, '2021-06-26 11:11:17', NULL),
(6, 1, 1, 1, '2021-06-26 11:11:17', NULL),
(7, 1, 2, 1, '2021-06-26 11:11:17', NULL),
(8, 1, 3, 1, '2021-06-26 11:11:17', NULL),
(9, 1, 0, 1, '2021-06-26 11:13:09', NULL),
(10, 1, 1, 1, '2021-06-26 11:13:09', NULL),
(11, 1, 2, 1, '2021-06-26 11:13:09', NULL),
(12, 1, 3, 1, '2021-06-26 11:13:09', NULL),
(13, 1, 0, 0, '2021-06-26 11:13:25', NULL),
(14, 1, 1, 1, '2021-06-26 11:13:25', NULL),
(15, 1, 2, 0, '2021-06-26 11:13:25', NULL),
(16, 1, 3, 0, '2021-06-26 11:13:25', NULL),
(17, 1, 0, 1, '2021-06-27 11:39:59', NULL),
(18, 1, 1, 0, '2021-06-27 11:39:59', NULL),
(19, 1, 2, 1, '2021-06-27 11:39:59', NULL),
(20, 1, 3, 0, '2021-06-27 11:39:59', NULL),
(21, 1, 0, 0, '2021-06-27 11:40:29', NULL),
(22, 1, 1, 0, '2021-06-27 11:40:29', NULL),
(23, 1, 2, 0, '2021-06-27 11:40:29', NULL),
(24, 1, 3, 0, '2021-06-27 11:40:29', NULL),
(25, 1, 0, 1, '2021-06-28 11:41:23', NULL),
(26, 1, 1, 1, '2021-06-28 11:41:23', NULL),
(27, 1, 2, 1, '2021-06-28 11:41:23', NULL),
(28, 1, 3, 1, '2021-06-28 11:41:23', NULL),
(29, 1, 0, 1, '2021-06-28 11:41:56', NULL),
(30, 1, 1, 0, '2021-06-28 11:41:56', NULL),
(31, 1, 2, 1, '2021-06-28 11:41:56', NULL),
(32, 1, 3, 0, '2021-06-28 11:41:56', NULL),
(33, 1, 0, 1, '2021-06-28 11:42:31', NULL),
(34, 1, 1, 0, '2021-06-28 11:42:31', NULL),
(35, 1, 2, 0, '2021-06-28 11:42:31', NULL),
(36, 1, 3, 0, '2021-06-28 11:42:31', NULL),
(37, 1, 0, 0, '2021-06-28 12:12:47', NULL),
(38, 1, 1, 1, '2021-06-28 12:12:47', NULL),
(39, 1, 2, 0, '2021-06-28 12:12:47', NULL),
(40, 1, 3, 0, '2021-06-28 12:12:47', NULL),
(41, 1, 0, 0, '2021-06-28 12:13:16', NULL),
(42, 1, 1, 0, '2021-06-28 12:13:16', NULL),
(43, 1, 2, 0, '2021-06-28 12:13:16', NULL),
(44, 1, 3, 0, '2021-06-28 12:13:16', NULL),
(45, 1, 0, 1, '2021-06-28 12:14:36', NULL),
(46, 1, 1, 1, '2021-06-28 12:14:36', NULL),
(47, 1, 2, 1, '2021-06-28 12:14:36', NULL),
(48, 1, 3, 1, '2021-06-28 12:14:36', NULL),
(49, 1, 0, 1, '2021-06-28 12:15:04', NULL),
(50, 1, 1, 0, '2021-06-28 12:15:04', NULL),
(51, 1, 2, 1, '2021-06-28 12:15:04', NULL),
(52, 1, 3, 0, '2021-06-28 12:15:04', NULL),
(53, 4, 0, 0, '2021-07-08 18:14:46', NULL),
(54, 4, 1, 1, '2021-07-08 18:14:46', NULL),
(55, 4, 2, 0, '2021-07-08 18:14:46', NULL),
(56, 4, 3, 0, '2021-07-08 18:14:46', NULL),
(57, 4, 0, 1, '2021-07-08 18:15:00', NULL),
(58, 4, 1, 0, '2021-07-08 18:15:00', NULL),
(59, 4, 2, 0, '2021-07-08 18:15:00', NULL),
(60, 4, 3, 0, '2021-07-08 18:15:00', NULL),
(61, 4, 0, 1, '2021-07-09 09:26:19', NULL),
(62, 4, 1, 1, '2021-07-09 09:26:19', NULL),
(63, 4, 2, 0, '2021-07-09 09:26:19', NULL),
(64, 4, 3, 0, '2021-07-09 09:26:19', NULL),
(65, 4, 0, 1, '2021-07-09 09:38:26', NULL),
(66, 4, 1, 1, '2021-07-09 09:38:26', NULL),
(67, 4, 0, 0, '2021-07-09 10:07:28', NULL),
(68, 4, 1, 0, '2021-07-09 10:07:28', NULL),
(69, 4, 2, 1, '2021-07-09 10:07:28', NULL),
(70, 4, 0, 1, '2021-07-09 10:54:36', NULL),
(71, 4, 1, 0, '2021-07-09 10:54:36', NULL),
(72, 4, 2, 1, '2021-07-09 10:54:36', NULL),
(73, 4, 3, 1, '2021-07-09 10:54:36', NULL),
(74, 4, 4, 0, '2021-07-09 10:54:36', NULL),
(75, 4, 5, 2, '2021-07-09 10:54:36', NULL),
(76, 4, 6, 0, '2021-07-09 10:54:36', NULL),
(77, 4, 0, 1, '2021-07-09 10:56:35', NULL),
(78, 4, 1, 1, '2021-07-09 10:56:35', NULL),
(79, 4, 2, 1, '2021-07-09 10:56:35', NULL),
(80, 4, 3, 1, '2021-07-09 10:56:35', NULL),
(81, 4, 4, 0, '2021-07-09 10:56:35', NULL),
(82, 4, 5, 2, '2021-07-09 10:56:35', NULL),
(83, 4, 6, 0, '2021-07-09 10:56:35', NULL),
(84, 4, 0, 1, '2021-07-09 10:57:05', NULL),
(85, 4, 1, 1, '2021-07-09 10:57:05', NULL),
(86, 4, 2, 1, '2021-07-09 10:57:05', NULL),
(87, 4, 3, 1, '2021-07-09 10:57:05', NULL),
(88, 4, 4, 0, '2021-07-09 10:57:05', NULL),
(89, 4, 5, 2, '2021-07-09 10:57:05', NULL),
(90, 4, 6, 0, '2021-07-09 10:57:05', NULL),
(91, 4, 0, 1, '2021-07-09 10:58:14', NULL),
(92, 4, 1, 0, '2021-07-09 10:58:14', NULL),
(93, 4, 2, 0, '2021-07-09 10:58:14', NULL),
(94, 4, 3, 1, '2021-07-09 10:58:14', NULL),
(95, 4, 4, 0, '2021-07-09 10:58:14', NULL),
(96, 4, 5, 0, '2021-07-09 10:58:14', NULL),
(97, 4, 6, 0, '2021-07-09 10:58:14', NULL),
(98, 1, 0, 0, '2021-07-10 08:07:47', NULL),
(99, 1, 1, 1, '2021-07-10 08:07:47', NULL),
(100, 1, 2, 1, '2021-07-10 08:07:47', NULL),
(101, 1, 3, 3, '2021-07-10 08:07:47', NULL),
(102, 1, 4, 0, '2021-07-10 08:07:47', NULL),
(103, 1, 5, 0, '2021-07-10 08:07:47', NULL),
(104, 1, 6, 1, '2021-07-10 08:07:47', NULL),
(105, 5, 0, 0, '2021-07-10 09:47:52', NULL),
(106, 5, 1, 0, '2021-07-10 09:47:52', NULL),
(107, 5, 2, 1, '2021-07-10 09:47:52', NULL),
(108, 5, 3, 3, '2021-07-10 09:47:52', NULL),
(109, 5, 4, 0, '2021-07-10 09:47:52', NULL),
(110, 5, 5, 0, '2021-07-10 09:47:52', NULL),
(111, 5, 6, 0, '2021-07-10 09:47:52', NULL),
(112, 4, 0, 1, '2021-07-10 11:12:58', NULL),
(113, 4, 1, 1, '2021-07-10 11:12:58', NULL),
(114, 4, 2, 0, '2021-07-10 11:12:58', NULL),
(115, 4, 3, 0, '2021-07-10 11:12:58', NULL),
(116, 4, 4, 0, '2021-07-10 11:12:58', NULL),
(117, 4, 5, 0, '2021-07-10 11:12:58', NULL),
(118, 4, 6, 0, '2021-07-10 11:12:58', NULL),
(119, 4, 0, 1, '2021-07-10 11:16:14', NULL),
(120, 4, 1, 0, '2021-07-10 11:16:14', NULL),
(121, 4, 2, 0, '2021-07-10 11:16:14', NULL),
(122, 4, 3, 1, '2021-07-10 11:16:14', NULL),
(123, 4, 4, 0, '2021-07-10 11:16:14', NULL),
(124, 4, 5, 0, '2021-07-10 11:16:14', NULL),
(125, 4, 6, 0, '2021-07-10 11:16:14', NULL),
(126, 4, 0, 1, '2021-07-09 22:00:00', NULL),
(127, 4, 1, 0, '2021-07-09 22:00:00', NULL),
(128, 4, 2, 0, '2021-07-09 22:00:00', NULL),
(129, 4, 3, 0, '2021-07-09 22:00:00', NULL),
(130, 4, 4, 1, '2021-07-09 22:00:00', NULL),
(131, 4, 5, 0, '2021-07-09 22:00:00', NULL),
(132, 4, 6, 0, '2021-07-09 22:00:00', NULL),
(133, 4, 0, 1, '2021-07-10 22:00:00', NULL),
(134, 4, 1, 1, '2021-07-10 22:00:00', NULL),
(135, 4, 2, 0, '2021-07-10 22:00:00', NULL),
(136, 4, 3, 0, '2021-07-10 22:00:00', NULL),
(137, 4, 4, 0, '2021-07-10 22:00:00', 2),
(138, 4, 5, 0, '2021-07-10 22:00:00', NULL),
(139, 4, 6, 0, '2021-07-10 22:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exam_answers`
--

CREATE TABLE `exam_answers` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `questionId` int(11) NOT NULL,
  `correctness` tinyint(1) NOT NULL,
  `solved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_answers`
--

INSERT INTO `exam_answers` (`id`, `userId`, `questionId`, `correctness`, `solved_at`) VALUES
(1, 4, 0, 1, '2021-07-09 16:39:23'),
(2, 4, 1, 0, '2021-07-09 16:39:23'),
(3, 4, 2, 0, '2021-07-09 16:39:23'),
(4, 4, 3, 3, '2021-07-09 16:39:23'),
(5, 4, 4, 0, '2021-07-09 16:39:23'),
(6, 4, 5, 0, '2021-07-09 16:39:23'),
(7, 4, 6, 0, '2021-07-09 16:39:23'),
(8, 4, 0, 1, '2021-07-09 22:00:00'),
(9, 4, 1, 1, '2021-07-09 22:00:00'),
(10, 4, 2, 0, '2021-07-09 22:00:00'),
(11, 4, 3, 1, '2021-07-09 22:00:00'),
(12, 4, 0, 0, '2021-07-09 22:00:00'),
(13, 4, 1, 0, '2021-07-09 22:00:00'),
(14, 4, 2, 0, '2021-07-09 22:00:00'),
(15, 4, 3, 0, '2021-07-09 22:00:00'),
(16, 4, 0, 0, '2021-07-09 22:00:00'),
(17, 4, 1, 0, '2021-07-09 22:00:00'),
(18, 4, 2, 0, '2021-07-09 22:00:00'),
(19, 4, 3, 1, '2021-07-09 22:00:00'),
(20, 4, 4, 1, '2021-07-09 22:00:00'),
(21, 4, 5, 1, '2021-07-09 22:00:00'),
(22, 4, 6, 0, '2021-07-09 22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passwort` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `passwort`, `created_at`) VALUES
(1, 'Sophia', '$2y$10$PuV62h1YhT6tj6RWSdurye9n7/yb3ip8WWfXHuCJF7LNq6FDvB/Hy', '2021-06-09 12:57:34'),
(2, 'sophia2', '$2y$10$yr98.7UkHI9B.YrDLUx8w.IXAIf52TeSMbDpewaDqlHaR3UUVKdfm', '2021-06-13 10:01:22'),
(3, 'yoni123', '$2y$10$vYIrnhwRx9klyyllodVzbeb151UjvuOubxkA0.QZNXKglkFVNRM/e', '2021-06-15 07:38:49'),
(4, 'test', '$2y$10$daVnx7hhBgbmjg8A0q3vteBUqrZc4CNs6TfkEVV8uN3/6XAsDFGju', '2021-06-15 07:38:56'),
(5, 'yoni', '$2y$10$Uazm2s/xMlIXf.SJ3ng66uDHHvwyU7ng2Z4nmqFudPdZ/an/8JmC6', '2021-06-22 07:23:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_answers`
--
ALTER TABLE `exam_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
--
-- AUTO_INCREMENT for table `exam_answers`
--
ALTER TABLE `exam_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
