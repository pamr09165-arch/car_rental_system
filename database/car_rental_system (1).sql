-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2026 at 06:49 PM
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
  `amount` decimal(10,2) NOT NULL CHECK (`amount` >= 0),
  `method` varchar(30) NOT NULL CHECK (`method` in ('credit_card','promptpay','wallet','bank_transfer')),
  `status` varchar(20) NOT NULL DEFAULT 'pending' CHECK (`status` in ('pending','paid','failed','refunded')),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `trg_payments_uuid` BEFORE INSERT ON `payments` FOR EACH ROW BEGIN
    IF NEW.id IS NULL OR NEW.id = '' THEN
        SET NEW.id = UUID();
    END IF;
END
$$
DELIMITER ;

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
('11bc60cb-2eec-4316-8546-598da3b89c9a', '0ffc3371-8385-11f1-aae2-2c3b709563e6', 'Toyota', 'Yaris', 2007, 'กข2024', 5000.00, 2000.00, 'กรุงเทพ', 'car_6a5d1f781cc745.25991877.jpg', 'available', '2026-07-19 18:46:17', '2026-07-19 19:03:20');

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
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `idx_payments_booking_id` (`booking_id`);

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
  ADD CONSTRAINT `fk_bookings_renter` FOREIGN KEY (`renter_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_bookings_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Constraints for table `inspections`
--
ALTER TABLE `inspections`
  ADD CONSTRAINT `fk_inspections_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `fk_vehicles_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
