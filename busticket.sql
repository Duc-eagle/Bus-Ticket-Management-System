-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 06:08 AM
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
-- Database: `busticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `license_plate` varchar(255) NOT NULL,
  `bus_name` varchar(255) NOT NULL,
  `total_seats` int(11) NOT NULL,
  `has_beds` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `license_plate`, `bus_name`, `total_seats`, `has_beds`, `created_at`, `updated_at`) VALUES
(1, '29B-999.99', 'Limousine Vip', 30, 0, '2026-04-21 18:18:33', '2026-04-21 18:18:33');

-- --------------------------------------------------------

--
-- Table structure for table `bus_stations`
--

CREATE TABLE `bus_stations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_id` bigint(20) UNSIGNED NOT NULL,
  `station_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_stations`
--

INSERT INTO `bus_stations` (`id`, `province_id`, `station_name`, `address`, `phone`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bến Xe Mỹ Đình', 'abc', '0123456789', '2026-04-21 18:17:01', '2026-04-21 18:17:01'),
(2, 2, 'Bến Xe Bắc Hải Phòng', 'Tỉnh Lộ 359C, Kênh Giang, Thủy Nguyên, Hải Phòng', '0772.369.769', '2026-04-21 18:17:39', '2026-04-21 18:17:39');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '0001_01_01_000003_create_provinces_table', 1),
(5, '0001_01_01_000004_create_bus_stations_table', 1),
(6, '0001_01_01_000005_create_routes_table', 1),
(7, '0001_01_01_000007_create_buses_table', 1),
(8, '0001_01_01_000008_create_seats_table', 1),
(9, '0001_01_01_000009_create_trips_table', 1),
(10, '0001_01_01_000010_create_payment_methods_table', 1),
(11, '0001_01_01_000011_create_tickets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `method_name` varchar(255) NOT NULL,
  `method_description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `method_name`, `method_description`, `created_at`, `updated_at`) VALUES
