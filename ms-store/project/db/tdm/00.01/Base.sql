-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 10 2016 г., 11:35
-- Версия сервера: 5.5.41-log
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

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_CURS`
--

CREATE TABLE IF NOT EXISTS `TDM_CURS` (
  `CODE` varchar(3) NOT NULL,
  `RATE` float(12,7) NOT NULL,
  `TEMPLATE` varchar(12) NOT NULL,
  `TRUNCATE` int(1) NOT NULL,
  PRIMARY KEY (`CODE`),
  KEY `CODE` (`CODE`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_IM_COLUMNS`
--

CREATE TABLE IF NOT EXISTS `TDM_IM_COLUMNS` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `SUPID` int(4) NOT NULL,
  `NUM` int(2) NOT NULL,
  `FIELD` varchar(16) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `SUPID` (`SUPID`) USING HASH
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_IM_SUPPLIERS`
--

CREATE TABLE IF NOT EXISTS `TDM_IM_SUPPLIERS` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(32) NOT NULL,
  `CODE` varchar(32) NOT NULL,
  `COLUMN_SEP` varchar(3) NOT NULL DEFAULT ';',
  `ARTBRA_SEP` varchar(3) NOT NULL,
  `ARTBRA_SIDE` int(1) NOT NULL DEFAULT '1',
  `ENCODE` varchar(9) NOT NULL DEFAULT 'CP1251',
  `FILE_PATH` varchar(256) NOT NULL,
  `FILE_NAME` varchar(32) NOT NULL,
  `FILE_PASSW` varchar(32) NOT NULL,
  `START_FROM` int(12) NOT NULL,
  `STOP_BEFORE` int(12) NOT NULL,
  `DELETE_ON_START` int(1) NOT NULL,
  `PRICE_EXTRA` int(4) NOT NULL,
  `CONSIDER_HOT` int(1) NOT NULL,
  `PRICE_ADD` float(12,2) NOT NULL,
  `PRICE_TYPE` int(3) NOT NULL,
  `MIN_AVAIL` int(4) NOT NULL,
  `MAX_DAY` int(4) NOT NULL,
  `DEF_BRAND` varchar(32) NOT NULL,
  `DEF_CURRENCY` varchar(3) NOT NULL,
  `DAY_ADD` int(4) NOT NULL,
  `DEF_AVAILABLE` int(4) NOT NULL,
  `DEF_STOCK` varchar(16) NOT NULL,
  PRIMARY KEY (`ID`,`CONSIDER_HOT`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_LANGS`
--

CREATE TABLE IF NOT EXISTS `TDM_LANGS` (
  `LANG` char(2) NOT NULL,
  `CODE` char(32) NOT NULL,
  `VALUE` varchar(512) NOT NULL,
  `TYPE` tinyint(1) NOT NULL DEFAULT '0',
  `SYSTEM` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`LANG`,`CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_LINKS`
--

CREATE TABLE IF NOT EXISTS `TDM_LINKS` (
  `PKEY1` varchar(64) NOT NULL,
  `BKEY1` varchar(32) NOT NULL,
  `AKEY1` varchar(32) NOT NULL,
  `PKEY2` varchar(64) NOT NULL,
  `BKEY2` varchar(32) NOT NULL,
  `AKEY2` varchar(32) NOT NULL,
  `SIDE` int(1) NOT NULL,
  `CODE` varchar(32) NOT NULL,
  PRIMARY KEY (`PKEY1`,`PKEY2`),
  KEY `PKEY1` (`PKEY1`,`SIDE`) USING HASH,
  KEY `PKEY2` (`PKEY2`,`SIDE`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_PRICES`
--

CREATE TABLE IF NOT EXISTS `TDM_PRICES` (
  `BKEY` varchar(64) NOT NULL,
  `AKEY` varchar(64) NOT NULL,
  `ARTICLE` varchar(32) NOT NULL,
  `ALT_NAME` varchar(128) NOT NULL DEFAULT '',
  `BRAND` varchar(32) NOT NULL,
  `PRICE` float(12,2) NOT NULL,
  `TYPE` int(2) NOT NULL,
  `CURRENCY` varchar(3) NOT NULL,
  `DAY` int(4) NOT NULL,
  `AVAILABLE` int(4) NOT NULL,
  `SUPPLIER` varchar(32) NOT NULL,
  `STOCK` varchar(32) NOT NULL,
  `OPTIONS` varchar(64) NOT NULL,
  `CODE` varchar(32) NOT NULL,
  `DATE` varchar(10) NOT NULL,
  PRIMARY KEY (`BKEY`,`DAY`,`SUPPLIER`,`STOCK`,`TYPE`,`AKEY`,`OPTIONS`),
  KEY `AKEY` (`BKEY`,`AKEY`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_SEOMETA`
--

CREATE TABLE IF NOT EXISTS `TDM_SEOMETA` (
  `LANG` int(2) NOT NULL,
  `URL` varchar(128) NOT NULL,
  `TITLE` varchar(128) DEFAULT NULL,
  `KEYWORDS` varchar(128) DEFAULT NULL,
  `DESCRIPTION` varchar(128) DEFAULT NULL,
  `H1` varchar(64) DEFAULT NULL,
  `TOPTEXT` text,
  `BOTTEXT` text,
  PRIMARY KEY (`URL`,`LANG`),
  KEY `URL` (`URL`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_SETTINGS`
--

CREATE TABLE IF NOT EXISTS `TDM_SETTINGS` (
  `ITEM` varchar(32) NOT NULL DEFAULT '',
  `FIELD` varchar(32) NOT NULL DEFAULT '',
  `VALUE` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`ITEM`,`FIELD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_WS`
--

CREATE TABLE IF NOT EXISTS `TDM_WS` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(32) NOT NULL,
  `ACTIVE` int(1) NOT NULL,
  `SCRIPT` varchar(32) NOT NULL,
  `CACHE` int(1) NOT NULL,
  `CLIENT_ID` varchar(32) NOT NULL,
  `LOGIN` varchar(32) NOT NULL,
  `PASSW` varchar(32) NOT NULL,
  `QUERY_LIMIT` int(4) NOT NULL,
  `CURRENCY` varchar(3) NOT NULL,
  `TYPE` int(3) NOT NULL,
  `DAY_ADD` int(2) NOT NULL,
  `PRICE_ADD` float(12,2) NOT NULL,
  `PRICE_EXTRA` int(6) NOT NULL,
  `MIN_AVAIL` int(4) NOT NULL,
  `MAX_DAY` int(4) NOT NULL,
  `LINKS_TAKE` int(1) NOT NULL,
  `LINKS_SIDE` int(1) NOT NULL,
  `PRICE_CODE` varchar(32) NOT NULL,
  `REFRESH_TIME` int(12) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `TDM_WS_TIME`
--

CREATE TABLE IF NOT EXISTS `TDM_WS_TIME` (
  `SID` int(9) NOT NULL,
  `WSID` int(3) NOT NULL,
  `PKEY` varchar(64) NOT NULL,
  `TIME` int(10) NOT NULL,
  PRIMARY KEY (`SID`,`WSID`,`PKEY`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
