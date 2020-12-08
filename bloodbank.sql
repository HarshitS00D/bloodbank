-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2020 at 08:48 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bloodbank`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_group`
--

CREATE TABLE `blood_group` (
  `Id` int(1) NOT NULL,
  `BloodGroup` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blood_group`
--

INSERT INTO `blood_group` (`Id`, `BloodGroup`) VALUES
(1, 'O+'),
(2, 'O-'),
(3, 'A+'),
(4, 'A-'),
(5, 'B+'),
(6, 'B-'),
(7, 'AB+'),
(8, 'AB-');

-- --------------------------------------------------------

--
-- Table structure for table `blood_info`
--

CREATE TABLE `blood_info` (
  `Id` int(11) NOT NULL,
  `HospitalId` int(11) NOT NULL,
  `BloodGroup` int(1) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Updated_At` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blood_info`
--

INSERT INTO `blood_info` (`Id`, `HospitalId`, `BloodGroup`, `Quantity`, `Updated_At`) VALUES
(6, 2, 1, 25, '2020-12-07 18:43:41'),
(7, 3, 2, 19, '2020-12-07 17:41:08'),
(8, 3, 3, 20, '2020-12-07 18:35:20'),
(9, 3, 4, 20, '2020-12-07 18:35:28'),
(10, 3, 5, 20, '2020-12-07 18:35:32'),
(11, 3, 6, 20, '2020-12-07 18:35:35'),
(12, 3, 7, 20, '2020-12-07 18:35:39'),
(13, 3, 8, 20, '2020-12-07 18:35:44'),
(14, 2, 4, 10, '2020-12-07 18:44:11'),
(15, 2, 2, 5, '2020-12-07 18:44:04'),
(16, 2, 3, 5, '2020-12-07 18:44:08'),
(17, 2, 5, 5, '2020-12-07 18:44:14'),
(18, 2, 6, 5, '2020-12-07 18:44:18'),
(19, 2, 7, 5, '2020-12-07 18:44:21'),
(20, 2, 8, 5, '2020-12-07 18:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `blood_request`
--

CREATE TABLE `blood_request` (
  `RequestId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `HospitalId` int(11) NOT NULL,
  `BloodGroup` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Status` varchar(40) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blood_request`
--

INSERT INTO `blood_request` (`RequestId`, `UserId`, `HospitalId`, `BloodGroup`, `Quantity`, `Status`) VALUES
(1, 12, 3, 2, 1, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `HospitalId` int(11) NOT NULL,
  `HospitalName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PhoneNumber` varchar(10) NOT NULL,
  `State` varchar(50) NOT NULL,
  `City` varchar(50) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `Password` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`HospitalId`, `HospitalName`, `Email`, `PhoneNumber`, `State`, `City`, `Address`, `Password`) VALUES
(2, 'PGI Hospital', 'pgi@gmail.com', '1234567895', 'Chandigarh', 'Chandigarh', 'Madhya Marg, Chandigarh', 'password'),
(3, 'Fortis', 'fortis@gmail.com', '9876543210', 'Delhi', 'Central Delhi', 'Delhi 6', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserId` int(11) NOT NULL,
  `FirstName` varchar(40) NOT NULL,
  `LastName` varchar(40) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PhoneNumber` varchar(10) NOT NULL,
  `BloodGroup` int(1) NOT NULL,
  `Password` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserId`, `FirstName`, `LastName`, `Email`, `PhoneNumber`, `BloodGroup`, `Password`) VALUES
(7, 'Harshit', 'Sood', 'hsood92@gmail.com', '7404148745', 3, 'harshit'),
(8, 'Prikshit', 'rana', 'prikshit@gmail.com', '9729810245', 7, 'prikshit'),
(10, 'Vipin', 'Sood', 'vipinsood@gmail.com', '9876543212', 5, 'vipin123'),
(11, 'tanishq', 'malhotra', 'tanishq@gmail.com', '1234567895', 1, 'tanishq'),
(12, 'sanskar', 'sharma', 'sanskar@gmail.com', '1234567896', 2, 'sanskar');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_group`
--
ALTER TABLE `blood_group`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `blood_info`
--
ALTER TABLE `blood_info`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `BloodGroup` (`BloodGroup`),
  ADD KEY `HospitalId` (`HospitalId`);

--
-- Indexes for table `blood_request`
--
ALTER TABLE `blood_request`
  ADD PRIMARY KEY (`RequestId`),
  ADD KEY `BloodGroup` (`BloodGroup`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `HospitalId` (`HospitalId`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`HospitalId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserId`),
  ADD KEY `BloodGroup` (`BloodGroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_group`
--
ALTER TABLE `blood_group`
  MODIFY `Id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `blood_info`
--
ALTER TABLE `blood_info`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `blood_request`
--
ALTER TABLE `blood_request`
  MODIFY `RequestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `HospitalId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_info`
--
ALTER TABLE `blood_info`
  ADD CONSTRAINT `blood_info_ibfk_1` FOREIGN KEY (`BloodGroup`) REFERENCES `blood_group` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `blood_info_ibfk_2` FOREIGN KEY (`HospitalId`) REFERENCES `hospitals` (`HospitalId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `blood_request`
--
ALTER TABLE `blood_request`
  ADD CONSTRAINT `blood_request_ibfk_1` FOREIGN KEY (`BloodGroup`) REFERENCES `blood_group` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `blood_request_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `blood_request_ibfk_3` FOREIGN KEY (`HospitalId`) REFERENCES `hospitals` (`HospitalId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`BloodGroup`) REFERENCES `blood_group` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
