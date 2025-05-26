-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2025 年 04 月 24 日 18:03
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
-- 資料表結構 `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('reserved','cancelled','checked_in') DEFAULT 'reserved',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `reservation`
--

INSERT INTO `reservation` (`reservation_id`, `user_id`, `seat_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 406, '2025-04-21', '2025-04-21', 'reserved', '2025-04-21 23:28:44', '2025-04-21 23:28:44'),
(2, 11, 406, '2025-04-22', '2025-04-22', 'reserved', '2025-04-22 12:46:38', '2025-04-22 12:46:38'),
(3, 2, 408, '2025-04-24', '2025-04-24', 'checked_in', '2025-04-22 16:35:23', '2025-04-24 23:11:56'),
(4, 11, 506, '2025-04-25', '2025-04-25', 'cancelled', '2025-04-23 12:54:07', '2025-04-23 13:25:43'),
(11, 11, 706, '2025-04-28', '2025-04-30', 'reserved', '2025-04-23 14:07:34', '2025-04-23 14:07:34'),
(12, 11, 513, '2025-05-08', '2025-05-08', 'reserved', '2025-04-23 14:07:53', '2025-04-23 14:07:53'),
(15, 11, 810, '2025-05-03', '2025-05-04', 'reserved', '2025-04-23 22:40:14', '2025-04-23 22:40:14'),
(16, 11, 407, '2025-04-24', '2025-04-24', 'cancelled', '2025-04-24 22:15:39', '2025-04-24 23:01:15'),
(17, 11, 805, '2025-04-23', '2025-04-25', 'cancelled', '2025-04-24 23:02:43', '2025-04-24 23:07:18'),
(18, 11, 806, '2025-04-24', '2025-04-27', 'cancelled', '2025-04-24 23:11:36', '2025-04-24 23:12:07');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`start_date`,`end_date`),
  ADD UNIQUE KEY `seat_id` (`seat_id`,`start_date`,`end_date`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seat` (`seat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
