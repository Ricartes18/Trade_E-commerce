-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 07:05 PM
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
-- Database: `user`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `User_ID` int(11) NOT NULL,
  `Product_ID` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `User_ID` int(36) NOT NULL,
  `username` varchar(255) NOT NULL,
  `FirstName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `LastName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `PhoneNumber` varchar(11) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`User_ID`, `username`, `FirstName`, `LastName`, `PhoneNumber`, `Password`, `role`, `date_created`) VALUES
(1, 'ricartes', 'Cedric', 'Dungca', '09813320360', '$2y$10$JITH8iQeAPC.z0/T.PNFDefqsLQo.xd/YC2SGVejEe9GXlT.S/5EG', 'admin', '2025-03-28 00:22:56'),
(2, 'chewei', 'Meg', 'Esguerra', '09369537233', '$2y$10$T0xfb9eTT/GzXtJ7X37LXef4JUpkoR3JOgJ1eesHGWIWBv56QWX62', 'admin', '2025-03-28 00:22:56'),
(3, 'Korlishells', 'Adrian', 'Curley', '09605644843', '$2y$10$KRINDvCQ/hQY.6Jy2AFwLOZ2s0zyWjt/2q8n1OyuCxgOtOawS9nTe', 'admin', '2025-03-28 00:24:49'),
(4, 'seowfie', 'Sofia', 'Sarmiento', '09229495255', '$2y$10$TpBLrrvAGG2/RngHk/m1/uSQUoIo99FTCcusrPCH5yg.3Nc1UMUqu', 'admin', '2025-03-28 00:24:49'),
(5, 'coc0o.09', 'Coco', 'Dungca', '09218627366', '$2y$10$TuNZxkI6tx2cJlC08oM5oun2poHNtYdnXhBmjntggrQ6MafRS.IGK', 'user', '2025-03-28 00:29:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD UNIQUE KEY `User_ID` (`User_ID`,`Product_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `User_ID` (`User_ID`),
  ADD UNIQUE KEY `PhoneNumber` (`PhoneNumber`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `User_ID` int(36) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `User_ID` FOREIGN KEY (`User_ID`) REFERENCES `info` (`User_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
