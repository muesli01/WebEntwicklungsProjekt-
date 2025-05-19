-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 19 2025 г., 00:21
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
(15, 'HL9A5', 11.00, '2025-05-22', 'aktiv', 11.00);

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
  `status` enum('offen','bezahlt','storniert') DEFAULT 'offen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `bestellnummer`, `bestelldatum`, `gesamtpreis`, `status`) VALUES
(8, 5, 'ORD1746306030', '2025-05-03 21:00:30', 120000.00, 'offen'),
(9, 1, 'ORD1746402003', '2025-05-04 23:40:03', 58000.00, 'offen'),
(10, 5, 'ORD1746465767', '2025-05-05 17:22:47', 40000.00, 'offen'),
(11, 5, 'ORD1746629154', '2025-05-07 14:45:54', 58000.00, 'offen'),
(12, 5, 'ORD1746629194', '2025-05-07 14:46:34', 58000.00, 'offen'),
(13, 5, 'ORD1746629515', '2025-05-07 14:51:55', 46889.00, 'offen'),
(14, 5, 'ORD1746629671', '2025-05-07 14:54:31', 43000.00, 'offen'),
(15, 5, 'ORD1746629735', '2025-05-07 14:55:35', 58000.00, 'offen'),
(16, 5, 'ORD1746630325', '2025-05-07 15:05:25', 62000.00, 'offen'),
(17, 5, 'ORD1746630389', '2025-05-07 15:06:29', 61999.00, 'offen'),
(18, 5, 'ORD1746631267', '2025-05-07 15:21:07', 0.00, 'offen');

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
(22, 18, 3, 1, 62000.00);

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
(3, 'Audi RS 7', 'Komfortabel und zuverlässig.', 62000.00, 'car3.jpg'),
(9, 'vodka', 'braucht nicht ', 99999.00, 'stolichnaya-premium-vodka-0_7-liter.jpg'),
(10, '123', '123', 321.00, 'car5.jpg');

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
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

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
