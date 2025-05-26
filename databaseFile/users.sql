-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2025 年 05 月 17 日 16:52
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `lib_reservation`
--

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `create_date` datetime DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `is_admin`, `create_date`, `update_date`) VALUES
(1, 'admin', '$2y$10$Uf9JwIhT7eAcQ4Cr1vK22Oh89fR24g6xR2M6Oouw..u5H.MlaUTJe', 'admin@example.com', 1, '2025-04-14 13:44:16', '2025-04-14 15:25:22'),
(2, 'alice', '$2y$10$BtSbRtcm0e0c3epgAGE/kO4qIsJ9lRN2rsQ88vUzYH89mrv3m0BTO', 'alice@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:24:36'),
(3, 'brian', '$2y$10$k0x8dKXq0YF3V3GQkf/OgufkOjfkjBMtoaIrS8rNJBHyvRgk1Dh1a', 'brian@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:26:01'),
(4, 'cathy', '$2y$10$fJCU9RhptvHukxy/KAzeBOwlUctL3PdaPXsTEcy4sZhHUEPZR6Hce', 'cathy@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:26:23'),
(5, 'daniel', '$2y$10$NiOLnNa7KIq0f1PyiQuG.ujONokF8CjPNnrfshnlRM6/ayy1Sw4K.', 'daniel@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:26:47'),
(6, 'emily', '$2y$10$qAsGajn/XugjADUFtCjZhOYuu3eQ.h2ltbt9TfbrTNufz8qNOI64e', 'emily@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:28:33'),
(7, 'frank', '$2y$10$0vKEEROgoRh/hgtWPGfrh.tMa7qry4SIAD8bU1cZRMt9hdgRo80mm', 'frank@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:28:56'),
(8, 'grace', '$2y$10$c1K6wor/I1NfvqINL5Idc.xsT/g4My5xfotjpqVjDEnwXleLS3/o6', 'grace@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:29:14'),
(9, 'henry', '$2y$10$fpSKtafVQfwvXEmAq8nPAOfNXzlErsHvQRVk5QFU1U8WuUtcze77q', 'henry@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:29:29'),
(10, 'irene', '$2y$10$iJIobybR6zjgtk0tRipm0e16omTgtO1.f7ynpmCmeZFQhiXNzS7VO', 'irene@example.com', 0, '2025-04-14 13:44:16', '2025-04-14 15:29:42'),
(11, 'Lee', '$2y$10$3QClHQepRLhewOWZ65RTBu/9mttByKF85tSWWBndgZIu.84qgAGrO', 'lilee20020404@gmail.com', 0, '2025-04-14 15:35:46', '2025-05-15 14:17:01'),
(12, 'Lee12', '$2y$10$QX2CAlRuHte9YFpEE7y4NufMgXMGT3t5BXbKxDeQ.e3YdTApRXV6.', 'Lee12@yahoo.com.tw', 0, '2025-04-14 16:16:01', '2025-04-14 16:16:01');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
