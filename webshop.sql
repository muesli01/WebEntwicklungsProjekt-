-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 29 2025 г., 13:42
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
(1, 'VW Golf', 'Sportliches und luxuriöses SUV.', 75000.00, 'car1.jpg'),
(2, 'VW Tiguan', 'Elegante Limousine mit modernster Technik.', 58000.00, 'car2.jpg'),
(3, 'Audi RS 7', 'Komfortabel und zuverlässig.', 62000.00, 'car3.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(6, '123', '123@gmail.com', '$2y$10$MpsikC5d.200L0.IPt/wYOCH1thQaOGJDkNrcmY95he5WGcgui.B6', '2025-04-05 17:57:24'),
(9, 'jakob', 'jakob@gmail.com', '$2y$10$d45XhrY5vzZynt4HJV9g2ulrjbbLdDF6vBDJdD7ZOIAvOLkpXc.QW', '2025-04-05 18:04:39'),
(11, '321', '321@gmail.com', '$2y$10$3hP0ioPrdkN/688LtMyySu21n78342gGf0jNXAQggARSmZmc/LjXa', '2025-04-05 18:06:36'),
(12, '666', '666@gmail.com', '$2y$10$KyyMQcZsoA0uYsXDKIYKVeClhyMYEBZ1XtQt3wAvAA09ozkKvNcr6', '2025-04-09 14:05:18'),
(13, 'ewq', 'ewq@gmail.com', '$2y$10$qkUT.o5o7HvIxSQgSeVv1eHCVnntsLg13LS1kOuHPFp9EtJ/8fOwO', '2025-04-11 15:25:31'),
(15, 'ewqq', 'ewqq@gmail.com', '$2y$10$JK5eHKo6wjQLgMMV9mgoXO/uwTW4vAcZ8jA5Gcb9ie/KoffzStyY2', '2025-04-11 15:29:23'),
(16, 'aaa', 'aaa@gmail.com', '$2y$10$XWDAbtP9/ANKIuCJfWGiZ.wql1rLiegGz5WLZ.ofNEu0R2qd1lOy.', '2025-04-11 15:31:20');

--
-- Индексы сохранённых таблиц
--

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
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
