-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 01:11 AM
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
-- Database: `crm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) DEFAULT NULL,
  `entity` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billto`
--

CREATE TABLE `billto` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billto`
--

INSERT INTO `billto` (`id`, `code`, `title`, `address`, `created_at`) VALUES
(2, 'BT00001', 'NCI Agency Headquarters', 'Boulevard Leopold III, 1110\r\nBrussels, Belgium', '2025-09-03 20:51:27');

--
-- Triggers `billto`
--
DELIMITER $$
CREATE TRIGGER `trg_billto_code` BEFORE INSERT ON `billto` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('BT', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM billTo),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `buyer`
--

CREATE TABLE `buyer` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buyer`
--

INSERT INTO `buyer` (`id`, `code`, `name`, `email`, `phone`, `address`, `created_at`) VALUES
(2, 'B00001', 'Bob', 'bob@gmail.com', '+1 654 234 5533', 'Blizzardian, Dion Foe, #123', '2025-09-03 21:19:21');

--
-- Triggers `buyer`
--
DELIMITER $$
CREATE TRIGGER `trg_buyer_code` BEFORE INSERT ON `buyer` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('B', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM buyer),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `name`, `title`, `email`, `phone`, `address`, `created_at`) VALUES
(1, 'C00001', 'John Doe', 'Acme Inc', 'john@acme.com', '1234567890', '123 Main St', '2025-08-21 21:38:24'),
(2, 'C00002', 'Jane Smith', 'Tech Corp', 'jane@techcorp.com', '0987654321', '45 Market St', '2025-08-21 21:38:24'),
(11, 'C00003', 'Mark', 'Markus', 'mark@gmail.com', '1234567890', 'Abc, Xyz #985', '2025-08-23 18:48:55'),
(14, 'C00012', 'Will', 'Artist', 'will@gmail.com', '0123456789', 'William Shakesphern 123', '2025-09-02 23:08:51');

--
-- Triggers `customers`
--
DELIMITER $$
CREATE TRIGGER `trg_customers_code` BEFORE INSERT ON `customers` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('C', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM customers),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `status` enum('Draft','Sent','Paid','Overdue') DEFAULT 'Draft',
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `invoices`
--
DELIMITER $$
CREATE TRIGGER `trg_invoices_code` BEFORE INSERT ON `invoices` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('I', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM invoices),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `from_email` varchar(255) NOT NULL,
  `to_email` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sale_id`, `from_email`, `to_email`, `message`, `created_at`) VALUES
(1, 18, 'admin@gmail.com', 'Mark@gmail.com', 'Mark check your sale...', '2025-08-26 21:39:51'),
(2, 18, 'admin@gmail.com', 'mark@gmail.com', 'Okay now do whats needed,\r\n\r\nDid you understood what I explained just now?\r\n\r\nIf no, then don\'t touch anything.', '2025-08-26 21:43:37'),
(3, 18, 'admin2@gmail.com', 'mark@gmail.com', 'Mark note this:\r\n\r\nDon\'t make any mistakes.\r\nDon\'t do anything if you not know about that.\r\n\r\nThank you.', '2025-08-26 22:09:14'),
(4, 18, 'admin2@gmail.com', 'mark@gmail.com', 'Got it!', '2025-08-26 22:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `rfq_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `status` enum('Pending','Delivered','Completed') DEFAULT 'Pending',
  `tracking_number` varchar(100) DEFAULT NULL,
  `total_cost` decimal(12,2) DEFAULT 0.00,
  `total_price` decimal(12,2) DEFAULT 0.00,
  `profit` decimal(12,2) DEFAULT 0.00,
  `margin` decimal(6,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `trg_orders_code` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('O', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM orders),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_lines`
--

CREATE TABLE `order_lines` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product` varchar(150) DEFAULT NULL,
  `qty` int(11) DEFAULT 1,
  `unit_price` decimal(12,2) DEFAULT 0.00,
  `cost_price` decimal(12,2) DEFAULT 0.00,
  `tracking_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sales_price` decimal(10,2) DEFAULT 0.00,
  `cost_price` decimal(10,2) DEFAULT 0.00,
  `catalog_file` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `sales_price`, `cost_price`, `catalog_file`, `created_at`) VALUES
