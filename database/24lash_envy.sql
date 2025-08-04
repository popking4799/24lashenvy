-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2025 at 11:31 AM
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
-- Database: `24lash_envy`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `service_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Accepted','In Progress','Completed','Cancelled') DEFAULT 'Accepted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `user_id`, `name`, `email`, `phone`, `service_id`, `staff_id`, `appointment_date`, `appointment_time`, `status`, `created_at`) VALUES
(1, 4, 'Emmanuel Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 1, 5, '2025-06-18', '08:00:00', 'Completed', '2025-06-17 19:39:40'),
(2, 7, 'Emmanuel Martins', 'bfg0658@gmail.com', '08160888975', 5, 5, '2025-06-19', '14:00:00', 'Accepted', '2025-06-18 06:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `post_date` date DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `title`, `content`, `image`, `post_date`, `category`, `tags`) VALUES
(5, 'The Ultimate Guide to Eyelash Extensions: Choosing the Right Style for You', 'Explore everything you need to know about eyelash extensions — from classic to volume lashes, how to choose the right length and curl for your eye shape, and aftercare tips to keep them looking flawless.', 'images/blog/lash1.jpg', '2023-09-15', 'Lash Extensions', 'eyelashes,lash extensions,beauty tips'),
(6, 'Top 5 Lash Application Mistakes to Avoid', 'Struggling with applying lashes that won’t stay or look natural? Learn the most common mistakes people make when applying false lashes and how to correct them for a perfect finish.', 'images/blog/lash2.jpg', '2023-09-20', 'False Lashes', 'lashes,application mistakes,beauty hacks'),
(7, 'Wig Care 101: How to Maintain Your Human Hair Wig', 'A good wig can transform your look — but only if you care for it properly. This guide walks you through washing, conditioning, storing, and styling your human hair wigs to extend their life.', 'images/blog/wig1.jpg', '2023-09-25', 'Wigs', 'wig care,human hair wigs,hair maintenance'),
(8, 'Lace Front vs Full Lace Wigs: What’s the Difference?', 'Confused about lace front and full lace wigs? We break down the key differences, pros and cons of each, and which is better suited for everyday wear, styling flexibility, and comfort.', 'images/blog/wig2.jpg', '2023-10-01', 'Wig Types', 'lace wigs,full lace,lifestyle beauty');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `category`, `file_path`) VALUES
(1, 'Classic Hair Style 1', 'hair styles', 'images/gallery/1.jpg'),
(4, 'Volume set', 'Lashes', 'images/gallery/6985da81-cef3-4edb-bdc7-6aa82c7a1883.jpeg'),
(5, 'Ombre Set', 'Lashes', 'images/gallery/Makeup Trends 2023 - My Affordable Beauty Tips.jpeg'),
(6, 'Hybrid Set', 'Lashes', 'images/gallery/fb289055-f6c2-415a-8b66-af29564ccc0a.jpeg'),
(7, 'Coily Wig', 'Wigs', 'images/gallery/fe8fb02c-54e6-4d6a-a5c5-69ac7edbb921.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `subscribed_at`) VALUES
(1, 'emar0266@gmail.com', '2025-06-17 23:17:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` enum('online_payment','cod') NOT NULL DEFAULT 'online_payment',
  `status` enum('unpaid','pending','paid','packed','shipped','delivered','cancelled') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `first_name`, `last_name`, `email`, `telephone`, `address`, `city`, `postal_code`, `country`, `total`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 01:44:17', '2025-06-18 01:44:17'),
