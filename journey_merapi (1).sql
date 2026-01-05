-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 05:22 AM
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
-- Database: `journey_merapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID_admin` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID_admin`, `Username`, `Email`, `Password`, `Created_at`) VALUES
(1, 'PramudyaAdmin', 'Admin01@gmail.com', '$2y$10$5TR9PatFY7UTDIFfHccFQuXqDuEUoau9G8mV046cK4Nvwv97ELLdy', '2025-12-09 15:46:30'),
(2, 'JanaAdmin', 'Admin02@gmail.com', '$2y$10$euWO9OubkL7kQP8DQ4obVOSL87M5AVx/my8exX4q/8/FqXNKJx/8O', '2025-12-09 15:46:30'),
(3, 'ZaldiAdmin', 'Admin03@gmail.com', '$2y$10$/UJO00lt7mGg3kGWIuexU.Waq86APhJLfqNNYxVX9ausLpzaHBnWK', '2025-12-09 15:46:30'),
(4, 'FirmanAdmin', 'Admin04@gmail.com', '$2y$10$Mx4H310DLNk8eVEAYOcl0edGMBDkimYGfptQJFACmEHOrU/bxuJRy', '2025-12-09 15:46:30');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `ID_Contact` int(11) NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Message` text NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`ID_Contact`, `User_ID`, `Name`, `Email`, `Message`, `Created_at`) VALUES
(1, 2, 'danish', 'danish12@gmail.com', 'Aplikasi nya sangat interaktif bagi pengguna', '2025-12-10 05:53:09');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID_Order` int(11) NOT NULL,
  `ID_User` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `special_request` text DEFAULT NULL,
  `Jumlah_orang` int(11) DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `pickup_time` time DEFAULT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `Status` varchar(50) DEFAULT 'Pending',
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID_Order`, `ID_User`, `full_name`, `country`, `email`, `phone`, `special_request`, `Jumlah_orang`, `pickup_date`, `pickup_time`, `pickup_location`, `total_price`, `Status`, `Created_at`) VALUES
(1, 2, 'Pramudya Danish Ersyandi ', 'Indonesia ', 'danish12@gmail.com', '088804901667', 'Saya ingin bisa senang dengan wisata yang telah saya pilih ', 4, '2025-12-13', '08:00:00', 'Meeting Point Merapi', 1820000, 'Selesai', '2025-12-10 23:58:58'),
(2, 3, 'Firman Ardiansyah ', 'Indonesia ', 'firman@gmail.com', '0888012345678', 'Saya ingin mencoba ', 2, '2025-12-14', '10:00:00', 'Meeting Point Merapi', 30000, 'Selesai', '2025-12-11 05:39:56'),
(3, 2, 'danish', 'indonesia', 'danish12@gmail.com', '088804901667', 'Saya ingin sudah dimulai saat jam telah dibuka', 4, '2025-12-20', '08:00:00', 'Meeting Point Merapi', 1860000, 'Selesai', '2025-12-15 04:25:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `ID_Item` int(11) NOT NULL,
  `ID_Order` int(11) NOT NULL,
  `Tour_ID` int(11) NOT NULL,
  `Tour_Name` varchar(100) NOT NULL,
  `Price` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`ID_Item`, `ID_Order`, `Tour_ID`, `Tour_Name`, `Price`, `Quantity`, `Subtotal`) VALUES
(1, 1, 1, 'Jeep Lava Tour Merapi', 450000, 4, 1800000),
(2, 1, 3, 'Museum Sisa Hartaku', 5000, 4, 20000),
(3, 2, 2, 'Bunker Kaliadem', 10000, 2, 20000),
(4, 2, 3, 'Museum Sisa Hartaku', 5000, 2, 10000),
(5, 3, 1, 'Jeep Lava Tour Merapi', 450000, 4, 1800000),
(6, 3, 2, 'Bunker Kaliadem', 10000, 4, 40000),
(7, 3, 3, 'Museum Sisa Hartaku', 5000, 4, 20000);

-- --------------------------------------------------------

--
-- Table structure for table `status_merapi`
--