(11, 'P00001', 'Prod 1', 342.00, 255.23, 'uploads/catalogs/1756239490_invoice.pdf', '2025-08-26 20:18:10'),
(12, 'P00012', 'Prod 2', 565.53, 246.33, 'uploads/catalogs/1756239541_invoice.pdf', '2025-08-26 20:19:01'),
(13, 'P00013', 'Prod 3', 687.54, 354.23, NULL, '2025-08-26 20:19:28'),
(14, 'P00014', 'Prod 4', 5743.54, 3556.42, 'uploads/catalogs/1756239604_invoice.pdf', '2025-08-26 20:20:04'),
(15, 'P00015', 'Prod 5', 123.12, 111.11, NULL, '2025-08-26 20:28:15'),
(16, 'P00016', 'Prod 6', 345.00, 234.21, 'uploads/catalogs/1756240119_invoice.pdf', '2025-08-26 20:28:39');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `trg_products_code` BEFORE INSERT ON `products` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('P', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM products),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rfqs`
--

CREATE TABLE `rfqs` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `rfq_number` varchar(50) DEFAULT NULL,
  `rfq_title` varchar(150) DEFAULT NULL,
  `quote_date` date DEFAULT NULL,
  `validity` varchar(50) DEFAULT '5 Months',
  `lead_time` int(11) DEFAULT 0,
  `shipping` decimal(12,2) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `salesPerson_id` int(11) DEFAULT NULL,
  `billTo_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `shipTo_id` int(11) DEFAULT NULL,
  `status` enum('Open','Won','Lost') DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rfqs`
--

INSERT INTO `rfqs` (`id`, `code`, `rfq_number`, `rfq_title`, `quote_date`, `validity`, `lead_time`, `shipping`, `customer_id`, `salesPerson_id`, `billTo_id`, `buyer_id`, `shipTo_id`, `status`, `created_at`) VALUES
(23, 'R00023', '4252', 'RFQ MAIN', '2025-09-04', '5 Months', 5, 0.00, 2, 4, 2, 2, 2, 'Open', '2025-09-04 17:03:51'),
(27, 'R00024', '4564', 'Rfq', '2025-09-06', '2 Months', 8, 0.00, 2, 4, 2, 2, 3, 'Open', '2025-09-04 19:39:58'),
(28, 'R00028', '9789', 'Rfq Pdf', '2025-09-08', '1 Months', 10, 45.79, 14, 4, 2, 2, 2, 'Open', '2025-09-04 19:58:22');

--
-- Triggers `rfqs`
--
DELIMITER $$
CREATE TRIGGER `trg_rfqs_code` BEFORE INSERT ON `rfqs` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('R', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM rfqs),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rfq_lines`
--

CREATE TABLE `rfq_lines` (
  `id` int(11) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit` varchar(50) DEFAULT 'Each',
  `part` varchar(100) DEFAULT NULL,
  `mfg` varchar(100) DEFAULT NULL,
  `coo` varchar(50) DEFAULT NULL,
  `eccn` varchar(50) DEFAULT NULL,
  `cust` varchar(100) DEFAULT NULL,
  `htsus` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unit_price` decimal(12,2) DEFAULT 0.00,
  `total_price` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rfq_lines`
--

INSERT INTO `rfq_lines` (`id`, `rfq_id`, `qty`, `unit`, `part`, `mfg`, `coo`, `eccn`, `cust`, `htsus`, `description`, `unit_price`, `total_price`) VALUES
(6, 23, 2, 'Each', '5383-TW-12-ND', 'Maury Microwave / TW-12', 'UNKNOWN', 'EAR99', 'NATO PO NO. 42521602', '9030.89.0100', 'Torque Wrench ', 358.00, 1432.00),
(9, 27, 4, 'Each', '5383-TW-12-ND', 'Maury Microwave / TW-12', 'UNKNOWN', 'EAR99', 'NATO PO NO. 42521602', '9030.89.0100', 'Torque Wrench ', 235.00, 1235.00),
(12, 28, 5, 'Each', '5383-TW-12-ND', 'Maury Microwave / TW-12', 'UNKNOWN', 'EAR99', 'NATO PO NO. 42521602', '9030.89.0100', 'Torque Wrench ', 375.00, 1250.00);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `bill_to` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `delivery_in` varchar(255) DEFAULT NULL,
  `rfq_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `code`, `bill_to`, `customer_id`, `delivery_in`, `rfq_id`, `title`, `expiration`, `created_at`) VALUES
(18, 'S00001', 'Default Company', 11, '12 Days', NULL, 'Sales RFQ 1', '2025-09-08', '2025-08-26 20:36:56'),
(19, 'S00019', 'Default Company', 1, '4 Days', NULL, 'Sales RFQ 1-2', '2025-08-30', '2025-08-26 20:38:56');

--
-- Triggers `sales`
--
DELIMITER $$
CREATE TRIGGER `trg_sales_code` BEFORE INSERT ON `sales` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('S', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM sales),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `salesperson`
--

