-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 07:04 PM
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
-- Database: `merch_exchange`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Order_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Product_ID` int(10) UNSIGNED NOT NULL,
  `num_ordered` int(9) NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `OrderDetails_ID` int(11) NOT NULL,
  `Order_ID` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `pickup_location` enum('Holy Angel University - Main Gate','SM City Clark - Main Entrance','SM Telebastagan - Food Court','Marquee Mall - J.CO Entrance') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Holy Angel University - Main Gate',
  `mode_of_payment` enum('Cash on Meetup','GCASH') NOT NULL DEFAULT 'Cash on Meetup',
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Pending','Ongoing','Delivered','Cancelled') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `Product_ID` int(10) UNSIGNED NOT NULL,
  `Product_Image` varchar(255) NOT NULL,
  `Photocard_Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Tradable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`Product_ID`, `Product_Image`, `Photocard_Title`, `Description`, `Price`, `Quantity`, `Tradable`) VALUES
(1, 'Yunah.jpg', 'Yunah SUPER REAL ME POB M2 Debut Show', 'ILLIT Yunah SUPER REAL ME M2 Debut Show', 400.00, 5, 1),
(2, 'Moka.jpg', 'Moka SUPER REAL ME POB M2 Debut Show', 'ILLIT Moka SUPER REAL ME M2 Debut Show', 1600.00, 2, 0),
(3, 'Minju.jpg', 'Minju SUPER REAL ME POB M2 Debut Show', 'ILLIT Minju SUPER REAL ME M2 Debut Show', 480.00, 3, 1),
(4, 'Iroha.jpg', 'Iroha SUPER REAL ME POB M2 Debut Show', 'ILLIT Iroha SUPER REAL ME M2 Debut Show', 640.00, 4, 0),
(5, 'Wonhee.jpg', 'Wonhee SUPER REAL ME POB M2 Debut Show', 'ILLIT Wonhee SUPER REAL ME M2 Debut Show', 640.00, 2, 1),
(6, 'chaewonhoodie.jpg', 'Kim Chaewon Hoodie', 'IZ*ONE Oneiric Diary 3D Version', 2000.00, 1, 0),
(7, 'youngeunheart.jpg', 'Seo Youngeun Fingerheart', 'KEP1ER First Impact Connect 1 Ver', 100.00, 7, 1),
(8, 'chaehyunpisngi.jpg', 'Kim Chaehyun Pisngi', 'KEP1ER First Impact Connect - Ver', 180.00, 5, 1),
(9, 'hanschool.jpg', 'Han Jisung School Unif', 'STRAY KIDS GO Live White Back Member Ver', 300.00, 2, 1),
(10, 'flowerwon.jpg', 'Yang Jungwon Flowerwon', 'ENHYPEN Dimension Answer Yet Ver', 400.00, 2, 1),
(11, 'leeseomandu.jpg', 'Lee Hyunseo Leeseo Mandu', 'IVE Eleven WithDrama PoB', 850.00, 1, 0),
(12, 'isaprincess.jpg', 'Lee Chaeyoung Isa Princess', 'STAYC Stereotype Type B', 350.00, 2, 1),
(13, 'AhyeonDRIP.png', 'Ahyeon DRIP FOREVER ALBUM ', 'BABYMONSTER Ahyeon DRIP FOREVER', 450.00, 4, 1),
(14, 'GiselleMYWORLD.png', 'Giselle MY WORLD ALBUM', 'aespa Giselle My World', 400.00, 2, 0),
(15, 'KarinaMYWORLD.png', 'Karina MY WORLD ALBUM', 'aespa Karina My World', 600.00, 4, 0),
(16, 'Jeonghanhawak.png', 'Jeonghan SVT RIGHT HERE', 'SVT Jeonghan SVT RIGHT HERE DEAR VER.A', 300.00, 2, 1),
(17, 'HanniHowSweet.png', 'Hanni HOW SWEET WEVERSE POB', 'NJZ HANNI HOW SWEET WEVERSE POB', 250.00, 6, 1),
(18, 'MingyuTadashi.png', 'Mingyu \'Tadashi\' IN THE SOOP', 'SVT Mingyu IN THE SOOP', 500.00, 3, 1),
(19, 'NingningArmageddon.png', 'Ningning ARMAGEDDON', 'aespa Ningning ARMAGEDDON SM TOWN', 850.00, 3, 0),
(20, 'WonyALIVE.jpg', 'Wonyoung Alive Japan 2nd EP', 'IVE Wonyoung Alive Japan 2nd EP', 1200.00, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trade`
--

CREATE TABLE `trade` (
  `Trade_ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `Product_ID` int(10) UNSIGNED NOT NULL,
  `Trade_Name` varchar(255) NOT NULL,
  `Trade_Description` text NOT NULL,
  `Trade_Offer` varchar(255) NOT NULL,
  `Trade_Status` enum('Pending','Ongoing','Completed','Declined') NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`Order_ID`),
  ADD UNIQUE KEY `Order_ID` (`Order_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `submitted_date` (`submitted_date`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`OrderDetails_ID`),
  ADD KEY `submitted_date` (`submitted_date`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_ID`),
  ADD KEY `Photocard_Title` (`Photocard_Title`);

--
-- Indexes for table `trade`
--
ALTER TABLE `trade`
  ADD PRIMARY KEY (`Trade_ID`),
  ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `OrderDetails_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `Product_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `trade`
--
ALTER TABLE `trade`
  MODIFY `Trade_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `Product_ID` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `User_ID` FOREIGN KEY (`User_ID`) REFERENCES `user`.`info` (`User_ID`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `submitted_date` FOREIGN KEY (`submitted_date`) REFERENCES `orders` (`submitted_date`) ON DELETE CASCADE;

--
-- Constraints for table `trade`
--
ALTER TABLE `trade`
  ADD CONSTRAINT `username` FOREIGN KEY (`username`) REFERENCES `user`.`info` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
