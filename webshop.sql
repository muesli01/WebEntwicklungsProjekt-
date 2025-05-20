-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 20 2025 г., 21:53
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `webshop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `wert` decimal(10,2) NOT NULL,
  `gueltig_bis` date NOT NULL,
  `status` enum('aktiv','eingelöst','abgelaufen') DEFAULT 'aktiv',
  `remaining_value` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `wert`, `gueltig_bis`, `status`, `remaining_value`) VALUES
(10, 'IR0P8', 10000.00, '2025-05-07', 'abgelaufen', 10000.00),
(11, '9O3BE', 11111.00, '2025-05-10', 'eingelöst', 0.00),
(12, 'PM0NP', 15000.00, '2025-05-23', 'eingelöst', 0.00),
(13, 'S66VK', 1.00, '2025-05-08', 'eingelöst', 0.00),
(14, 'JJLUY', 62000.00, '2025-05-08', 'eingelöst', 0.00),
(15, 'HL9A5', 11.00, '2025-05-22', 'eingelöst', 0.00),
(16, 'IHPPK', 123.00, '2025-05-22', 'eingelöst', 0.00),
(17, 'TNIT3', 1234.00, '2025-05-31', 'eingelöst', 0.00),
(18, '9Y7FB', 321321.00, '2025-05-22', 'aktiv', 259321.00),
(19, '2HXS2', 123.00, '2025-05-30', 'eingelöst', 0.00),
(20, 'GNQWG', 1.00, '2025-05-20', 'eingelöst', 0.00),
(21, '0RI01', 23.00, '2025-05-22', 'eingelöst', 0.00);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bestellnummer` varchar(50) NOT NULL,
  `bestelldatum` timestamp NOT NULL DEFAULT current_timestamp(),
  `gesamtpreis` decimal(10,2) NOT NULL,
  `status` enum('offen','bezahlt','storniert') DEFAULT 'offen',
  `coupon_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `bestellnummer`, `bestelldatum`, `gesamtpreis`, `status`, `coupon_id`) VALUES
(8, 5, 'ORD1746306030', '2025-05-03 21:00:30', 120000.00, 'offen', NULL),
(9, 1, 'ORD1746402003', '2025-05-04 23:40:03', 58000.00, 'offen', NULL),
(10, 5, 'ORD1746465767', '2025-05-05 17:22:47', 40000.00, 'offen', NULL),
(11, 5, 'ORD1746629154', '2025-05-07 14:45:54', 58000.00, 'offen', NULL),
(12, 5, 'ORD1746629194', '2025-05-07 14:46:34', 58000.00, 'offen', NULL),
(13, 5, 'ORD1746629515', '2025-05-07 14:51:55', 46889.00, 'offen', NULL),
(14, 5, 'ORD1746629671', '2025-05-07 14:54:31', 43000.00, 'offen', NULL),
(15, 5, 'ORD1746629735', '2025-05-07 14:55:35', 58000.00, 'offen', NULL),
(16, 5, 'ORD1746630325', '2025-05-07 15:05:25', 62000.00, 'offen', NULL),
(17, 5, 'ORD1746630389', '2025-05-07 15:06:29', 61999.00, 'offen', NULL),
(18, 5, 'ORD1746631267', '2025-05-07 15:21:07', 0.00, 'offen', NULL),
(19, 5, 'ORD682b8d431b82d', '2025-05-19 19:57:55', 40000.00, 'offen', NULL),
(20, 5, 'ORD682b8d9f2745d', '2025-05-19 19:59:27', 0.00, 'offen', NULL),
(21, 5, 'ORD20250519222421650', '2025-05-19 20:24:21', 39877.00, 'offen', NULL),
(22, 5, 'ORD20250519223106470', '2025-05-19 20:31:06', 57999.00, 'offen', NULL),
(23, 5, 'ORD20250519223408797', '2025-05-19 20:34:08', 58000.00, 'offen', NULL),
(24, 5, 'ORD20250519223457622', '2025-05-19 20:34:57', 40000.00, 'offen', NULL),
(25, 5, 'ORD20250519223524611', '2025-05-19 20:35:24', 40000.00, 'offen', NULL),
(26, 5, 'ORD20250519224319522', '2025-05-19 20:43:19', 58000.00, 'offen', NULL),
(27, 5, 'ORD20250519224448822', '2025-05-19 20:44:48', 61977.00, 'offen', 21),
(28, 5, 'ORD20250519230946522', '2025-05-19 21:09:46', 102000.00, 'offen', NULL),
(29, 5, 'ORD20250519231015666', '2025-05-19 21:10:15', 62000.00, 'offen', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(11, 8, 2, 1, 58000.00),
(12, 8, 3, 1, 62000.00),
(13, 9, 2, 1, 58000.00),
(14, 10, 1, 1, 40000.00),
(15, 11, 2, 1, 58000.00),
(16, 12, 2, 1, 58000.00),
(17, 13, 2, 1, 58000.00),
(18, 14, 2, 1, 58000.00),
(19, 15, 2, 1, 58000.00),
(20, 16, 3, 1, 62000.00),
(21, 17, 3, 1, 62000.00),
(22, 18, 3, 1, 62000.00),
(23, 19, 1, 1, 40000.00),
(24, 20, 3, 1, 62000.00),
(25, 21, 1, 1, 40000.00),
(26, 22, 2, 1, 58000.00),
(27, 23, 2, 1, 58000.00),
(28, 24, 1, 1, 40000.00),
(29, 25, 1, 1, 40000.00),
(30, 26, 2, 1, 58000.00),
(31, 27, 3, 1, 62000.00),
(32, 28, 1, 1, 40000.00),
(33, 28, 3, 1, 62000.00),
(34, 29, 3, 1, 62000.00);

-- --------------------------------------------------------

--
-- Структура таблицы `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bestellnummer` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `user_id`, `bestellnummer`, `method`, `details`, `created_at`) VALUES
(1, 5, 'ORD682b8d431b82d', 'Standard', '111', '2025-05-19 19:57:55'),
(2, 5, 'ORD682b8d9f2745d', 'Standard', '111', '2025-05-19 19:59:27'),
(3, 5, 'ORD20250519222421650', 'Standard', '111', '2025-05-19 20:24:21'),
(4, 5, 'ORD20250519223106470', 'Standard', '111', '2025-05-19 20:31:06'),
(5, 5, 'ORD20250519223408797', 'Standard', '111', '2025-05-19 20:34:08'),
(6, 5, 'ORD20250519223457622', 'Standard', '111', '2025-05-19 20:34:57'),
(7, 5, 'ORD20250519223524611', 'Standard', '111', '2025-05-19 20:35:24'),
(8, 5, 'ORD20250519224319522', 'Standard', '111', '2025-05-19 20:43:19'),
(9, 5, 'ORD20250519224448822', 'Standard', '111', '2025-05-19 20:44:48'),
(10, 5, '', '222', '222', '2025-05-19 21:09:18'),
(11, 5, 'ORD20250519230946522', '222', '222', '2025-05-19 21:09:46'),
(12, 5, 'ORD20250519231015666', 'Standard', '111', '2025-05-19 21:10:15'),
(13, 5, '', '333', '333', '2025-05-19 21:11:51');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'VW Golf', 'Sportliches und luxuriöses SUV.', 40000.00, 'car1.jpg'),
(2, 'VW Tiguan', 'Elegante Limousine mit modernster Technik.', 58000.00, 'car2.jpg'),
(3, 'Audi RS 7', 'Komfortabel und zuverlässig.', 62000.00, 'car3.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `user_id`, `product_id`, `order_id`, `rating`, `comment`, `created_at`) VALUES
(5, 5, 3, 18, 5, 'gute', '2025-05-13 22:13:50'),
(6, 5, 3, 17, 5, 'gute', '2025-05-13 22:23:09'),
(7, 5, 2, 14, 5, '1', '2025-05-13 22:25:28'),
(8, 5, 3, 16, 5, '', '2025-05-13 22:26:28'),
(9, 5, 3, 8, 5, '2', '2025-05-13 22:26:48'),
(10, 5, 2, 8, 4, 'ф', '2025-05-13 22:26:49'),
(11, 5, 2, 12, 5, '123', '2025-05-13 22:27:12');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `anrede` varchar(10) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `nachname` varchar(100) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `plz` varchar(10) NOT NULL,
  `ort` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `zahlung` varchar(255) NOT NULL,
  `rolle` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `anrede`, `vorname`, `nachname`, `adresse`, `plz`, `ort`, `email`, `username`, `password`, `zahlung`, `rolle`, `created_at`, `active`, `remember_token`) VALUES
(1, 'Herr', '123', '123', '123', '1070', 'Wien', '123@gmail.com', '123', '$2y$10$YqN1alZVjM/6t0kXWDkQ2uxJ1BdSwkhT4JWz1J23UyQBM/gfdEMHW', '123', 'user', '2025-04-30 09:41:52', 1, NULL),
(3, 'Herr', 'Admin', 'Admin', 'Adminstraße 1', '1010', 'Wien', 'admin@example.com', 'admin', '$2y$10$ADZyRVecB6cIPp/gQAjm4.xAJ2Z/ueUvzvG8iQ3wKUxcXekdyytVa', 'Rechnung', 'admin', '2025-04-30 09:46:47', 1, NULL),
(5, 'Herr', '111', '111', '111', '1111', '111', '1111@gmail.com', '111', '$2y$10$HeKszDYs4acEzL9msjNrIex/8H98UlUtgExrwpvgQlkLxYZjRuceW', '111', 'user', '2025-05-03 19:05:19', 1, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bestellnummer` (`bestellnummer`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_user` (`user_id`),
  ADD KEY `fk_reviews_product` (`product_id`),
  ADD KEY `fk_reviews_order` (`order_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `fk_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
