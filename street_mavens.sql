-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2021 at 04:35 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `street_mavens`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `date_added`) VALUES
(20, 3, 4, 1, '2021-06-12 10:27:27'),
(21, 3, 3, 1, '2021-06-12 10:27:29'),
(22, 3, 2, 1, '2021-06-12 10:27:31'),
(23, 3, 1, 1, '2021-06-12 10:27:33');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `payment_method` enum('Cash On Delivery') NOT NULL,
  `status` enum('Pending','Confirmed') NOT NULL DEFAULT 'Pending',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `payment_method`, `status`, `date_added`) VALUES
(10, 3, 4, 3, 'Cash On Delivery', 'Pending', '2021-06-12 08:36:53'),
(11, 3, 3, 1, 'Cash On Delivery', 'Pending', '2021-06-12 09:46:53'),
(12, 4, 2, 1, 'Cash On Delivery', 'Pending', '2021-06-12 10:03:19'),
(13, 4, 3, 1, 'Cash On Delivery', 'Confirmed', '2021-06-12 10:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `price` int(11) NOT NULL,
  `discount_percentage` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_path` varchar(200) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `name`, `description`, `price`, `discount_percentage`, `stock`, `image_path`, `date_added`) VALUES
(1, 1, 'Godzilla', 'T-Shirt', 750, 10, 997, 'uploads/1.jpg', '2021-05-23 21:50:49'),
(2, 1, 'Madness', 'T-Shirt', 750, 10, 997, 'uploads/2.jpg', '2021-05-23 21:50:49'),
(3, 1, 'Scorpio', 'T-Shirt', 750, 5, 998, 'uploads/3.jpg', '2021-05-23 21:50:49'),
(4, 1, 'Death', 'T-Shirt', 750, 0, 999, 'uploads/4.jpg', '2021-05-23 21:50:49'),
(5, 1, 'Under Style Spell', 'T-Shirt', 750, 0, 0, 'uploads/5.jpg', '2021-05-23 21:50:49'),
(6, 1, 'Muan', 'T-Shirt', 750, 0, 999, 'uploads/6.jpg', '2021-05-23 21:50:49'),
(7, 1, 'Sinister (Black)', 'T-Shirt', 750, 0, 999, 'uploads/7.jpg', '2021-05-23 21:50:49'),
(8, 1, 'Sinister (White)', 'T-Shirt', 750, 0, 999, 'uploads/8.jpg', '2021-05-23 21:50:49'),
(9, 1, 'All Orange 2', 'T-Shirt', 750, 0, 994, 'uploads/9.jpg', '2021-05-23 21:50:49'),
(16, 2, 'sample', 'babagsak ata kami', -150, 0, -9, 'uploads/16.jpg', '2021-06-12 10:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `permission` enum('normal','admin') NOT NULL DEFAULT 'normal',
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(300) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `permission`, `first_name`, `middle_name`, `last_name`, `address`, `contact_number`, `email`, `date_added`) VALUES
(1, 'unknown', '$2y$10$4Gi7h1yM.4M.hTcRQHhOFu..LpgJL7l27WS7XCvnhXhBnA9KHMz0u', 'admin', 'unknown', 'unknown', 'unknown', 'unknown', '09081002617', 'iluminati@gmail.com', '2021-05-23 21:48:45'),
(2, 'gerald', '$2y$10$CheWoASCLGRdPfxj9YXqa.F5U00lme8FbNYMJqDwgzTx75yl0Ub0m', 'admin', 'Gerald', 'Daquigan', 'Valderama', 'Blk 31 Lot 2 Phase 9 Pascam 2 Wellington Place General Trias Cavite', '09555667172', 'angegerald124@gmail.com', '2021-06-12 08:07:04'),
(3, 'wendell', '$2y$10$ITLzecQQA1OPU8O61dPXqOsz1y0.frj4aMA5Y/FdbXtw3zzjDgE1q', 'normal', 'Wendell', 'Torrinuevo', 'Amante', 'Blk 6 Compound Pasong Buaya 2 Imus, Cavite', '09612911866', 'wendellamante@gmail.com', '2021-06-12 08:11:46'),
(4, 'james', '$2y$10$aE3NtxSnn5SZm2OvUoR3a.ffW/Wq4xwWeuJa1nibEjiMBKEphunaK', 'normal', 'king james', 'lacson', 'rasus', 'blkf18 lot 4 brg 2 dasma, cavite', '09555667172', 'kingjames@gmail.com', '2021-06-12 09:58:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Cart_ProductId_Products_Id` (`product_id`),
  ADD KEY `FK_Cart_UserId_Users_Id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Purchased_ProductId_Products_Id` (`product_id`),
  ADD KEY `FK_Purchased_UserId_Users_Id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Products_UserId_Users_Id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_Cart_ProductId_Products_Id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Cart_UserId_Users_Id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_Purchased_ProductId_Products_Id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Purchased_UserId_Users_Id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_Products_UserId_Users_Id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