CREATE TABLE `salesperson` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salesperson`
--

INSERT INTO `salesperson` (`id`, `code`, `name`, `title`, `email`, `phone`, `signature`, `created_at`) VALUES
(4, 'SP00001', 'Alice', 'Procurement Manager', 'bids@usfts.us', '+1 619 918 5090', 'uploads/signatures/1756930450_alice-signature.png', '2025-09-03 20:14:10');

--
-- Triggers `salesperson`
--
DELIMITER $$
CREATE TRIGGER `trg_salesPerson_code` BEFORE INSERT ON `salesperson` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('SP', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM salesPerson),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_lines`
--

CREATE TABLE `sale_lines` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `catalog_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_lines`
--

INSERT INTO `sale_lines` (`id`, `sale_id`, `product_id`, `qty`, `unit_price`, `cost_price`, `catalog_file`) VALUES
(32, 18, 11, 2, 342.00, 255.23, 'uploads/catalogs/1756239490_invoice.pdf'),
(33, 18, 13, 5, 687.54, 354.23, ''),
(34, 18, 14, 2, 5743.54, 3556.42, 'uploads/catalogs/1756239604_invoice.pdf'),
(35, 18, 16, 6, 345.00, 234.21, 'uploads/catalogs/1756240119_invoice.pdf'),
(36, 19, 13, 7, 687.54, 354.23, '');

-- --------------------------------------------------------

--
-- Table structure for table `shipto`
--

CREATE TABLE `shipto` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `company` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipto`
--

INSERT INTO `shipto` (`id`, `code`, `name`, `company`, `email`, `phone`, `address`, `created_at`) VALUES
(2, 'ST00002', 'Emily Johnson', 'Global Trade Ltd.', 'emily.j@globaltrade.com', '+1-555-5678', '456 Market Road, Los Angeles, CA 90015', '2025-09-03 21:06:46'),
(3, 'ST00003', 'Michael Brown', 'Tech Supplies Inc.', 'm.brown@techsupplies.com', '+1-555-9012', '789 Silicon Valley Blvd, San Jose, CA 95110', '2025-09-03 21:06:46'),
(4, 'ST00004', 'Sophia Davis', 'Logistics Partners', 'sophia.davis@logistics.com', '+1-555-3456', '321 Industrial Park, Dallas, TX 75201', '2025-09-03 21:06:46'),
(5, 'ST00005', 'David Wilson', 'GreenWorld Exporters', 'd.wilson@greenworld.com', '+1-555-7890', '654 River Road, Miami, FL 33101', '2025-09-03 21:06:46');

--
-- Triggers `shipto`
--
DELIMITER $$
CREATE TRIGGER `trg_shipto_code` BEFORE INSERT ON `shipto` FOR EACH ROW BEGIN
    IF NEW.code IS NULL OR NEW.code = '' THEN
        SET NEW.code = CONCAT('ST', LPAD((SELECT IFNULL(MAX(id),0)+1 FROM shipTo),5,'0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Sales','Finance','Auditor') NOT NULL DEFAULT 'Sales',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'Admin', '2025-08-21 21:38:24'),
(2, 'sales1', '', '0ad80eb119d9bf7775aa23786b05b391', 'Sales', '2025-08-21 21:38:24'),
(3, 'finance', '', 'b9c9b331a8a5007cb2b766c6cd293372', 'Finance', '2025-08-21 21:38:24'),
(4, 'auditor', '', 'd33542b8458db8cabd9843fe7c1e8784', 'Auditor', '2025-08-21 21:38:24'),
(5, 'Admin2', 'admin2@gmail.com', '0192023a7bbd73250516f069df18b500', 'Admin', '2025-08-26 22:05:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `billto`
--
ALTER TABLE `billto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `rfq_id` (`rfq_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_lines`
--
ALTER TABLE `order_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `rfqs`
--
ALTER TABLE `rfqs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `rfq_lines`
--
ALTER TABLE `rfq_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rfq_id` (`rfq_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `rfq_id` (`rfq_id`);

--
-- Indexes for table `salesperson`
--
ALTER TABLE `salesperson`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_lines`
--
ALTER TABLE `sale_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shipto`
--
ALTER TABLE `shipto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billto`
--
ALTER TABLE `billto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `buyer`
--
ALTER TABLE `buyer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_lines`
--
ALTER TABLE `order_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rfqs`
--
ALTER TABLE `rfqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `rfq_lines`
--
ALTER TABLE `rfq_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `salesperson`
--
ALTER TABLE `salesperson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sale_lines`
--
ALTER TABLE `sale_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `shipto`
--
ALTER TABLE `shipto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_lines`
--
ALTER TABLE `order_lines`
  ADD CONSTRAINT `order_lines_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rfq_lines`
--
ALTER TABLE `rfq_lines`
  ADD CONSTRAINT `rfq_lines_ibfk_1` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sale_lines`
--
ALTER TABLE `sale_lines`
  ADD CONSTRAINT `sale_lines_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_lines_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
