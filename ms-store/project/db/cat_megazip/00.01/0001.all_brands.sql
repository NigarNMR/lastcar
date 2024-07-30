-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 14 2016 г., 08:09
-- Версия сервера: 5.5.45
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `cat_megazip`
--

--
-- Очистить таблицу перед добавлением данных `BRAND`
--

TRUNCATE TABLE `BRAND`;
--
-- Дамп данных таблицы `BRAND`
--

INSERT INTO `BRAND` (`BRA_ID`, `BRA_NAME`, `BRA_IMAGE`, `BRA_TYP_ID`) VALUES
(1, 'yamaha', NULL, 1),
(2, 'suzuki', NULL, 1),
(3, 'kawasaki', NULL, 1),
(4, 'honda', NULL, 1),
(5, 'polaris', NULL, 1),
(6, 'yamaha', NULL, 2),
(7, 'suzuki', NULL, 2),
(8, 'kawasaki', NULL, 2),
(9, 'honda', NULL, 2),
(10, 'arctic-cat', NULL, 2),
(11, 'bombardier', NULL, 2),
(12, 'polaris', NULL, 2),
(13, 'yamaha', NULL, 3),
(14, 'arctic-cat', NULL, 3),
(15, 'bombardier', NULL, 3),
(16, 'polaris', NULL, 3),
(17, 'yamaha', NULL, 4),
(18, 'kawasaki', NULL, 4),
(19, 'arctic-cat', NULL, 4),
(20, 'bombardier', NULL, 4),
(21, 'polaris', NULL, 4);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
