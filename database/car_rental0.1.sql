-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2026 at 01:12 PM
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
-- Database: `car_rental_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` char(36) NOT NULL,
  `renter_id` char(36) NOT NULL,
  `vehicle_id` char(36) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `return_location` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL CHECK (`total_price` >= 0),
  `status` varchar(20) NOT NULL DEFAULT 'pending' CHECK (`status` in ('pending','confirmed','in_progress','completed','cancelled','rejected')),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `renter_id`, `vehicle_id`, `start_date`, `end_date`, `pickup_location`, `return_location`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
('24d548eb-1703-40c2-a6ad-94a66bdcbd33', '839a3727-0d47-4265-afd7-8a139d770873', '656862a2-bece-4d25-aeb9-041dd5cf3a88', '2026-07-24', '2026-07-30', '', '', 14000.00, 'pending', '2026-07-23 10:47:14', '2026-07-23 10:47:14');

--
-- Triggers `bookings`
--
DELIMITER $$
CREATE TRIGGER `trg_bookings_uuid` BEFORE INSERT ON `bookings` FOR EACH ROW BEGIN
    IF NEW.id IS NULL OR NEW.id = '' THEN
        SET NEW.id = UUID();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` char(36) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `id_card_no` varchar(20) NOT NULL,
  `driver_license_no` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `id_card_image` varchar(255) DEFAULT NULL,
  `license_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `phone`, `email`, `id_card_no`, `driver_license_no`, `address`, `id_card_image`, `license_image`, `created_at`, `updated_at`) VALUES
('839a3727-0d47-4265-afd7-8a139d770873', 'ภัทรดนัย บุญยัง', '0123456789', '69319100034@phuketvc.ac.th', '1800400395116', '12000000000000', '--', 'idcard_6a61f11bf1bd65.14691330.png', 'license_6a61f11bf1f6e6.33821079.png', '2026-07-23 10:46:51', '2026-07-23 10:46:51');

-- --------------------------------------------------------

--
-- Table structure for table `inspections`
--

CREATE TABLE `inspections` (
  `id` char(36) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `type` varchar(20) NOT NULL CHECK (`type` in ('check_in','check_out')),
  `photo_urls` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `inspected_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `inspections`
--
DELIMITER $$
CREATE TRIGGER `trg_inspections_uuid` BEFORE INSERT ON `inspections` FOR EACH ROW BEGIN
    IF NEW.id IS NULL OR NEW.id = '' THEN
        SET NEW.id = UUID();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` char(36) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `method` enum('cash','credit_card','promptpay','wallet','bank_transfer') NOT NULL,
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `amount`, `method`, `status`, `paid_at`, `created_at`) VALUES
('e3157411-5106-5b10-9af6-ba822c106e29', '24d548eb-1703-40c2-a6ad-94a66bdcbd33', 14000.00, 'wallet', 'pending', '2026-07-24 07:00:00', '2026-07-23 11:05:20');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` char(36) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `rating` smallint(6) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `reviews`
--
DELIMITER $$
CREATE TRIGGER `trg_reviews_uuid` BEFORE INSERT ON `reviews` FOR EACH ROW BEGIN
    IF NEW.id IS NULL OR NEW.id = '' THEN
        SET NEW.id = UUID();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'renter' CHECK (`role` in ('renter','owner','admin','support')),
  `id_card_no` varchar(30) DEFAULT NULL,
  `license_no` varchar(30) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `role`, `id_card_no`, `license_no`, `is_verified`, `created_at`, `updated_at`) VALUES
('0ffc3371-8385-11f1-aae2-2c3b709563e6', 'Admin', 'admin@gmail.com', '0918145645', '$2y$10$dxstCxt1qLmIbq5sXxixIO/cuBigWmSPdFJz9XGxPtSboEB1D7rou', 'admin', '1234567890123', 'D123456789', 1, '2026-07-19 15:18:14', '2026-07-19 15:28:45');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `trg_users_uuid` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.id IS NULL OR NEW.id = '' THEN
        SET NEW.id = UUID();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` char(36) NOT NULL,
  `owner_id` char(36) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` smallint(6) DEFAULT NULL,
  `plate_no` varchar(20) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL CHECK (`price_per_day` >= 0),
  `deposit_amount` decimal(10,2) DEFAULT 0.00,
  `location` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'available' CHECK (`status` in ('available','rented','maintenance','inactive')),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `owner_id`, `brand`, `model`, `year`, `plate_no`, `price_per_day`, `deposit_amount`, `location`, `image`, `status`, `created_at`, `updated_at`) VALUES
('656862a2-bece-4d25-aeb9-041dd5cf3a88', '0ffc3371-8385-11f1-aae2-2c3b709563e6', 'lembo', 's100', 2025, 'รวย888', 2000.00, 20000.00, 'ภูเก็ต', 'car_6a61f0eb035224.82679277.jpg', 'rented', '2026-07-23 10:46:03', '2026-07-23 10:47:14'),
('9f1a29e0-5f11-4e3f-9bef-83e7ee801399', '0ffc3371-8385-11f1-aae2-2c3b709563e6', 'Toyota', 'Yaris', 2008, 'กข2024', 500.00, 2000.00, 'ภูเก็ต', 'car_6a61f0950786b5.54813550.jpg', 'available', '2026-07-23 10:44:37', '2026-07-23 10:44:37'),
('a6bd82aa-f086-486f-af61-214958f55e8e', '0ffc3371-8385-11f1-aae2-2c3b709563e6', 'Toyota', 'Yaris', 2007, 'กล1819', 500.00, 2000.00, 'ภูเก็ต', 'car_6a61f0b3b82ab3.55934550.jpg', 'available', '2026-07-23 10:45:07', '2026-07-23 10:45:07');

--
-- Triggers `vehicles`
--
DELIMITER $$
CREATE TRIGGER `trg_vehicles_uuid` BEFORE INSERT ON `vehicles` FOR EACH ROW BEGIN
    IF NEW.id IS NULL OR NEW.id = '' THEN
        SET NEW.id = UUID();
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bookings_renter_id` (`renter_id`),
  ADD KEY `idx_bookings_vehicle_id` (`vehicle_id`),
  ADD KEY `idx_bookings_status` (`status`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_card_no` (`id_card_no`),
  ADD UNIQUE KEY `driver_license_no` (`driver_license_no`);

--
-- Indexes for table `inspections`
--
ALTER TABLE `inspections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_inspections_booking_id` (`booking_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_payment_booking` (`booking_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `idx_reviews_booking_id` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_no` (`plate_no`),
  ADD KEY `idx_vehicles_owner_id` (`owner_id`),
  ADD KEY `idx_vehicles_status` (`status`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_booking_customer` FOREIGN KEY (`renter_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `inspections`
--
ALTER TABLE `inspections`
  ADD CONSTRAINT `fk_inspection_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payment_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_review_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `fk_vehicle_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
