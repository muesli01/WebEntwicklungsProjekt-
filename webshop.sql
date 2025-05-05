-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 05 2025 г., 18:29
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
  `status` enum('aktiv','eingelöst','abgelaufen') DEFAULT 'aktiv'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `wert`, `gueltig_bis`, `status`) VALUES
(1, '1O1FW', 1000.00, '2025-05-15', 'eingelöst'),
(2, 'E42T0', 123.00, '2025-05-01', 'abgelaufen'),
(3, '6QFAH', 13.00, '2025-05-17', 'eingelöst'),
(4, 'YFS27', 2.00, '2025-05-22', 'aktiv'),
(5, 'BL82L', 88.00, '2025-05-02', 'abgelaufen'),
(6, 'V7S9M', 123000.00, '2025-05-04', 'abgelaufen'),
(7, 'RDXDL', 1.00, '2025-05-06', 'eingelöst'),
(8, 'MON11', 0.00, '2025-05-02', 'abgelaufen');

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
(9, 1, 'ORD1746402003', '2025-05-04 23:40:03', 58000.00, 'offen');

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
(13, 9, 2, 1, 58000.00);

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
(7, 'Mercedes ', 'S class', 250000.00, 'car5.jpg');

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
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `anrede`, `vorname`, `nachname`, `adresse`, `plz`, `ort`, `email`, `username`, `password`, `zahlung`, `rolle`, `created_at`, `active`) VALUES
(1, 'Herr', '123', '123', '123', '1070', 'Wien', '123@gmail.com', '123', '$2y$10$YqN1alZVjM/6t0kXWDkQ2uxJ1BdSwkhT4JWz1J23UyQBM/gfdEMHW', '123', 'user', '2025-04-30 09:41:52', 1),
(3, 'Herr', 'Admin', 'Admin', 'Adminstraße 1', '1010', 'Wien', 'admin@example.com', 'admin', '$2y$10$ADZyRVecB6cIPp/gQAjm4.xAJ2Z/ueUvzvG8iQ3wKUxcXekdyytVa', 'Rechnung', 'admin', '2025-04-30 09:46:47', 1),
(5, 'Herr', '111', '111', '111', '1111', '111', '1111@gmail.com', '111', '$2y$10$HeKszDYs4acEzL9msjNrIex/8H98UlUtgExrwpvgQlkLxYZjRuceW', '111', 'user', '2025-05-03 19:05:19', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