(1, 'ZaloPay', 'Thanh toán nhanh qua ứng dụng ZaloPay. Miễn phí chuyển tiền, an toàn 100%', '2026-05-07 19:04:06', '2026-05-07 19:04:06'),
(2, 'VNPay', 'Thanh toán qua VNPay. Hỗ trợ thẻ tín dụng, thẻ ghi nợ, tài khoản ngân hàng', '2026-05-07 19:04:25', '2026-05-07 19:04:25'),
(3, 'Momo', 'Thanh toán qua ứng dụng Momo. Nhanh chóng, bảo mật và tiện lợi', '2026-05-07 19:04:40', '2026-05-07 19:04:40'),
(4, 'Bank Transfer', 'Chuyển khoản trực tiếp. Vui lòng liên hệ với chúng tôi để nhận thông tin tài khoản', '2026-05-07 19:04:58', '2026-05-07 19:04:58'),
(5, 'Credit Card', 'Thanh toán bằng thẻ tín dụng quốc tế (Visa, Mastercard). An toàn với mã hóa SSL', '2026-05-07 19:05:16', '2026-05-07 19:05:16'),
(6, 'PayPal', 'Thanh toán quốc tế an toàn qua PayPal', '2026-05-07 19:05:34', '2026-05-07 19:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `province_name`, `created_at`, `updated_at`) VALUES
(1, 'Hà Nội', '2026-04-21 18:16:36', '2026-04-21 18:16:36'),
(2, 'Hải Phòng', '2026-04-21 18:16:43', '2026-04-21 18:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `departure_location` bigint(20) UNSIGNED NOT NULL,
  `arrival_location` bigint(20) UNSIGNED NOT NULL,
  `distance` int(11) NOT NULL,
  `estimate_time` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `route_name`, `departure_location`, `arrival_location`, `distance`, `estimate_time`, `created_at`, `updated_at`) VALUES
(1, 'Hà Nội - Hải Phòng', 1, 2, 120, '3 giờ', '2026-04-21 18:18:18', '2026-04-21 18:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bus_id` bigint(20) UNSIGNED NOT NULL,
  `seat_code` varchar(255) NOT NULL,
  `status` enum('available','booked') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `bus_id`, `seat_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'A1', 'available', '2026-04-21 18:20:08', '2026-04-21 18:20:08'),
(2, 1, 'A2', 'available', '2026-05-08 18:50:53', '2026-05-08 18:50:53'),
(3, 1, 'A3', 'available', '2026-05-08 18:53:21', '2026-05-08 18:53:21'),
(4, 1, 'A4', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(5, 1, 'A5', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(6, 1, 'A6', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(7, 1, 'A7', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(8, 1, 'A8', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(9, 1, 'A9', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(10, 1, 'A10', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(11, 1, 'A11', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(12, 1, 'A12', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(13, 1, 'A13', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(14, 1, 'A14', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(15, 1, 'A15', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(16, 1, 'A16', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(17, 1, 'A17', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(18, 1, 'A18', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(19, 1, 'A19', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(20, 1, 'A20', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(21, 1, 'A21', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(22, 1, 'A22', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(23, 1, 'A23', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(24, 1, 'A24', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(25, 1, 'A25', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(26, 1, 'A26', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(27, 1, 'A27', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(28, 1, 'A28', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(29, 1, 'A29', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04'),
(30, 1, 'A30', 'available', '2026-05-09 01:56:04', '2026-05-09 01:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_name` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `seat_id` bigint(20) UNSIGNED NOT NULL,
  `trip_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('confirmed','pending_payment','paid','cancelled') NOT NULL DEFAULT 'confirmed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trip_name` varchar(255) NOT NULL,
  `bus_id` bigint(20) UNSIGNED NOT NULL,
  `route_id` bigint(20) UNSIGNED NOT NULL,
  `trip_date` date NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `status` enum('scheduled','running','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `trip_name`, `bus_id`, `route_id`, `trip_date`, `departure_time`, `arrival_time`, `base_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hà Nội - Hải Phòng', 1, 1, '2026-04-23', '07:00:00', '11:00:00', 300000.00, 'completed', '2026-04-21 18:21:15', '2026-05-05 00:21:47'),
(2, 'Hà Nội - Hải Phòng', 1, 1, '2026-04-28', '06:30:00', '08:00:00', 150000.00, 'completed', '2026-04-27 01:06:57', '2026-04-28 08:49:06'),
(3, 'Hà Nội - Hải Phòng', 1, 1, '2026-05-06', '06:30:00', '08:30:00', 200000.00, 'completed', '2026-05-05 00:20:54', '2026-05-07 19:17:00'),
(4, 'Hà Nội - Hải Phòng', 1, 1, '2026-05-09', '06:30:00', '08:30:00', 200000.00, 'scheduled', '2026-05-07 19:17:57', '2026-05-07 19:17:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `user_name`, `email`, `phone`, `password`, `dob`, `address`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Thế Duyệt', 'duyet', 'duyet@gmail.com', NULL, '$2y$12$SB3BC/v9mZ0VKr4L4Vqxo.ZmN3OUfNcPL2/EQUYN879hkmEYZ9D26', NULL, NULL, 'customer', NULL, '2026-05-04 08:26:17'),
(2, 'Mai Trung Đức', 'Duc', 'c975246@gmail.com', NULL, '$2y$12$3gTrKffyQH16x4nyzHBl9OktlzHu18VIIWe1LYhP.1Vo0pHqfp7vq', NULL, NULL, 'admin', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buses_license_plate_unique` (`license_plate`);

--
-- Indexes for table `bus_stations`
--
ALTER TABLE `bus_stations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_stations_province_id_foreign` (`province_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_method_name_unique` (`method_name`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provinces_province_name_unique` (`province_name`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `routes_departure_location_foreign` (`departure_location`),
  ADD KEY `routes_arrival_location_foreign` (`arrival_location`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `seats_bus_id_seat_code_unique` (`bus_id`,`seat_code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_user_id_foreign` (`user_id`),
  ADD KEY `tickets_seat_id_foreign` (`seat_id`),
  ADD KEY `tickets_trip_id_foreign` (`trip_id`),
  ADD KEY `tickets_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trips_bus_id_foreign` (`bus_id`),
  ADD KEY `trips_route_id_foreign` (`route_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_user_name_unique` (`user_name`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bus_stations`
--
ALTER TABLE `bus_stations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bus_stations`
--
ALTER TABLE `bus_stations`
  ADD CONSTRAINT `bus_stations_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_arrival_location_foreign` FOREIGN KEY (`arrival_location`) REFERENCES `bus_stations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `routes_departure_location_foreign` FOREIGN KEY (`departure_location`) REFERENCES `bus_stations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trips_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
