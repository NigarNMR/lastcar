-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 15 2016 г., 05:54
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
-- Очистить таблицу перед добавлением данных `VTYPE`
--

TRUNCATE TABLE `VTYPE`;
--
-- Дамп данных таблицы `VTYPE`
--

INSERT INTO `VTYPE` (`TYP_ID`, `TYP_NAME`, `TYP_IMAGE`) VALUES
(1, 'Мотоцикл', NULL),
(2, 'Квадроцикл', NULL),
(3, 'Снегоход', NULL),
(4, 'Гидроцикл', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