(2, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 01:51:43', '2025-06-18 01:51:43'),
(3, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 02:00:29', '2025-06-18 02:00:29'),
(4, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 02:01:35', '2025-06-18 02:01:35'),
(5, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 05:34:01', '2025-06-18 05:34:01'),
(6, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 05:34:42', '2025-06-18 05:34:42'),
(7, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 05:37:31', '2025-06-18 05:37:31'),
(8, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 05:40:31', '2025-06-18 05:40:31'),
(9, 4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', 'ORUmba$$0658', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', '900104', 'Nigeria', 4000.00, 'online_payment', 'unpaid', '2025-06-18 05:45:38', '2025-06-18 05:45:38'),
(10, 7, 'Emmanuel', 'Martins', 'bfg0658@gmail.com', '08160888975', 'lincoln university', 'abuja', '900104', 'nigeria', 4000.00, 'online_payment', 'pending', '2025-06-18 07:05:31', '2025-06-18 08:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 1, 'Classic Lash extension', 1, 4000.00),
(2, 2, 1, 'Classic Lash extension', 1, 4000.00),
(3, 3, 1, 'Classic Lash extension', 1, 4000.00),
(4, 4, 1, 'Classic Lash extension', 1, 4000.00),
(5, 5, 1, 'Classic Lash extension', 1, 4000.00),
(6, 6, 1, 'Classic Lash extension', 1, 4000.00),
(7, 7, 1, 'Classic Lash extension', 1, 4000.00),
(8, 8, 1, 'Classic Lash extension', 1, 4000.00),
(9, 9, 1, 'Classic Lash extension', 1, 4000.00),
(10, 10, 1, 'Classic Lash extension', 1, 4000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `stock_status` enum('in_stock','out_of_stock') DEFAULT 'in_stock',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `category`, `price`, `image_url`, `stock_status`, `created_at`, `updated_at`) VALUES
(1, 'Classic Lash extension', 'A well suited lash extension', 'Lashes', 4000.00, 'images/shop/download.jpeg', 'in_stock', '2025-06-17 23:12:41', '2025-06-17 23:12:41'),
(3, 'Ombre Set', 'A unique lash set', 'Lashes', 4000.00, 'images/shop/Makeup Trends 2023 - My Affordable Beauty Tips.jpeg', 'in_stock', '2025-06-18 08:20:32', '2025-06-18 08:20:32'),
(4, 'Hybrid Set', 'A unique lash set', 'Lashes', 4000.00, 'images/shop/fb289055-f6c2-415a-8b66-af29564ccc0a.jpeg', 'in_stock', '2025-06-18 08:23:13', '2025-06-18 08:23:13'),
(5, 'Volume Set', 'A unique lash set', 'Lashes', 4000.00, 'images/shop/6985da81-cef3-4edb-bdc7-6aa82c7a1883.jpeg', 'out_of_stock', '2025-06-18 08:23:57', '2025-06-18 08:35:45'),
(6, 'Coily Wig', 'A classic and chick selection', 'Wigs', 15000.00, 'images/shop/fe8fb02c-54e6-4d6a-a5c5-69ac7edbb921.jpeg', 'in_stock', '2025-06-18 08:34:07', '2025-06-18 08:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `member_price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `category`, `description`, `price`, `member_price`, `duration`, `created_at`, `updated_at`) VALUES
(1, 'Classic set', 'Lashes', 'Long lasting lashes extension', 4000.00, 3000.00, 60, '2025-06-17 19:33:38', '2025-06-17 19:33:38'),
(2, 'Hybrid Set', 'Lashes', 'Long lasting hybrid set of lashes', 4000.00, 3000.00, 60, '2025-06-18 01:33:24', '2025-06-18 01:33:24'),
(3, 'Ombre Set', 'Lashes', 'Long lasting set of ombre lashes', 4000.00, 3000.00, 60, '2025-06-18 01:34:21', '2025-06-18 01:34:21'),
(4, 'Volume set', 'Lashes', 'Long lasting set of volume lashes', 4000.00, 3000.00, 60, '2025-06-18 01:35:26', '2025-06-18 01:35:26'),
(5, 'Semi Permanent Tattoo', 'Tattoo', 'Unique and beautiful tatoo', 15000.00, 10500.00, 180, '2025-06-18 01:37:19', '2025-06-18 01:37:19'),
(6, 'Permanent Tattoo', 'Tattoo', 'Unique and beautiful tattoo', 20000.00, 15500.00, 180, '2025-06-18 01:38:11', '2025-06-18 01:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `role` enum('user','admin','staff') DEFAULT 'user',
  `specialization` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `telephone`, `fax`, `address`, `city`, `country`, `postal_code`, `role`, `specialization`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User1', 'admin1@hiruna.dev', '$2y$10$UNoa4vy9FZ/ZS/0wP65zfuq.rDHh6eOy9/TFb850OH1Zv5ZcaNJpi', '0711000001', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2025-04-18 15:40:21', '2025-04-18 15:40:21'),
(2, 'John', 'Doe', 'john.doe@hiruna.dev', '$2y$10$UNoa4vy9FZ/ZS/0wP65zfuq.rDHh6eOy9/TFb850OH1Zv5ZcaNJpi', '0712000001', NULL, NULL, NULL, NULL, NULL, 'user', NULL, '2025-04-18 15:40:22', '2025-04-18 15:40:22'),
(3, 'Admin', 'User2', 'admin2@hiruna.dev', 'ORUmba$$0658', '0711000001', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, '2025-04-18 15:43:58', '2025-04-18 15:43:58'),
(4, 'Emmanuel', 'Martins', 'emar0266@gmail.com', '$2y$10$9btJHnEqqiCsr/HYuBDwxu0y8a4oynmUu7k//r9f1CrKwIqg8vkJC', 'ORUmba$$0658', '', 'Dunamis road Kurudu along nyanya-karshi road ', 'Abuja', 'Nigeria', '900104', 'admin', NULL, '2025-04-19 16:33:13', '2025-06-18 01:43:27'),
(5, 'Kingsley', 'Adumike', 'em48433013@gmail.com', '$2y$10$5vyncFIJSpH0T0.0FFS.ou4GpcUVFSU.NxRo.VJ7vc9VO.uUdMFjS', '08160888975', NULL, NULL, NULL, NULL, NULL, 'staff', NULL, '2025-04-19 16:56:28', '2025-04-19 16:56:28'),
(6, 'Obialo', 'Yasita', 'obialoyasita@gmail.com', '$2y$10$G0bAsoR7cESmsO/G3bbliOz2E09JybzCcSoeANoPBrmXtVskHX88u', '09072472311', '', NULL, NULL, NULL, NULL, 'user', NULL, '2025-05-06 22:47:13', '2025-05-06 22:50:21'),
(7, 'Emmanuel', 'Martins', 'bfg0658@gmail.com', '$2y$10$SWfoQ8ScmgJ3aNh2LTEMLOzXDQNEljsQnn6O4UPiXEEP1B0ZwdRu6', '08160888975', '', 'lincoln university', 'abuja', 'nigeria', '900104', 'user', NULL, '2025-06-18 06:49:53', '2025-06-18 07:04:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_otp`
--

CREATE TABLE `user_otp` (
  `user_otp_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `otp_code` int(11) NOT NULL,
  `otp_expiry` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `user_id`, `product_id`, `date_added`) VALUES
(1, 4, 1, '2025-06-18 01:40:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_otp`
--
ALTER TABLE `user_otp`
  ADD PRIMARY KEY (`user_otp_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_otp`
--
ALTER TABLE `user_otp`
  MODIFY `user_otp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
