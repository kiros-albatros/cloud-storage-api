-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 26 2023 г., 00:36
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cloud-storage_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `File`
--

CREATE TABLE `File` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `directory` varchar(250) NOT NULL,
  `user_owner_id` int NOT NULL,
  `extension` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `File`
--

INSERT INTO `File` (`id`, `name`, `directory`, `user_owner_id`, `extension`) VALUES
(7, 'move3.jpg', '', 3, 'jpg'),
(8, '3.pdf', '', 3, 'pdf'),
(11, '100502.jpg', '', 4, 'jpg'),
(13, '.jpg', '', 4, 'jpg'),
(14, '321.jpg', '', 4, 'jpg'),
(16, '32125.jpg', '', 4, 'jpg'),
(17, '155', ' ', 4, ' ');

-- --------------------------------------------------------

--
-- Структура таблицы `File_accesses`
--

CREATE TABLE `File_accesses` (
  `file_id` int NOT NULL,
  `user_id` int NOT NULL,
  `share_url` varchar(250) NOT NULL,
  `permission_level` varchar(50) NOT NULL,
  `user_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `File_accesses`
--

INSERT INTO `File_accesses` (`file_id`, `user_id`, `share_url`, `permission_level`, `user_email`) VALUES
(7, 5, 'file/share/7', 'read', 'tester2@gmail.com'),
(9, 4, 'file/share/9', 'contribute', 'tester861@gmail.com'),
(7, 6, 'file/share/7', 'read', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `User`
--

INSERT INTO `User` (`id`, `email`, `password`, `role`) VALUES
(4, 'tester861@gmail.com', '213256', 'user'),
(5, 'tester2@gmail.com', '123456', 'user'),
(11, 'admin1@gmail.com', '123456', 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `File`
--
ALTER TABLE `File`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `File`
--
ALTER TABLE `File`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
