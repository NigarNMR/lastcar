-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 29 2016 г., 12:51
-- Версия сервера: 10.0.26-MariaDB
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `web_eijen_ru`
--

-- --------------------------------------------------------

--
-- Структура таблицы `oc_order_product_status_workflow`
--

CREATE TABLE IF NOT EXISTS `oc_order_product_status_workflow` (
  `order_product_status_id_A` int(11) NOT NULL,
  `order_product_status_id_B` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `oc_order_product_status_workflow`
--

INSERT INTO `oc_order_product_status_workflow` (`order_product_status_id_A`, `order_product_status_id_B`, `value`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 7, 1),
(1, 10, 1),
(1, 12, 1),
(1, 16, 1),
(2, 2, 1),
(2, 7, 1),
(2, 10, 1),
(2, 12, 1),
(2, 16, 1),
(3, 2, 1),
(3, 3, 1),
(3, 7, 1),
(3, 8, 1),
(3, 9, 1),
(3, 10, 1),
(3, 12, 1),
(3, 14, 1),
(3, 15, 1),
(5, 5, 1),
(5, 16, 1),
(7, 7, 1),
(7, 16, 1),
(8, 2, 1),
(8, 7, 1),
(8, 8, 1),
(8, 9, 1),
(8, 10, 1),
(8, 12, 1),
(8, 15, 1),
(9, 2, 1),
(9, 7, 1),
(9, 9, 1),
(9, 10, 1),
(9, 12, 1),
(9, 15, 1),
(10, 2, 1),
(10, 7, 1),
(10, 10, 1),
(10, 12, 1),
(10, 16, 1),
(11, 2, 1),
(11, 3, 1),
(11, 7, 1),
(11, 8, 1),
(11, 9, 1),
(11, 10, 1),
(11, 11, 1),
(11, 12, 1),
(11, 13, 1),
(11, 14, 1),
(12, 2, 1),
(12, 7, 1),
(12, 10, 1),
(12, 12, 1),
(12, 16, 1),
(13, 2, 1),
(13, 3, 1),
(13, 7, 1),
(13, 8, 1),
(13, 9, 1),
(13, 10, 1),
(13, 12, 1),
(13, 13, 1),
(13, 14, 1),
(13, 15, 1),
(14, 2, 1),
(14, 7, 1),
(14, 8, 1),
(14, 9, 1),
(14, 10, 1),
(14, 12, 1),
(14, 14, 1),
(14, 15, 1),
(15, 2, 1),
(15, 5, 1),
(15, 7, 1),
(15, 10, 1),
(15, 12, 1),
(15, 15, 1),
(16, 2, 1),
(16, 3, 1),
(16, 7, 1),
(16, 8, 1),
(16, 9, 1),
(16, 10, 1),
(16, 11, 1),
(16, 12, 1),
(16, 13, 1),
(16, 14, 1),
(16, 16, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `oc_order_product_status_workflow`
--
ALTER TABLE `oc_order_product_status_workflow`
  ADD PRIMARY KEY (`order_product_status_id_A`,`order_product_status_id_B`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
