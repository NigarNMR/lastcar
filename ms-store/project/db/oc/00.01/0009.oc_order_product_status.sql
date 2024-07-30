-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 29 2016 г., 12:55
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
-- Структура таблицы `oc_order_product_status`
--

CREATE TABLE IF NOT EXISTS `oc_order_product_status` (
  `order_product_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `bg_color` varchar(7) NOT NULL DEFAULT '#ffffff' COMMENT 'цвет фона',
  `text_color` varchar(7) NOT NULL DEFAULT '#000000' COMMENT 'цвет текста'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

TRUNCATE TABLE  `oc_order_product_status`;
--
-- Дамп данных таблицы `oc_order_product_status`
--

INSERT INTO `oc_order_product_status` (`order_product_status_id`, `language_id`, `name`, `required`, `bg_color`, `text_color`) VALUES
(2, 1, 'Pull off', 0, 'e6e6e6', '000000'),
(3, 2, 'В доставке', 0, 'feffb8', 'bd8842'),
(5, 2, 'Выдано', 0, 'c6ffa3', '000000'),
(9, 2, 'Пришло на склад', 0, 'feffd1', '800080'),
(10, 2, 'Утилизированно', 0, '#ffffff', '#000000'),
(12, 2, 'Приостановлен', 0, 'b8c9ff', '000000'),
(13, 1, 'In transit', 0, 'ffd1ed', '800080'),
(16, 2, 'В работе', 0, 'ff8afd', '000000'),
(2, 2, 'Снято', 0, 'e6e6e6', '000000'),
(3, 1, 'Shipped', 0, 'feffb8', 'bd8842'),
(7, 2, 'Отказ', 0, 'ff5266', '000000'),
(5, 1, 'Complete', 0, 'c6ffa3', '000000'),
(8, 2, 'Пришло на склад региона', 0, '008000', 'ffff00'),
(9, 1, 'Canceled Reversal', 0, 'feffd1', '800080'),
(10, 1, 'Failed', 0, '#ffffff', '#000000'),
(11, 2, 'Закуплено', 0, 'a8ffa3', '800080'),
(13, 2, 'В пути', 0, 'ffd1ed', '800080'),
(1, 1, 'Получен', 0, '', ''),
(15, 2, 'Ожидает выдачи', 0, 'ffffff', '938fff'),
(14, 2, 'Пришло на склад МСК', 0, 'b7e1bf', '000000'),
(7, 1, 'Canceled', 0, 'ff5266', '000000'),
(1, 2, 'Получен', 0, '', ''),
(16, 1, 'Voided', 0, 'ff8afd', '000000'),
(12, 1, 'Reversed', 0, 'b8c9ff', '000000'),
(11, 1, 'Refunded', 0, 'a8ffa3', '800080'),
(8, 1, 'Сame to  warehouse region', 0, '008000', 'ffff00'),
(14, 1, 'On a warehouse ', 0, 'b7e1bf', '000000'),
(15, 1, 'Awaits extradition', 0, 'ffffff', '938fff');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `oc_order_product_status`
--
ALTER TABLE `oc_order_product_status`
  ADD PRIMARY KEY (`order_product_status_id`,`language_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `oc_order_product_status`
--
ALTER TABLE `oc_order_product_status`
  MODIFY `order_product_status_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
