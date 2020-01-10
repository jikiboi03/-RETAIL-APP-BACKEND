-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2020 at 04:43 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `retail_app_production`
--

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `num` int(11) NOT NULL,
  `prod_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qty_before` int(11) DEFAULT NULL,
  `qty_after` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `encoded` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_adjustments`
--

INSERT INTO `stock_adjustments` (`num`, `prod_id`, `qty`, `qty_before`, `qty_after`, `remarks`, `encoded`) VALUES
(6, 63, 2, 9, 11, 'test1', '2020-01-10 23:16:49'),
(7, 63, 5, 11, 16, 'test2', '2020-01-10 23:17:27'),
(8, 63, -3, 16, 13, 'test3', '2020-01-10 23:18:23'),
(9, 63, -4, 13, 9, 'test4', '2020-01-10 23:19:24'),
(10, 85, 3, 0, 3, 'test1', '2020-01-10 23:20:50'),
(11, 84, 5, 0, 5, 'test1', '2020-01-10 23:21:18'),
(12, 84, -6, 5, -1, 'test2', '2020-01-10 23:24:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`num`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `num` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