CREATE TABLE `status_merapi` (
  `ID_status` int(11) NOT NULL,
  `Level` varchar(50) NOT NULL,
  `Deskripsi` text NOT NULL,
  `Rekomendasi` text DEFAULT NULL,
  `Update_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `Admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_merapi`
--

INSERT INTO `status_merapi` (`ID_status`, `Level`, `Deskripsi`, `Rekomendasi`, `Update_time`, `Admin_id`) VALUES
(1, 'Siaga', 'Aktivitas vulkanik Merapi meningkat signifikan. Terjadi pertumbuhan kubah lava, guguran lava pijar, dan potensi awan panas mulai terlihat. Kegempaan vulkanik dalam meningkat.\r\n', 'Dilarang beraktivitas dalam radius 5 km dari puncak. Warga di daerah rawan bencana disarankan menyiapkan rencana evakuasi. Wisata Merapi diarahkan tutup sementara.\r\n', '2025-12-10 12:38:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `ID_Tour` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `Gambar` varchar(255) DEFAULT NULL,
  `Deskripsi` text DEFAULT NULL,
  `Rating` decimal(2,1) DEFAULT NULL,
  `Harga_mulai` int(11) DEFAULT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`ID_Tour`, `Nama`, `Gambar`, `Deskripsi`, `Rating`, `Harga_mulai`, `Created_at`) VALUES
(1, 'Jeep Lava Tour Merapi', 'https://ik.imagekit.io/pandooin/tr:pr-true/production/images/attraction/lava-tour-merapi/xZGPIyHj1qC45HKdCoXmsV8bLYP1AkSW1KXVIu0I.jpg', 'Petualangan jeep menyusuri jalur bekas letusan Gunung Merapi.', 5.0, 450000, '2025-12-09 05:30:41'),
(2, 'Bunker Kaliadem', 'img/bungkerkaliadem.jpeg', 'Bunker bersejarah yang berada tepat menghadap Gunung Merapi.', 4.7, 10000, '2025-12-09 05:30:41'),
(3, 'Museum Sisa Hartaku', 'img/museumsisahartaku.jpg', 'Museum berisi peninggalan warga setelah letusan Merapi 2010.', 4.9, 5000, '2025-12-09 05:30:41'),
(4, 'Bukit Klangon', 'img/bukit_klangon.jpg', 'Spot sunrise dengan pemandangan terbaik menuju puncak Merapi.', 4.9, 15000, '2025-12-09 05:30:41'),
(5, 'The Lost World Castle ', 'img/Lostworldcastle.jpg', 'Taman wisata bertema kastil abad pertengahan dengan spot foto unik.', 4.8, 30000, '2025-12-10 23:08:01'),
(6, 'The World Landmark Merapi Park ', 'img/merapipark.jpg', 'Miniatur ikon dunia seperti Eiffel, Liberty, dan Big Ben.', 4.8, 35000, '2025-12-10 23:15:16'),
(7, 'Museum Ullen Sentalu', 'img/museumsentalu.jpg', 'Museum seni & budaya Jawa yang misterius dengan koleksi bersejarah.', 4.9, 100000, '2025-12-10 23:19:07'),
(8, 'Agrowisata Bhumi Merapi', 'img/bhumimerapi.jpg', 'Wisata edukasi hewan & pertanian dengan spot ala Eropa.', 4.8, 50000, '2025-12-10 23:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID_User` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID_User`, `Username`, `Email`, `Password`, `Created_at`) VALUES
(1, 'Zaldi', 'zaldi@gmail.com', '$2y$10$aJ./zHp8hgZlrECu5ikH6u5dgJSJgGiY/dlH68CiZOi.GI7VwoA6K', '2025-12-10 02:41:51'),
(2, 'danish12', 'danish12@gmail.com', '$2y$10$PSSSmUHYM3OfmVoVy4kY5eQKgP.u7ak0w1TTBNp.pfLh733qRK9IS', '2025-12-10 05:51:45'),
(3, 'Firman', 'firman@gmail.com', '$2y$10$NPJCeLTtpJTkTp4r/8QVa.pZbr0lYVEqwgFU7.rm.WCfSEzPHSw0i', '2025-12-11 05:38:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID_admin`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`ID_Contact`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID_Order`),
  ADD KEY `ID_User` (`ID_User`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`ID_Item`),
  ADD KEY `ID_Order` (`ID_Order`);

--
-- Indexes for table `status_merapi`
--
ALTER TABLE `status_merapi`
  ADD PRIMARY KEY (`ID_status`),
  ADD KEY `Admin_id` (`Admin_id`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`ID_Tour`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID_User`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `ID_Contact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID_Order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `ID_Item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `status_merapi`
--
ALTER TABLE `status_merapi`
  MODIFY `ID_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `ID_Tour` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`ID_User`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`ID_Order`) REFERENCES `orders` (`ID_Order`) ON DELETE CASCADE;

--
-- Constraints for table `status_merapi`
--
ALTER TABLE `status_merapi`
  ADD CONSTRAINT `status_merapi_ibfk_1` FOREIGN KEY (`Admin_id`) REFERENCES `admin` (`ID_admin`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
