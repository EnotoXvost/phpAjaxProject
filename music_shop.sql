-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 31 2024 г., 21:40
-- Версия сервера: 5.6.51
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `music_shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `deleted_records`
--

CREATE TABLE `deleted_records` (
  `id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date_release` year(4) NOT NULL,
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `deleted_records`
--

INSERT INTO `deleted_records` (`id`, `record_id`, `title`, `group_name`, `price`, `date_release`, `deleted_at`) VALUES
(1, 20, '123', 'Король И Шут', '123.00', 0000, NULL),
(2, 16, 'Absolutely', 'Boxer', '66000.00', 1977, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(14, 'Art Rock'),
(15, 'Psychedelic Rock'),
(18, 'Punk'),
(19, 'Classic Rock'),
(20, 'Indie Rock'),
(21, 'Epic'),
(22, 'Rock & Roll');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `country`) VALUES
(1, 'Король И Шут', 'Russia'),
(2, 'Bo Hansson', 'Germany'),
(3, 'Creedence Clearwater Revival', 'France'),
(4, 'Pixies', 'UK & Europe'),
(5, 'Boxer', 'UK'),
(6, 'Beatles', 'Japan'),
(7, 'test', 'test');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `date`) VALUES
(4, 1, '2023-12-08'),
(5, 1, '2023-12-08'),
(7, 1, '2023-12-09'),
(8, 1, '2023-12-11'),
(9, 1, '2023-12-11'),
(10, 5, '2023-12-11'),
(11, 1, '2023-12-11'),
(12, 1, '2023-12-11'),
(13, 5, '2023-12-11');

-- --------------------------------------------------------

--
-- Структура таблицы `records_in_orders`
--

CREATE TABLE `records_in_orders` (
  `order_id` int(11) NOT NULL,
  `records_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `records_in_orders`
--

INSERT INTO `records_in_orders` (`order_id`, `records_id`) VALUES
(4, 2),
(4, 1),
(5, 1),
(7, 9),
(7, 8),
(9, 17),
(12, 18);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `admin_able` tinyint(4) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `can_login` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `admin_able`, `username`, `email`, `password`, `can_login`) VALUES
(1, 1, 'Admin-1', 'admin@admin', 'admin', 0),
(4, 0, '123', 'test@gmail.com', 'test', 0),
(5, 0, 'ну я', 'qwe@qwe', '123', 1),
(6, 0, '', 'qqq@qq', '11', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `vinylRecords`
--

CREATE TABLE `vinylRecords` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `date_release` year(4) NOT NULL,
  `img_album` varchar(255) NOT NULL,
  `statuss` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `vinylRecords`
--

INSERT INTO `vinylRecords` (`id`, `title`, `group_id`, `genre_id`, `price`, `quantity`, `date_release`, `img_album`, `statuss`) VALUES
(1, 'Будь Как Дома, Путник....(кассета)', 1, 14, '1800', 3, 1994, '/images/kish.png', ''),
(2, 'Music Inspired By Lord Of The Rings', 2, 15, '4500', 0, 1970, '/images/main_site_photo.jpg', ''),
(7, 'Акустический Альбом (кассета)', 1, 14, '1800', 12, 1998, '/images/kish-2.jpg', ''),
(8, 'Герои и Злодеи (на черном виниле)', 1, 18, '18500', 0, 2000, '/images/kish-3.jpg', ''),
(9, 'Chooglin\'', 3, 19, '4800', 11, 1982, '/images/twr.jpg', ''),
(10, 'Creedence Clearwater Revival', 3, 19, '5500', 0, 1968, '/images/twr-2.jpg', ''),
(11, 'Bossanova', 4, 20, '4800', 5, 1990, '/images/pix-1.jpg', ''),
(17, 'A Collection Of Beatles Oldies', 6, 22, '6500', 0, 1966, '/images/6574912ccb74a7.77209190.jpg', ''),
(18, 'The Beatles', 6, 22, '100000', 0, 1968, '/images/6574915d0b33e5.08295136.jpg', ''),
(40, '123', 4, 19, '123', 213, 0000, '/images/664ca6120a2814.01481236.jpg', ''),
(41, '21321321', 1, 14, '123', 123, 0000, '/images/664caac6a54422.22808692.jpg', ''),
(42, '12ццц', 1, 14, '12', 12, 0000, '/images/664cab7b014e60.64114970.jpg', ''),
(43, '213qwe', 1, 14, '123', 123, 0000, '/images/664cabba5148f8.84893566.png', ''),
(44, 'цйу', 1, 14, '123', 123, 0000, '/images/664cac0e290355.52026411.jpg', 'В наличии'),
(45, '2e', 1, 14, '22', 12, 0000, '/images/664cac83a83d68.85116679.jpg', 'Не в наличии'),
(46, '333333', 7, 14, '33333333', 3333333, 0000, '/images/664cba73cc32e4.81012251.jpg', 'Не в наличии');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `deleted_records`
--
ALTER TABLE `deleted_records`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `records_in_orders`
--
ALTER TABLE `records_in_orders`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `records_id` (`records_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `vinylRecords`
--
ALTER TABLE `vinylRecords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genre_id` (`genre_id`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `deleted_records`
--
ALTER TABLE `deleted_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `vinylRecords`
--
ALTER TABLE `vinylRecords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `records_in_orders`
--
ALTER TABLE `records_in_orders`
  ADD CONSTRAINT `records_in_orders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `records_in_orders_ibfk_2` FOREIGN KEY (`records_id`) REFERENCES `vinylRecords` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `vinylRecords`
--
ALTER TABLE `vinylRecords`
  ADD CONSTRAINT `vinylrecords_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vinylrecords_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
