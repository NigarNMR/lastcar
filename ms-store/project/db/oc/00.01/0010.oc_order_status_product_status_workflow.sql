-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 05 2016 г., 14:27
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
-- Структура таблицы `oc_order_status_product_status_workflow`
--

CREATE TABLE IF NOT EXISTS `oc_order_status_product_status_workflow` (
  `order_status_id` int(11) NOT NULL,
  `order_product_status_id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 -any; 2 - all; 3 - if one;'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Очистить таблицу перед добавлением данных `oc_order_status_product_status_workflow`
--

TRUNCATE TABLE `oc_order_status_product_status_workflow`;
--
-- Дамп данных таблицы `oc_order_status_product_status_workflow`
--

INSERT INTO `oc_order_status_product_status_workflow` (`order_status_id`, `order_product_status_id`, `type`) VALUES
(0, 0, 2),
(9, 14, 2),
(9, 16, 3),
(9, 11, 1),
(5, 10, 2),
(1, 1, 2),
(13, 0, 2),
(13, 7, 3),
(13, 2, 3),
(13, 10, 3),
(11, 19, 2),
(11, 19, 3),
(11, 7, 1),
(11, 2, 1),
(11, 10, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
