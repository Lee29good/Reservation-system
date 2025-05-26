-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2025 年 05 月 17 日 16:51
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
-- 資料表結構 `block_time`
--

CREATE TABLE `block_time` (
  `block_id` int(11) NOT NULL,
  `block_start_date` date NOT NULL,
  `block_end_date` date NOT NULL,
  `seat_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL
) ;

--
-- 傾印資料表的資料 `block_time`
--

INSERT INTO `block_time` (`block_id`, `block_start_date`, `block_end_date`, `seat_id`, `reason`) VALUES
(1, '2025-04-15', '2025-04-17', 101, '設備維修'),
(2, '2025-04-18', '2025-04-20', 605, '清潔維護'),
(3, '2025-04-21', '2025-04-23', 618, '會議安排'),
(4, '2025-04-24', '2025-04-26', 512, '系統升級'),
(5, '2025-04-27', '2025-04-30', 704, '緊急修繕'),
(6, '2025-05-01', '2025-05-02', 104, '設備汰換'),
(7, '2025-05-03', '2025-05-05', 606, '深度清潔'),
(8, '2025-05-06', '2025-05-08', 408, '大型會議'),
(9, '2025-05-09', '2025-05-11', 505, '網路維護'),
(10, '2025-05-13', '2025-05-16', 714, '環境改善'),
(11, '2025-04-04', '2025-04-05', 801, '網路維修'),
(12, '2025-05-21', '2025-05-29', 405, '屋頂修繕');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `block_time`
--
ALTER TABLE `block_time`
  ADD PRIMARY KEY (`block_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `block_time`
--
ALTER TABLE `block_time`
  MODIFY `block_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
