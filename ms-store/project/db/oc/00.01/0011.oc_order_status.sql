-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 06 2016 г., 07:07
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
-- Структура таблицы `oc_order_status`
--

CREATE TABLE IF NOT EXISTS `oc_order_status` (
  `order_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `text_color` varchar(7) NOT NULL DEFAULT '000000' COMMENT 'цвет текста',
  `bg_color` varchar(7) NOT NULL DEFAULT 'ffffff' COMMENT 'цвет фона'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `oc_order_status`
--

TRUNCATE TABLE `oc_order_status`;
--
-- Дамп данных таблицы `oc_order_status`
--

INSERT INTO `oc_order_status` (`order_status_id`, `language_id`, `name`, `text_color`, `bg_color`) VALUES
(9, 1, 'On the anvil', 'ffe099', 'ffb3b3'),
(13, 1, 'Renouncement', '800080', 'adffd6'),
(1, 1, 'Pending', '0000ff', 'ffff00'),
(5, 2, 'Выдано', '000000', 'ffffff'),
(9, 2, 'В работе', 'ffe099', 'ffb3b3'),
(11, 2, 'Возврат', '000000', 'fff27a'),
(13, 2, 'Отказ', '800080', 'adffd6'),
(1, 2, 'Получен', '0000ff', 'ffff00'),
(5, 1, 'Issued', '000000', 'ffffff'),
(11, 1, 'Refunded', '000000', 'fff27a');

--
-- Индексы сохранённых таблиц
--



--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `oc_order_status`
--
ALTER TABLE `oc_order_status`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
