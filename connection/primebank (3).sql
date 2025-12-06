-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 12:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
('105704914063', '182375'),
('38391357849', '10000001600'),
('396220884838', '395413470566'),
('507240402784', '0'),
('565557711259', '0'),
('770694933012', '0'),
('807370691903', '990807238717'),
('816949134023', '380'),
('890025385548', '0'),
('957996884626', '0');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `beneficiary_id` int(11) NOT NULL,
  `user_account_no` varchar(50) NOT NULL,
  `beneficiary_name` varchar(100) NOT NULL,
  `beneficiary_account_no` varchar(50) NOT NULL,
  `beneficiary_ifsc` varchar(20) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `card_id` int(11) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `card_number` varchar(20) NOT NULL,
  `card_type` enum('Debit','Credit') NOT NULL,
  `expiry_date` varchar(7) NOT NULL COMMENT 'Format MM/YY',
  `cvv` int(4) NOT NULL,
  `card_limit` decimal(10,2) DEFAULT 0.00,
  `status` enum('Active','Blocked','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `debit`
--

CREATE TABLE `debit` (
  `id` int(11) NOT NULL,
  `account_no` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `fixed_deposits`
--

CREATE TABLE `fixed_deposits` (
  `fd_id` int(11) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `start_date` date NOT NULL,
  `maturity_date` date NOT NULL,
  `maturity_amount` decimal(15,2) NOT NULL,
  `status` enum('Active','Matured','Closed') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `loan_type` varchar(50) NOT NULL COMMENT 'Home, Personal, Car',
  `loan_amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `emi_amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('Active','Closed','Pending') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('396220884838', 'rakesh mali', 'mali', '', 'rakeshmali4619@gmail.com', '$2y$10$1Z3.M7LLv10fbehzoVeyO.dC36SjG/0S/hyoThV29YvKyb6aYDcw2', '7600404831', 'sai krupa -4, prima appt room no.03, mashal chowk, airport road nani daman 396210', 'Meghalaya', '396210', 'daman & diu'),
('507240402784', 'rakesh', 'mali', '', 'rakeshmali46519@gmail.com', '$2y$10$BBZKk0.Pl.MMCFJR6AX34.H2rTTZeLLImlaUV8OcBiDiwe03IPP5y', '0760040483', 'Daman', 'DAMAN & DIU', '396210', 'Daman'),
('565557711259', 'rakesh', 'mali', '', 'rakeshmali46519@gmail.com', '$2y$10$DnxB.eUVj/vKLXwfW7oy9.sjPOy0IH.6TeuHI9He5gFJnYzoDd8NC', '0760040483', 'Daman', 'DAMAN & DIU', '396210', 'Daman'),
('770694933012', 'rakesh', 'mali', '', 'rakeshmali46519@gmail.com', '$2y$10$kOkaQOzEUPvQDmzBh3HwIu8diMOo2Ge9DTCLyS61kJjpPQ4HLz.qi', '0760040483', 'Daman', 'DAMAN & DIU', '396210', 'Daman'),
('807370691903', 'rakesh mali', '', '', 'rakeshmali46519@gmail.com', '$2y$10$MGhHJO5Y6bN5nRDG10iZuuDmd6e60XS.Jiqe9iGvNH6JvG4R5O/kG', '7600404831', '', '', '', ''),
('890025385548', 'rakesh', 'mali', '', 'rakeshmali46519@gmail.com', '$2y$10$DUesvEAh4UATy9/9XATht.p.Fv77VktYQDvEEyLAk.6gZIFcnTMKC', '0760040483', 'Daman', 'DAMAN & DIU', '396210', 'Daman'),
('957996884626', 'rakesh', 'mali', '', 'rakeshmali46519@gmail.com', '$2y$10$an22CXfSCPhe8RqYq7ezbugyluyfDCXi8ouEvKteb2FA0ZnUh.jvO', '0760040483', 'Daman', 'DAMAN & DIU', '396210', 'Daman');

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `history_id` int(11) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `status` enum('Success','Failed') DEFAULT 'Success'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `message`, `type`, `date`) VALUES
(1, 'New Customer Created: 565557711259', 'SUCCESS', '2025-12-05 14:21:38'),
(2, 'New Customer Created: 890025385548', 'SUCCESS', '2025-12-05 14:22:24'),
(3, 'New Customer Created: 957996884626', 'SUCCESS', '2025-12-05 14:22:50');

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

--
-- Dumping data for table `moneytransfers`
--

INSERT INTO `moneytransfers` (`transfer_id`, `sender_account_id`, `recipient_account_id`, `amount`, `transfer_date`, `status`, `transfer_method`, `transfer_reference`, `sender_name`, `recipient_name`) VALUES
(2147483647, 2147483647, 2147483647, 15000000.00, '2025-12-04 11:27:07', 'Success', 'Bank Transfer', 'N/A', 'rakesh mali', 'rakesh mali');

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
(34, '38391357849', '200', '874219585400908', '2024-04-11', '00:00:00', '816949134023', '2024-04-11 14:48:23'),
(35, '105704914063', '15000', '456815371467479', '2025-12-04', '00:00:00', '807370691903', '2025-12-04 10:01:25'),
(36, '105704914063', '150555', '845407253040716', '2025-12-04', '00:00:00', '807370691903', '2025-12-04 10:09:40'),
(37, '38391357849', '10000000000', '578087755607507', '2025-12-04', '00:00:00', '807370691903', '2025-12-04 10:09:54');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('Alert','Offer','Security','Transaction') DEFAULT 'Alert',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `staff_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `ticket_id` int(11) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Open','In Progress','Resolved','Closed') DEFAULT 'Open',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_extended_details`
--

CREATE TABLE `user_extended_details` (
  `id` int(11) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `kyc_status` enum('Pending','Verified','Rejected') DEFAULT 'Pending',
  `account_type` enum('Savings','Current','Business') DEFAULT 'Savings',
  `branch_name` varchar(100) DEFAULT NULL,
  `branch_ifsc` varchar(20) DEFAULT NULL,
  `nominee_name` varchar(100) DEFAULT NULL,
  `nominee_relation` varchar(50) DEFAULT NULL,
  `account_open_date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`beneficiary_id`),
  ADD KEY `user_account_no` (`user_account_no`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`card_id`),
  ADD KEY `account_no` (`account_no`);

--
-- Indexes for table `debit`
--
ALTER TABLE `debit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`deposit_id`);

--
-- Indexes for table `fixed_deposits`
--
ALTER TABLE `fixed_deposits`
  ADD PRIMARY KEY (`fd_id`),
  ADD KEY `account_no` (`account_no`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `account_no` (`account_no`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`account_no`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `account_no` (`account_no`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `account_no` (`account_no`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `account_no` (`account_no`);

--
-- Indexes for table `user_extended_details`
--
ALTER TABLE `user_extended_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_no` (`account_no`);

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
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `beneficiary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debit`
--
ALTER TABLE `debit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fixed_deposits`
--
ALTER TABLE `fixed_deposits`
  MODIFY `fd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `money_transer_accountno`
--
ALTER TABLE `money_transer_accountno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_extended_details`
--
ALTER TABLE `user_extended_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
