-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2024 at 04:56 PM
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
-- Database: `primebank`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Primeadmin', 'admin@gmail.com', 'Admin@1234');

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `account_no` varchar(255) NOT NULL,
  `total_balance` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `balance`
--

INSERT INTO `balance` (`account_no`, `total_balance`) VALUES
('105704914063', '16820'),
('38391357849', '1600'),
('816949134023', '380');

-- --------------------------------------------------------

--
-- Table structure for table `credit`
--

CREATE TABLE `credit` (
  `account_id` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(20) NOT NULL,
  `transaction_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credit`
--

INSERT INTO `credit` (`account_id`, `amount`, `transaction_id`, `transaction_date`) VALUES
('245900279791', 32000.00, '171277156073937', '0000-00-00 00:00:00'),
('245900279791', 32000.00, '171277163610532', '0000-00-00 00:00:00'),
('430829803017', 10000.00, '171277176978823', '0000-00-00 00:00:00'),
('245900279791', 5000.00, '171277359572450', '0000-00-00 00:00:00'),
('245900279791', 5000.00, '171277363136976', '0000-00-00 00:00:00'),
('245900279791', 5000.00, '171277366711009', '0000-00-00 00:00:00'),
('430829803017', 100000.00, '171281326027347', '0000-00-00 00:00:00'),
('105704914063', 50000.00, '171281432957560', '0000-00-00 00:00:00'),
('105704914063', 50000.00, '171281435241870', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE `deposit` (
  `deposit_id` varchar(15) NOT NULL,
  `account_id` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `deposit_method` varchar(50) NOT NULL,
  `deposit_reference` varchar(100) NOT NULL,
  `deposit_date` date NOT NULL,
  `cheque_no` varchar(20) DEFAULT NULL,
  `cheque_name` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `cheque_deposit_ac_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`deposit_id`, `account_id`, `amount`, `deposit_method`, `deposit_reference`, `deposit_date`, `cheque_no`, `cheque_name`, `bank_name`, `cheque_deposit_ac_no`) VALUES
('171281432911841', '105704914063', 50000.00, 'cash', '', '2024-04-11', NULL, NULL, NULL, NULL),
('171281435236563', '105704914063', 50000.00, 'cheque', '', '2024-04-11', '123456', 'shree sai krupa', 'ibic', '2345687654334');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `account_no` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`account_no`, `name`, `lname`, `image`, `email`, `password`, `mobile_no`, `address`, `state`, `zip`, `city`) VALUES
('105704914063', 'bhavesh', 'bhadhe', '', 'bhavesh@gmail.com', '$2y$10$AWwZTpMtcAcVNAvYMkQtVO1GTp0DZ3YkFyJVmrd/iO17J0zBedOHW', '8866590889', 'vapi', 'Manipur', '396201', 'vapi'),
('38391357849', 'vignesh', '', '', 'vig@gmail.com', '$2y$10$LTLdY4s4NrlGOopH/yHdqeBl9l1tSeDqgY4i2zICPvkeinZSFkbV.', '', '', '', '', ''),
('816949134023', 'rakesh', '', '', 'rakesh@gmail.com', '$2y$10$LdOF7KgNJSz6S.0QvAy/Kezshni92KpmjPtmiTRn4PetKkhVk5T82', '9898801505', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `mobilemoneytransfers`
--

CREATE TABLE `mobilemoneytransfers` (
  `transfer_id` varchar(50) NOT NULL,
  `sender_account_id` int(11) DEFAULT NULL,
  `recipient_mobile_number` varchar(15) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `transfer_date` datetime DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `recipient_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobilemoneytransfers`
--

INSERT INTO `mobilemoneytransfers` (`transfer_id`, `sender_account_id`, `recipient_mobile_number`, `amount`, `transfer_date`, `sender_name`, `recipient_name`) VALUES
('183146058671569', 2147483647, '8866590889', 500.00, '2024-04-11 07:50:54', 'bhavesh', 'vignesh'),
('219801062332470', 2147483647, '8866590889', 5.00, '2024-04-11 16:54:57', 'rakesh', 'bhavesh'),
('231493126045072', 2147483647, '8866590889', 5.00, '2024-04-11 16:53:35', 'rakesh', 'bhavesh'),
('267474034656902', 2147483647, '8866590889', 500.00, '2024-04-11 16:50:38', 'rakesh', 'bhavesh'),
('403877922612594', 2147483647, '8866590889', 500.00, '2024-04-11 16:53:12', 'rakesh', 'bhavesh'),
('437275109416941', 2147483647, '8866590889', 500.00, '2024-04-11 16:36:35', 'bhavesh', 'rak'),
('524245192885217', 2147483647, '9898801505', 5000.00, '2024-04-11 07:51:54', 'bhavesh', 'rakesh'),
('576043862087684', 2147483647, '8866590889', 400.00, '2024-04-11 16:46:43', 'rakesh', 'fgfbv'),
('578040937850126', 2147483647, '9898801505', 200.00, '2024-04-11 16:38:32', 'bhavesh', 'rakesh'),
('627859595423235', 2147483647, '8866590889', 400.00, '2024-04-11 16:46:46', 'rakesh', 'fgfbv'),
('673211992870548', 2147483647, '9898801505', 800.00, '2024-04-11 16:37:39', 'bhavesh', 'rakesh'),
('726040385585925', 2147483647, '9898801505', 800.00, '2024-04-11 16:37:52', 'bhavesh', 'rakesh'),
('873485110793320', 2147483647, '8866590889', 5.00, '2024-04-11 16:55:27', 'rakesh', 'bhavesh'),
('951846138756924', 2147483647, '8866590889', 5.00, '2024-04-11 16:54:47', 'rakesh', 'bhavesh'),
('978609349573638', 2147483647, '9898801505', 600.00, '2024-04-11 11:18:38', 'bhavesh', 'rakesh'),
('995882370949553', 2147483647, '8866590889', 5000.00, '2024-04-11 16:43:29', 'rakesh', 'bhavesh');

-- --------------------------------------------------------

--
-- Table structure for table `moneytransfers`
--

CREATE TABLE `moneytransfers` (
  `transfer_id` int(11) NOT NULL,
  `sender_account_id` int(11) DEFAULT NULL,
  `recipient_account_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `transfer_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `transfer_method` varchar(50) DEFAULT NULL,
  `transfer_reference` varchar(100) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `recipient_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `money_transer_accountno`
--

CREATE TABLE `money_transer_accountno` (
  `id` int(11) NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `date` varchar(50) NOT NULL,
  `time` time NOT NULL,
  `sender_account_no` varchar(255) DEFAULT NULL,
  `transaction_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `money_transer_accountno`
--

INSERT INTO `money_transer_accountno` (`id`, `account_no`, `amount`, `transaction_id`, `date`, `time`, `sender_account_no`, `transaction_time`) VALUES
(32, '38391357849', '1200', '878625625478332', '2024-04-11', '00:00:00', '105704914063', '2024-04-11 09:17:56'),
(33, '38391357849', '200', '643077268655819', '2024-04-11', '00:00:00', '105704914063', '2024-04-11 14:35:31'),
(34, '38391357849', '200', '874219585400908', '2024-04-11', '00:00:00', '816949134023', '2024-04-11 14:48:23');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `withdrawal_id` varchar(115) NOT NULL,
  `account_id` varchar(116) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `withdrawal_date` datetime DEFAULT NULL,
  `withdrawal_method` varchar(50) DEFAULT NULL,
  `withdrawal_reference` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`withdrawal_id`, `account_id`, `name`, `amount`, `withdrawal_date`, `withdrawal_method`, `withdrawal_reference`) VALUES
('171268503474380', '430829803017', '', 500.00, '2024-04-09 23:20:34', 'cheque', '2334564'),
('171275858634430', '245900279791', '', 500.00, '2024-04-10 19:46:26', 'cheque', '23456'),
('171275869382526', '245900279791', '', 500.00, '2024-04-10 19:48:13', 'cheque', '23456'),
('171276912698358', '430829803017', 'rakesh mali', 1.00, '2024-04-10 22:42:06', '345', '3245'),
('171276920518190', '430829803017', 'rakesh mali', 1.00, '2024-04-10 22:43:25', '345', '3245'),
('171276958833320', '430829803017', 'rakesh mali', 1.00, '2024-04-10 22:49:48', '345', '3245'),
('171281438113582', '105704914063', 'bhavesh', 50000.00, '2024-04-11 11:16:21', 'cheaue', '2345678876');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogin`
--
ALTER TABLE `adminlogin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`account_no`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`deposit_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`account_no`);

--
-- Indexes for table `mobilemoneytransfers`
--
ALTER TABLE `mobilemoneytransfers`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `moneytransfers`
--
ALTER TABLE `moneytransfers`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `money_transer_accountno`
--
ALTER TABLE `money_transer_accountno`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`withdrawal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlogin`
--
ALTER TABLE `adminlogin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `money_transer_accountno`
--
ALTER TABLE `money_transer_accountno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
