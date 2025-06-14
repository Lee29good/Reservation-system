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
-- 資料表結構 `seat`
--

CREATE TABLE `seat` (
  `seat_id` int(11) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `position` varchar(20) NOT NULL,
  `has_power_outlet` tinyint(1) DEFAULT 0,
  `status` enum('available','reserved','disabled') DEFAULT 'available',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `seat`
--

INSERT INTO `seat` (`seat_id`, `room_type`, `position`, `has_power_outlet`, `status`, `updated_at`) VALUES
(101, '小型討論室', '1F', 1, 'available', '2025-04-21 14:20:59'),
(102, '小型討論室', '1F', 1, 'available', '2025-04-21 14:20:59'),
(103, '小型討論室', '1F', 1, 'available', '2025-04-21 14:20:59'),
(104, '小型討論室', '1F', 1, 'available', '2025-04-21 14:20:59'),
(401, '大型討論室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(402, '大型討論室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(403, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(404, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(405, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(406, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(407, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(408, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(409, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(410, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(411, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(412, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(413, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(414, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(415, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(416, '當日研究室', '4F', 1, 'available', '2025-04-21 14:20:59'),
(501, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(502, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(503, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(504, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(505, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(506, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(507, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(508, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(509, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(510, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(511, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(512, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(513, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(514, '當日研究室', '5F', 1, 'available', '2025-04-21 14:20:59'),
(601, '小型討論室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(602, '小型討論室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(603, '小型討論室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(604, '小型討論室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(605, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(606, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(607, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(608, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(609, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(610, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(611, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(612, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(613, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(614, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(615, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(616, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(617, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(618, '當日研究室', '6F', 1, 'available', '2025-04-21 14:20:59'),
(701, '小型討論室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(702, '小型討論室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(703, '小型討論室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(704, '小型討論室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(705, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(706, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(707, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(708, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(709, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(710, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(711, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(712, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(713, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(714, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(715, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(716, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(717, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(718, '長期研究室', '7F', 1, 'available', '2025-04-21 14:20:59'),
(801, '小型討論室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(802, '小型討論室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(803, '小型討論室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(804, '小型討論室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(805, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(806, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(807, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(808, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(809, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(810, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(811, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(812, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(813, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(814, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(815, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(816, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(817, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59'),
(818, '長期研究室', '8F', 1, 'available', '2025-04-21 14:20:59');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `seat`
--
ALTER TABLE `seat`
  ADD PRIMARY KEY (`seat_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `seat`
--
ALTER TABLE `seat`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=819;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
