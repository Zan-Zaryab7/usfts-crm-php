-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2025 at 01:55 AM
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
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `company` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `tax_id` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `name`, `company`, `email`, `phone`, `tax_id`, `address`, `created_at`) VALUES
(1, 'C00001', 'John Doe', 'Acme Inc', 'john@acme.com', '1234567890', 'TAX123', '123 Main St', '2025-08-21 21:38:24'),
(2, 'C00002', 'Jane Smith', 'Tech Corp', 'jane@techcorp.com', '0987654321', 'TAX456', '45 Market St', '2025-08-21 21:38:24');

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
  `customer_id` int(11) NOT NULL,
  `status` enum('Open','Won','Lost') DEFAULT 'Open',
  `total_price` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `product` varchar(150) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) DEFAULT 0.00,
  `cost_price` decimal(12,2) DEFAULT 0.00,
  `catalog_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Sales','Finance','Auditor') NOT NULL DEFAULT 'Sales',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Admin', '2025-08-21 21:38:24'),
(2, 'sales1', '0ad80eb119d9bf7775aa23786b05b391', 'Sales', '2025-08-21 21:38:24'),
(3, 'finance', 'b9c9b331a8a5007cb2b766c6cd293372', 'Finance', '2025-08-21 21:38:24'),
(4, 'auditor', 'd33542b8458db8cabd9843fe7c1e8784', 'Auditor', '2025-08-21 21:38:24');

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
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `rfq_lines`
--
ALTER TABLE `rfq_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rfq_id` (`rfq_id`);

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
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_lines`
--
ALTER TABLE `order_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rfqs`
--
ALTER TABLE `rfqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rfq_lines`
--
ALTER TABLE `rfq_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `rfqs`
--
ALTER TABLE `rfqs`
  ADD CONSTRAINT `rfqs_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rfq_lines`
--
ALTER TABLE `rfq_lines`
  ADD CONSTRAINT `rfq_lines_ibfk_1` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
