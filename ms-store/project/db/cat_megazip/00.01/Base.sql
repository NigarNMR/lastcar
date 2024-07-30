-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 11 2016 г., 08:45
-- Версия сервера: 5.5.45
-- Версия PHP: 5.4.44

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `cat_megazip`
--
CREATE DATABASE IF NOT EXISTS `cat_megazip` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `cat_megazip`;

-- --------------------------------------------------------

--
-- Структура таблицы `BRAND`
--

CREATE TABLE IF NOT EXISTS `BRAND` (
  `BRA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `BRA_NAME` varchar(32) NOT NULL,
  `BRA_IMAGE` varchar(45) DEFAULT NULL,
  `BRA_TYP_ID` int(11) NOT NULL,
  PRIMARY KEY (`BRA_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Структура таблицы `COORDINATES`
--

CREATE TABLE IF NOT EXISTS `COORDINATES` (
  `CRD_SPC_MEGAZIP_ID` int(11) NOT NULL,
  `CRD_SPA_MEGAZIP_ID` int(11) NOT NULL,
  `CRD_X_1` int(11) NOT NULL,
  `CRD_Y_1` int(11) NOT NULL,
  `CRD_X_2` int(11) NOT NULL,
  `CRD_Y_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `OPTIONS`
--

CREATE TABLE IF NOT EXISTS `OPTIONS` (
  `OPT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `OPT_NAME` varchar(200) NOT NULL,
  `OPT_IMAGE` varchar(45) DEFAULT NULL,
  `OPT_SRS_ID` int(11) NOT NULL,
  `OPT_MEGAZIP_ID` int(11) NOT NULL,
  `OPT_YEAR` varchar(8) DEFAULT NULL,
  `OPT_COLOR` varchar(32) DEFAULT NULL,
  `OPT_REGION` varchar(32) DEFAULT NULL,
  `OPT_ENG_CAPACITY` varchar(16) DEFAULT NULL,
  `OPT_MODEL_CODE` varchar(16) DEFAULT NULL,
  `OPT_REGION_CODE` varchar(16) DEFAULT NULL,
  `OPT_MODEL_GROUP` varchar(16) DEFAULT NULL,
  `OPT_COLOR_SCHEME` varchar(32) DEFAULT NULL,
  `OPT_COMMERCIAL_NAME` varchar(32) DEFAULT NULL,
  `OPT_ENG_CYCLES` varchar(32) DEFAULT NULL,
  `OPT_ENG_CODE` varchar(32) DEFAULT NULL,
  `OPT_FRAME_CODE` varchar(32) DEFAULT NULL,
  `OPT_NOTE` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`OPT_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38927 ;

-- --------------------------------------------------------

--
-- Структура таблицы `SERIES`
--

CREATE TABLE IF NOT EXISTS `SERIES` (
  `SRS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SRS_NAME` varchar(32) NOT NULL,
  `SRS_IMAGE` varchar(45) DEFAULT NULL,
  `SRS_BRA_ID` int(11) NOT NULL,
  `SRS_MEGAZIP_ID` int(11) NOT NULL,
  PRIMARY KEY (`SRS_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1167 ;

-- --------------------------------------------------------

--
-- Структура таблицы `SPARE_PARTS`
--

CREATE TABLE IF NOT EXISTS `SPARE_PARTS` (
  `SPA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SPA_ART` varchar(32) NOT NULL,
  `SPA_NAME` varchar(32) NOT NULL,
  `SPA_MEGAZIP_ID` int(11) NOT NULL,
  PRIMARY KEY (`SPA_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=130966 ;

-- --------------------------------------------------------

--
-- Структура таблицы `SPARE_PARTS_CATS`
--

CREATE TABLE IF NOT EXISTS `SPARE_PARTS_CATS` (
  `SPC_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SPC_NAME` varchar(200) NOT NULL,
  `SPC_IMAGE` varchar(128) DEFAULT NULL,
  `SPC_OPT_ID` int(11) NOT NULL,
  `SPC_MEGAZIP_ID` int(11) NOT NULL,
  PRIMARY KEY (`SPC_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36449 ;

-- --------------------------------------------------------

--
-- Структура таблицы `SPA_TO_SPC`
--

CREATE TABLE IF NOT EXISTS `SPA_TO_SPC` (
  `SPC_ID` int(11) NOT NULL,
  `SPA_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `UNIQ_OPTION_NAMES`
--

CREATE TABLE IF NOT EXISTS `UNIQ_OPTION_NAMES` (
  `UON_ID` int(11) NOT NULL AUTO_INCREMENT,
  `UON_NAME` varchar(64) NOT NULL,
  PRIMARY KEY (`UON_ID`),
  UNIQUE KEY `UON_NAME` (`UON_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `VTYPE`
--

CREATE TABLE IF NOT EXISTS `VTYPE` (
  `TYP_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TYP_NAME` varchar(32) NOT NULL,
  `TYP_IMAGE` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`TYP_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
