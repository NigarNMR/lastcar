-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 02 2016 г., 12:57
-- Версия сервера: 5.6.22-log
-- Версия PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tdm_web_eijen_ru`
--

--
-- Дамп данных таблицы `TDM_WS`
--

INSERT INTO `TDM_WS` (`ID`, `NAME`, `ACTIVE`, `SCRIPT`, `CACHE`, `CLIENT_ID`, `LOGIN`, `PASSW`, `QUERY_LIMIT`, `CURRENCY`, `TYPE`, `DAY_ADD`, `PRICE_ADD`, `PRICE_EXTRA`, `MIN_AVAIL`, `MAX_DAY`, `LINKS_TAKE`, `LINKS_SIDE`, `PRICE_CODE`, `REFRESH_TIME`) VALUES
(1, 'armtek', 1, 'armtek.ru', 1, '', 'mashkovsky_ab@mail.ru', '27072016armtek', 40, 'RUB', 1, 1, 0.00, 20, 0, 0, 1, 0, 'amt', 3),
(2, 'Emex', 1, 'emex.ru', 1, '397882', '397882', '12649a54', 40, 'RUB', 1, 1, 0.00, 20, 0, 0, 1, 0, 'emx', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
