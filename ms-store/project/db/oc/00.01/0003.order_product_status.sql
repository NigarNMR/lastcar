-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 12 2016 г., 12:09
-- Версия сервера: 5.6.22-log
-- Версия PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `autopuls_su`
--

-- --------------------------------------------------------

--
-- Структура таблицы `dev_order_product_history`
--

CREATE TABLE IF NOT EXISTS `oc_order_product_history` (
  `order_product_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_product_id` int(11) NOT NULL,
  `order_product_status_id` int(5) NOT NULL,
  `notify` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_product_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `dev_order_product_status`
--

CREATE TABLE IF NOT EXISTS `oc_order_product_status` (
  `order_product_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY (`order_product_status_id`,`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Очистить таблицу перед добавлением данных `dev_order_product_history`
--

TRUNCATE TABLE `oc_order_product_history`;
--
-- Очистить таблицу перед добавлением данных `dev_order_product_status`
--

TRUNCATE TABLE `oc_order_product_status`;
--
-- Дамп данных таблицы `dev_order_product_status`
--

INSERT INTO `oc_order_product_status` (`order_product_status_id`, `language_id`, `name`, `required`) VALUES
(2, 1, 'Processing', 0),
(3, 2, 'В доставке', 0),
(5, 2, 'Выдано', 0),
(8, 1, 'Denied', 0),
(9, 2, 'Пришло на склад', 0),
(10, 2, 'Утилизированно', 0),
(12, 2, 'Приостановлено', 0),
(13, 1, 'Chargeback', 0),
(16, 2, 'В работе', 1),
(15, 1, 'Processed', 0),
(14, 1, 'Expired', 0),
(2, 2, 'Processing', 0),
(3, 1, 'Shipped', 0),
(7, 2, 'Отказ', 0),
(5, 1, 'Complete', 0),
(8, 2, 'Denied', 0),
(9, 1, 'Canceled Reversal', 0),
(10, 1, 'Failed', 0),
(11, 2, 'Закуплено', 0),
(13, 2, 'Chargeback', 0),
(1, 1, 'Получен', 0),
(15, 2, 'Processed', 0),
(14, 2, 'Expired', 0),
(7, 1, 'Canceled', 0),
(1, 2, 'Получен', 1),
(16, 1, 'Voided', 0),
(12, 1, 'Reversed', 0),
(11, 1, 'Refunded', 0);


--
-- Структура таблицы `oc_order_product_status_workflow`
--

CREATE TABLE IF NOT EXISTS `oc_order_product_status_workflow` (
  `order_product_status_id_A` int(11) NOT NULL,
  `order_product_status_id_B` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_product_status_id_A`,`order_product_status_id_B`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `oc_order_status_product_status_workflow`
--

CREATE TABLE IF NOT EXISTS `oc_order_status_product_status_workflow` (
  `order_status_id` int(11) NOT NULL,
  `order_product_status_id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 -any; 2 - all; 3 - if one;'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `oc_order_status_workflow`
--

CREATE TABLE IF NOT EXISTS `oc_order_status_workflow` (
  `order_status_id_A` int(11) NOT NULL,
  `order_status_id_B` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_status_id_A`,`order_status_id_B`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Добавление столбца статуса товара в заказе в таблицу `oc_order_product`
--

ALTER TABLE  `oc_order_product` ADD  `order_product_status_id` INT NOT NULL ;