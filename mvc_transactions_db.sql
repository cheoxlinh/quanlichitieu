-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2025 at 11:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvc_transactions_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('currency', 'VND'),
('usd_to_vnd_rate', '26073.9368');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `amount` decimal(15,0) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'VND',
  `transaction_type` enum('Revenue','Expense') NOT NULL,
  `status` enum('Success','Pending','Failed') NOT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `amount`, `currency`, `transaction_type`, `status`, `tag_id`, `note`, `created_by`, `created_at`, `updated_at`) VALUES
(15, 20000000, 'VND', 'Expense', 'Success', 10, 'Đổ đất ruộng ,làm ao	', 1, '2025-06-09 07:34:06', '2025-06-09 07:34:10'),
(16, 10000000, 'VND', 'Expense', 'Success', 10, 'Đổ đất ruộng ,làm ao	', 1, '2025-06-09 07:34:23', '2025-06-09 07:34:23'),
(17, 10000000, 'VND', 'Expense', 'Success', 10, 'Đổ đất ruộng ,làm ao	', 1, '2025-06-09 07:34:35', '2025-06-09 07:34:35'),
(18, 500000, 'VND', 'Expense', 'Success', 9, 'Minh Tục hữu lộc ,gửi chị Thanh', 1, '2025-06-18 03:42:12', '2025-06-18 03:42:12'),
(19, 200000, 'VND', 'Expense', 'Success', 9, 'Trung Kỳ,GIẢNG TÍN', 1, '2025-06-18 08:23:12', '2025-06-18 08:23:19'),
(20, 200000, 'VND', 'Expense', 'Success', 9, 'CHÚ TÙNG CÓC,GIẢNG TÍN', 1, '2025-06-18 08:23:52', '2025-06-18 08:23:52'),
(21, 200000, 'VND', 'Expense', 'Success', 9, 'A BÌNH BẢO,GIẢNG TÍN', 1, '2025-06-18 08:25:31', '2025-06-18 08:25:31'),
(22, 300000, 'VND', 'Expense', 'Success', 9, 'LINH TRUYỀN,GIẢNG TÍN', 1, '2025-06-18 08:25:57', '2025-06-18 08:25:57'),
(23, 200000, 'VND', 'Expense', 'Success', 9, 'NGA LỤA,GIẢNG TÍN', 1, '2025-06-18 08:26:15', '2025-06-18 08:26:15'),
(24, 200000, 'VND', 'Expense', 'Success', 9, 'ỤT LỤA ,GIẢNG TÍN', 1, '2025-06-18 08:26:37', '2025-06-18 08:26:37'),
(25, 200000, 'VND', 'Expense', 'Success', 9, 'BÌNH HẢO,GIẢNG TÍN', 1, '2025-06-18 08:26:54', '2025-06-18 08:26:54'),
(26, 200000, 'VND', 'Expense', 'Success', 9, 'QUYỀN UYỂN,GIẢNG TÍN', 1, '2025-06-18 08:27:16', '2025-06-18 08:27:16'),
(27, 200000, 'VND', 'Expense', 'Success', 9, 'CHÚ HOÀI,GIẢNG TÍN', 1, '2025-06-18 08:27:34', '2025-06-18 08:27:34'),
(28, 200000, 'VND', 'Expense', 'Success', 9, 'O BÉ CHÍNH,GIẢNG TÍN', 1, '2025-06-18 08:27:50', '2025-06-18 08:27:50'),
(29, 200000, 'VND', 'Expense', 'Success', 9, 'HẠNH CƠ,GIẢNG TÍN', 1, '2025-06-18 08:28:05', '2025-06-18 08:28:05'),
(30, 500000, 'VND', 'Expense', 'Success', 9, 'ANH TÍNH,GIẢNG TÍN', 1, '2025-06-18 08:28:20', '2025-06-18 08:28:20');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_tags`
--

CREATE TABLE `transaction_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_tags`
--

INSERT INTO `transaction_tags` (`id`, `name`, `created_at`) VALUES
(9, 'Đám cưới', '2025-06-09 07:27:34'),
(10, 'Đổ đất - làm ao', '2025-06-09 07:33:06'),
(11, 'Tiền lương', '2025-06-09 07:36:16'),
(12, 'Sinh hoạt cá nhân', '2025-06-09 07:36:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$31yNN0CqEsGjL6EABmZwVuPdAyj5Bd6euo9yrUxnCeOMbK0vKzWUa', '2025-06-09 02:41:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tag_id` (`tag_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `transaction_tags`
--
ALTER TABLE `transaction_tags`
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
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `transaction_tags`
--
ALTER TABLE `transaction_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `transaction_tags` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
