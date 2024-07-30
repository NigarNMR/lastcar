-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 16 2016 г., 10:38
-- Версия сервера: 5.5.50
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
-- Структура таблицы `oc_banner`
--

--
-- Очистить таблицу перед добавлением данных `oc_banner`
--

TRUNCATE TABLE `oc_banner`;
--
-- Дамп данных таблицы `oc_banner`
--

INSERT INTO `oc_banner` (`banner_id`, `name`, `status`) VALUES
(6, 'HP Products', 1),
(7, 'Home Page Slideshow', 1),
(8, 'Manufacturers', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `oc_banner_image`
--

--
-- Очистить таблицу перед добавлением данных `oc_banner_image`
--

TRUNCATE TABLE `oc_banner_image`;
--
-- Дамп данных таблицы `oc_banner_image`
--

INSERT INTO `oc_banner_image` (`banner_image_id`, `banner_id`, `link`, `image`, `sort_order`) VALUES
(143, 7, '', 'catalog/main/Powertrain-8-Cylinder-.jpg', 2),
(87, 6, 'index.php?route=product/manufacturer/info&amp;manufacturer_id=7', 'catalog/demo/compaq_presario.jpg', 0),
(123, 8, '', 'catalog/brand/sachs.jpg', 0),
(121, 8, '', 'catalog/brand/phillips.jpg', 0),
(122, 8, '', 'catalog/brand/rancho.jpg', 0),
(120, 8, '', 'catalog/brand/ngk.jpg', 0),
(118, 8, '', 'catalog/brand/gates.jpg', 0),
(119, 8, '', 'catalog/brand/mann2.jpg', 0),
(116, 8, '', 'catalog/brand/castrol.jpg', 0),
(117, 8, '', 'catalog/brand/delphi.jpg', 0),
(115, 8, '', 'catalog/brand/ctr.jpg', 0),
(114, 8, '', 'catalog/brand/bosch.jpg', 0),
(113, 8, '', 'catalog/brand/akobono.jpg', 0),
(112, 8, '', 'catalog/brand/NISSENS.jpg', 0),
(111, 8, '', 'catalog/brand/LEM-Logo.jpg', 0),
(110, 8, '', 'catalog/brand/KYB.jpg', 0),
(124, 8, '', 'catalog/brand/skf.jpg', 0),
(125, 8, '', 'catalog/brand/vfm.jpg', 0),
(126, 8, '', 'catalog/brand/vr2.jpg', 0),
(127, 8, '', 'catalog/brand/zf.jpg', 0),
(128, 8, '', 'catalog/brand/Denso.png', 0),
(129, 8, '', 'catalog/brand/bosal_thumb.png', 0),
(130, 8, '', 'catalog/brand/monroe.png', 0),
(131, 8, '', 'catalog/brand/osram.png', 0),
(132, 8, '', 'catalog/brand/valeo.png', 0),
(133, 8, '', 'catalog/brand/ruville.gif', 0),
(134, 8, '', 'catalog/brand/TRW_LOGO.JPG', 0),
(144, 7, 'index.php?route=product/product&amp;path=57&amp;product_id=49', 'catalog/main/main-2.jpg', 3),
(142, 7, '', 'catalog/main/Untitled-1.jpg', 1),
(145, 7, '', 'catalog/main/mann.jpg', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `oc_banner_image_description`
--

--
-- Очистить таблицу перед добавлением данных `oc_banner_image_description`
--

TRUNCATE TABLE `oc_banner_image_description`;
--
-- Дамп данных таблицы `oc_banner_image_description`
--

INSERT INTO `oc_banner_image_description` (`banner_image_id`, `language_id`, `banner_id`, `title`) VALUES
(87, 1, 6, 'HP Banner'),
(121, 2, 8, 'philips'),
(120, 1, 8, 'ngk'),
(120, 2, 8, 'ngk'),
(119, 1, 8, 'mann'),
(145, 2, 7, 'фильтры'),
(119, 2, 8, 'mann'),
(118, 1, 8, 'gates'),
(118, 2, 8, 'gates'),
(117, 1, 8, 'delphi'),
(116, 1, 8, 'castrol'),
(87, 2, 6, 'HP Banner'),
(117, 2, 8, 'delphi'),
(116, 2, 8, 'castrol'),
(115, 1, 8, 'ctr'),
(115, 2, 8, 'ctr'),
(144, 1, 7, 'аккумулятор'),
(114, 1, 8, 'bosch'),
(114, 2, 8, 'bosch'),
(113, 1, 8, 'ak'),
(113, 2, 8, 'ak'),
(112, 1, 8, 'nis'),
(112, 2, 8, 'nis'),
(111, 1, 8, 'lem'),
(111, 2, 8, 'lem'),
(110, 1, 8, 'kyb'),
(110, 2, 8, 'kyb'),
(121, 1, 8, 'philips'),
(122, 2, 8, 'rancho'),
(122, 1, 8, 'rancho'),
(123, 2, 8, 'sachs'),
(123, 1, 8, 'sachs'),
(124, 2, 8, 'skf'),
(124, 1, 8, 'skf'),
(125, 2, 8, 'vfm'),
(125, 1, 8, 'vfm'),
(126, 2, 8, 'victor '),
(126, 1, 8, 'victor '),
(127, 2, 8, 'zf'),
(127, 1, 8, 'zf'),
(128, 2, 8, 'denso'),
(128, 1, 8, 'denso'),
(129, 2, 8, 'bosal'),
(129, 1, 8, 'bosal'),
(130, 2, 8, 'monroe'),
(130, 1, 8, 'monroe'),
(131, 2, 8, 'osram'),
(131, 1, 8, 'osram'),
(132, 2, 8, 'valeo'),
(132, 1, 8, 'valeo'),
(133, 2, 8, 'ruville'),
(133, 1, 8, 'ruville'),
(134, 2, 8, 'trw'),
(134, 1, 8, 'trw'),
(144, 2, 7, 'аккумулятор'),
(143, 1, 7, 'бампер'),
(143, 2, 7, 'бампер'),
(142, 1, 7, 'ходовая'),
(142, 2, 7, 'ходовая'),
(145, 1, 7, 'фильтры');

-- --------------------------------------------------------

--
-- Структура таблицы `oc_information`
--

--
-- Очистить таблицу перед добавлением данных `oc_information`
--

TRUNCATE TABLE `oc_information`;
--
-- Дамп данных таблицы `oc_information`
--

INSERT INTO `oc_information` (`information_id`, `bottom`, `sort_order`, `status`) VALUES
(3, 1, 3, 1),
(4, 1, 1, 1),
(5, 1, 4, 1),
(6, 1, 2, 1),
(7, 1, 0, 1),
(8, 1, 3, 1),
(9, 1, 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `oc_information_description`
--

--
-- Очистить таблицу перед добавлением данных `oc_information_description`
--

TRUNCATE TABLE `oc_information_description`;
--
-- Дамп данных таблицы `oc_information_description`
--

INSERT INTO `oc_information_description` (`information_id`, `language_id`, `title`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
(5, 1, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n', 'Terms &amp; Conditions', '', ''),
(3, 1, 'Privacy Policy', '&lt;p&gt;\r\n	Privacy Policy&lt;/p&gt;\r\n', 'Privacy Policy', '', ''),
(5, 2, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n', 'Terms &amp; Conditions', '', ''),
(3, 2, 'Privacy Policy', '&lt;p&gt;\r\n	Privacy Policy&lt;/p&gt;\r\n', 'Privacy Policy', '', ''),
(6, 1, 'Delivery ', '&lt;p&gt;\r\n	Delivery Information&lt;/p&gt;\r\n', 'Delivery ', '', ''),
(7, 2, 'Как заказать', '&lt;div class=&quot;wrapper&quot; style=&quot;margin: auto; padding: 0px 22px; border: 0px; outline: 0px; vertical-align: baseline; max-width: 1284px; min-width: 1024px; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&lt;div class=&quot;wrapper context&quot; style=&quot;margin: auto; padding: 0px 22px; border: 0px; outline: 0px; vertical-align: baseline; max-width: 1284px; min-width: 1024px; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;Основная часть запасных частей, которые мы можем Вам предложить, находится на нашем складе, а также на складах наших партнеров в Москве и &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;Подмосковье.&lt;/p&gt;&lt;div class=&quot;wrapper context&quot; style=&quot;margin: auto; padding: 0px 22px; border: 0px; outline: 0px; vertical-align: baseline; max-width: 1284px; min-width: 1024px; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&lt;h3 style=&quot;margin-right: 10px; margin-bottom: 0px; margin-left: 10px; padding: 0px; border: 0px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Оформить заявку на запчасти можно одним из указанных способов:&lt;/h3&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;В режиме on-line на нашем сайте, оформив заказ на автозапчасти&amp;nbsp;&lt;br&gt;По телефону:&amp;nbsp;&lt;span style=&quot;font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px; text-transform: uppercase;&quot;&gt;+7 (3822)977-430&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;line-height: 17.1428px;&quot;&gt;или&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px; text-transform: uppercase;&quot;&gt;+7 (952)180-4661&lt;/span&gt;&lt;/p&gt;&lt;p data-bind=&quot;text: $data&quot; style=&quot;margin-bottom: 0px; color: rgb(51, 51, 51); font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px; padding: 0px;&quot;&gt;&lt;a href=&quot;http://eijen.localhost/#&quot; style=&quot;color: rgb(221, 72, 20); font-family: Ubuntu, Tahoma, &amp;quot;Helvetica Neue&amp;quot;, Helvetica, Arial, sans-serif; line-height: 20px; text-align: right; background-color: rgb(255, 255, 255);&quot;&gt;&lt;/a&gt;&lt;/p&gt;&lt;h3 style=&quot;margin-right: 10px; margin-bottom: 0px; margin-left: 10px; padding: 0px; border: 0px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Обновление ассортимента автозапчастей&lt;/h3&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Информация на нашем сайте регулярно обновляется, что сводит к минимуму такие ситуации, когда на складе не окажется запчастей, числящихся «в наличии» в интернет-магазине. Кроме того, мы практически полностью исключаем возможность отличия продажных цен от цен, заявленных на нашем сайте.&lt;/p&gt;&lt;h3 style=&quot;margin-right: 10px; margin-bottom: 0px; margin-left: 10px; padding: 0px; border: 0px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Покупка запчасти через интернет-магазин&lt;/h3&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Чтобы заказать автозапчасти через интернет магазин необходимо:&amp;nbsp;&lt;br&gt;1. Зарегистрироваться на сайте.&amp;nbsp;&lt;br&gt;2. Выбрать нужные запчасти и положить их в корзину.&amp;nbsp;&lt;br&gt;3. Зайти в корзину и оформить заказ.&amp;nbsp;&lt;br&gt;&lt;br&gt;При оформлении заказа на запчасти Вы можете выбрать:&amp;nbsp;&lt;br&gt;1. Способ оплаты (наличный / безналичный платеж)&amp;nbsp;&lt;br&gt;2. Способ доставки (самовывоз / курьерская доставка)&amp;nbsp;&lt;br&gt;3. Адрес доставки&amp;nbsp;&lt;br&gt;4. Комментарий к заказу и доставке&lt;/p&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;При оформлении заказа на автозапчасти в интернет магазине необходимо указать Вашу контактную информацию для подтверждения наличия товара на складе нашими менеджерами и для оформления доставки.&lt;/p&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;', 'Как заказать', '', ''),
(8, 2, 'Контакты', '&lt;h1 class=&quot;page-title&quot; data-bind=&quot;text: pageTitleText&quot; style=&quot;font-stretch: normal; font-size: 30px; line-height: normal; font-family: Gals, Arial, sans-serif; margin-top: 10px; margin-bottom: 10px; color: rgb(0, 0, 0);&quot;&gt;&lt;span style=&quot;font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 19.2px;&quot;&gt;Ваш менеджер&lt;/span&gt;&lt;br&gt;&lt;/h1&gt;&lt;div class=&quot;value&quot; data-bind=&quot;text: managerFullName&quot; style=&quot;font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;&lt;br&gt;&lt;/div&gt;&lt;div class=&quot;header&quot; style=&quot;font-weight: bold; margin: 15px 0px 10px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;Электронная почта&lt;/div&gt;&lt;div class=&quot;value&quot; style=&quot;font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;&lt;br&gt;&lt;/div&gt;&lt;div class=&quot;header&quot; style=&quot;font-weight: bold; margin: 15px 0px 10px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;Телефоны&lt;/div&gt;&lt;div class=&quot;value&quot; data-bind=&quot;foreach: phones&quot; style=&quot;font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;&lt;p data-bind=&quot;text: $data&quot; style=&quot;margin-bottom: 0px; padding: 0px;&quot;&gt;&lt;a href=&quot;http://eijen.localhost/#&quot; style=&quot;color: rgb(221, 72, 20); font-family: Ubuntu, Tahoma, &amp;quot;Helvetica Neue&amp;quot;, Helvetica, Arial, sans-serif; line-height: 20px; text-align: right; background-color: rgb(255, 255, 255);&quot;&gt;&lt;/a&gt;&lt;/p&gt;&lt;p class=&quot;text-uppercase text-muted &quot; style=&quot;box-sizing: border-box; margin: 0px 0px 10px; text-transform: uppercase;&quot;&gt;&lt;font color=&quot;#000000&quot;&gt;+7 (3822)977-430&lt;/font&gt;&lt;/p&gt;&lt;p data-bind=&quot;text: $data&quot; style=&quot;margin-bottom: 0px; padding: 0px;&quot;&gt;&lt;a href=&quot;http://eijen.localhost/#&quot; style=&quot;color: rgb(221, 72, 20); font-family: Ubuntu, Tahoma, &amp;quot;Helvetica Neue&amp;quot;, Helvetica, Arial, sans-serif; line-height: 20px; text-align: right; background-color: rgb(255, 255, 255);&quot;&gt;&lt;/a&gt;&lt;/p&gt;&lt;p class=&quot;text-uppercase text-muted &quot; style=&quot;box-sizing: border-box; margin: 0px 0px 10px; text-transform: uppercase;&quot;&gt;&lt;font color=&quot;#000000&quot;&gt;+7 (952)180-4661&lt;/font&gt;&lt;/p&gt;&lt;/div&gt;&lt;div class=&quot;header&quot; style=&quot;font-weight: bold; margin: 15px 0px 10px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;Часы работы&lt;/div&gt;&lt;div class=&quot;float-left work-time-icons&quot; style=&quot;float: left; width: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;&lt;div data-bind=&quot;foreach: workTimeItems&quot;&gt;&lt;div class=&quot;work-time-item-icon&quot; data-bind=&quot;css: { ''day-off'': isActive === false }&quot; style=&quot;height: 3px; margin-bottom: 3px; width: 12px; background: url(&amp;quot;/images/info/work-time.jpg?v=38d3ccd51a2d2ee3cdbab82bbe6c7219&amp;quot;) -13px 0px no-repeat;&quot;&gt;&lt;/div&gt;&lt;div class=&quot;work-time-item-icon&quot; data-bind=&quot;css: { ''day-off'': isActive === false }&quot; style=&quot;height: 3px; margin-bottom: 3px; width: 12px; background: url(&amp;quot;/images/info/work-time.jpg?v=38d3ccd51a2d2ee3cdbab82bbe6c7219&amp;quot;) -13px 0px no-repeat;&quot;&gt;&lt;/div&gt;&lt;div class=&quot;work-time-item-icon&quot; data-bind=&quot;css: { ''day-off'': isActive === false }&quot; style=&quot;height: 3px; margin-bottom: 3px; width: 12px; background: url(&amp;quot;/images/info/work-time.jpg?v=38d3ccd51a2d2ee3cdbab82bbe6c7219&amp;quot;) -13px 0px no-repeat;&quot;&gt;&lt;/div&gt;&lt;div class=&quot;work-time-item-icon&quot; data-bind=&quot;css: { ''day-off'': isActive === false }&quot; style=&quot;height: 3px; margin-bottom: 3px; width: 12px; background: url(&amp;quot;/images/info/work-time.jpg?v=38d3ccd51a2d2ee3cdbab82bbe6c7219&amp;quot;) -13px 0px no-repeat;&quot;&gt;&lt;/div&gt;&lt;div class=&quot;work-time-item-icon&quot; data-bind=&quot;css: { ''day-off'': isActive === false }&quot; style=&quot;height: 3px; margin-bottom: 3px; width: 12px; background: url(&amp;quot;/images/info/work-time.jpg?v=38d3ccd51a2d2ee3cdbab82bbe6c7219&amp;quot;) -13px 0px no-repeat;&quot;&gt;&lt;/div&gt;&lt;div class=&quot;work-time-item-icon&quot; data-bind=&quot;css: { ''day-off'': isActive === false }&quot; style=&quot;height: 3px; margin-bottom: 3px; width: 12px; background: url(&amp;quot;/images/info/work-time.jpg?v=38d3ccd51a2d2ee3cdbab82bbe6c7219&amp;quot;) -13px 0px no-repeat;&quot;&gt;&lt;/div&gt;&lt;div class=&quot;work-time-item-icon day-off&quot; data-bind=&quot;css: { ''day-off'': isActive === false }&quot; style=&quot;height: 3px; margin-bottom: 3px; width: 12px; background: url(&amp;quot;/images/info/work-time.jpg?v=38d3ccd51a2d2ee3cdbab82bbe6c7219&amp;quot;) -13px -28px no-repeat;&quot;&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=&quot;float-left&quot; style=&quot;float: left; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;&lt;div data-bind=&quot;foreach: workTimeStrItems&quot;&gt;&lt;div data-bind=&quot;text: $data.trim()&quot;&gt;Будни с 09:00 - 19:30&lt;/div&gt;&lt;div data-bind=&quot;text: $data.trim()&quot;&gt;Суббота с 09:00 - 17:00&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=&quot;clear&quot; style=&quot;clear: both; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;&lt;/div&gt;&lt;div class=&quot;header&quot; style=&quot;font-weight: bold; margin: 15px 0px 10px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;Адрес офиса обслуживания&lt;/div&gt;&lt;div class=&quot;value&quot; data-bind=&quot;text: fullAddress&quot; style=&quot;font-family: Arial, Helvetica, sans-serif; font-size: 14px; line-height: 19.2px;&quot;&gt;Россия, Томск&lt;/div&gt;', 'Контакты', '', ''),
(4, 1, 'About Us', '&lt;p&gt;\r\n	About Us&lt;/p&gt;\r\n', 'About Us', '', ''),
(4, 2, 'О Нас', '&lt;h2 style=&quot;margin-right: 10px; margin-bottom: 15px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&lt;b&gt;Крупнейший поставщик запчастей в России.&lt;/b&gt;&lt;/h2&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Реализация запасных частей и комплектующих для автомобилей различных зарубежных марок. Максимально используя преимущества интернета, мы стремимся предоставить своим клиентам самый широкий выбор автозапчастей, а также сделать их покупку выгодной и удобной из любого уголка России. Почему мы рекомендуем обратиться именно в наш интернет-магазин?&lt;/p&gt;&lt;h2 style=&quot;margin-right: 10px; margin-bottom: 15px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&lt;b&gt;Традиционно низкие цены на запчасти.&lt;/b&gt;&lt;/h2&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Мы предлагаем вам наиболее низкие цены. В наши дни уже невозможно полностью отказаться от автомобиля и расходов на его содержание. Но эти расходы можно существенно сократить, воспользовавшись услугами нашего интернет-магазина. Помимо этого, мы предлагаем гибкую систему скидок и регулярно проводим бонусные программы для наших постоянных заказчиков.&lt;/p&gt;&lt;h2 style=&quot;margin-right: 10px; margin-bottom: 15px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&lt;b&gt;Огромный ассортимент оригинальных и неоригинальных запасных частей.&lt;/b&gt;&lt;/h2&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Мы предлагаем вам большой ассортимент. Таким образом, наши клиенты могут выбрать любые запчасти и комплектующие для своего автомобиля. Также в нашем каталоге представлен широкий выбор аксессуаров, автомасла, диски, шины и многие другие товары. Это оригинальная продукция от ведущих мировых производителей, ассортимент которой постоянно увеличивается. Компания Reant-auto располагает собственным складом автозапчастей, что позволяет выполнять самые сложные заказы в кратчайшие сроки.&lt;/p&gt;&lt;h2 style=&quot;margin-right: 10px; margin-bottom: 15px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&lt;b&gt;Удобный заказ и быстрая доставка запчастей.&lt;/b&gt;&lt;/h2&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Мы предлагаем вам подбор необходимых запчастей в режиме онлайн. Сотрудники нашей компании ценят личное время заказчиков, позволяя потратить его наиболее эффективно. Например, подбор запасных частей и комплектующих для автомобиля в нашем интернет-магазине позволяет сравнить характеристики тех или иных деталей для автомобиля и сделать наиболее практичный и правильный выбор. Помимо этого, к услугам заказчиков автоматизированная система приёма и обработки заказа, которая позволяет быстро и надёжно совершать необходимые покупки.&lt;/p&gt;&lt;h2 style=&quot;margin-right: 10px; margin-bottom: 15px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;&lt;b&gt;Подобрать запчасть поможет опытный менеджер.&lt;/b&gt;&lt;/h2&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;На сайте можно пользоваться онлайн-каталогами, делать заказы или поддерживать контакт с менеджером через личный кабинет пользователя. Каждый заказ незамедлительно обрабатывается, и служба доставки нашей компании отправляет заказанные товары в любой регион России. Мы работаем как с индивидуальными, так и с оптовыми заказчиками, постоянно расширяя свои возможности.&amp;nbsp;&lt;/p&gt;&lt;p style=&quot;margin-right: 10px; margin-bottom: 30px; margin-left: 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;&quot;&gt;Важнейшими приоритетами деятельности компании является ответственность, открытость и индивидуальный подход к каждому клиенту. Мы дорожим своей деловой репутацией, а потому стремимся приложить все силы к тому, чтобы наше сотрудничество было продуктивным и долговременным. Желаем вам удачных покупок в нашем интернет-магазине!&lt;/p&gt;\r\n', 'О Нас', '', ''),
(6, 2, 'Доставка', '&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial&quot;&gt;&lt;strong style=&quot;font-size: 14px; line-height: normal;&quot;&gt;&lt;span style=&quot;font-size: 14pt;&quot;&gt;&lt;span style=&quot;font-size: 18pt;&quot;&gt;Способ оплаты и получения заказа.&lt;/span&gt;&lt;/span&gt;&lt;/strong&gt;&lt;/font&gt;&lt;/p&gt;&lt;font face=&quot;Arial&quot;&gt;&lt;strong style=&quot;color: rgb(64, 64, 64); font-size: 14px; line-height: normal;&quot;&gt;&lt;span style=&quot;font-size: 14pt;&quot;&gt;&lt;br&gt;&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;color: rgb(64, 64, 64); line-height: normal; font-size: 12pt;&quot;&gt;Для работы с заказами в системе Bardetail используются внутренние Персональные счета клиентов. Все средства полученные от клиента попадают на Персональный счет клиента в системе и могут быть использованы для работы с заказами. В случае заказа товарных позиций со сроком доставки более 1 дня необходима предоплата. Предоплата может быть внесена удобным для Вас способом. В случае снятия заказа клиент вправе получить средства тем способом, которым был оплачен заказ.&lt;br&gt;&lt;br&gt;На текущий момент мы можем предложить Вам способы оплаты:&lt;br&gt;&lt;br&gt;&lt;span style=&quot;font-size: 14pt;&quot;&gt;&lt;strong&gt;Оплата за наличный расчет.&lt;/strong&gt;&lt;/span&gt;&lt;br&gt;&lt;br&gt;Предполагает внесение денежных средств через кассу офиса обслуживания, в котором Вы зарегистрированы.&lt;br&gt;&lt;br&gt;&lt;span style=&quot;font-size: 14pt;&quot;&gt;&lt;strong&gt;Безналичный&lt;/strong&gt;&amp;nbsp;&lt;strong&gt;способ.&lt;/strong&gt;&lt;/span&gt;&lt;br&gt;&lt;br&gt;Предполагает безналичный перевод денег из банка в банк.&amp;nbsp;&lt;br&gt;&lt;br&gt;Для Юридических лиц плательщиком является организация указанная при регистрации.&amp;nbsp;&lt;br&gt;Для Частных лиц плательщиком является лицо, указанное при регистрации. Вашим банком могут взиматься комиссионные сборы. Размер сборов Вы можете уточнить в Вашем банке.&lt;br&gt;Для получения счета на оплату пожалуйста обратитесь к Вашему менеджеру.&lt;br&gt;&lt;br&gt;&lt;span style=&quot;font-size: 14pt;&quot;&gt;&lt;strong&gt;Банковской&lt;/strong&gt;&amp;nbsp;&lt;strong&gt;картой&lt;/strong&gt;&amp;nbsp;&lt;strong&gt;On-Line&lt;/strong&gt;&amp;nbsp;&lt;strong&gt;(только&lt;/strong&gt;&amp;nbsp;&lt;strong&gt;для&lt;/strong&gt;&amp;nbsp;&lt;strong&gt;физических&lt;/strong&gt;&amp;nbsp;&lt;strong&gt;лиц).&lt;/strong&gt;&lt;/span&gt;&lt;br&gt;&lt;br&gt;Для пополнения Вашего Персонального счета On-Line выберите способ оплаты &quot;Банковской картой&quot;. По окончании оформления заказа Вам будет доступна форма оплаты. Для оплаты доступны заказы находящиеся в статусе «Получен» и «Приостановлен». Нажмите &quot;Оплатить&quot;. Далее следуйте инструкциям на сайте платежной системы. Использовать для оплаты разрешается только Вашу личную банковскую карту. Все действия с персональными данными осуществляются при помощи защищенного канала на сервере платежной системы. В систему заказов Bardetail &amp;nbsp;персональные данные о Ваших банковских картах не передаются. В случае, если осуществить платеж не удается, свяжитесь, пожалуйста, с представителем Вашего банка для выяснения причины отказа в платеже.&lt;br&gt;&lt;br&gt;&lt;/span&gt;&lt;span style=&quot;color: rgb(64, 64, 64); line-height: normal; font-size: 12pt;&quot;&gt;&lt;/span&gt;&lt;span style=&quot;color: rgb(64, 64, 64); line-height: normal; font-size: 14pt;&quot;&gt;&lt;strong&gt;Возможные способы получения груза.&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;color: rgb(64, 64, 64); font-size: 14px; line-height: normal;&quot;&gt;&lt;/span&gt;&lt;/font&gt;&lt;p&gt;&lt;/p&gt;&lt;p style=&quot;color: rgb(64, 64, 64); font-size: 14px; line-height: normal;&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;&lt;br&gt;Транспортной компанией до офиса компании.&amp;nbsp;&lt;br&gt;&lt;br&gt;При этом транспортные расходы могут быть как за счет клиента, так и за счет организации, в зависимости от суммы заказа и индивидуальным условиям работы. Мы тесно сотрудничаем с транспортными компаниями: Ратэк, Энергия, ЖелДорЭкспедиция, но можем отправить Ваш товар и любой другой компанией по Вашему усмотрению.&lt;/font&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial&quot;&gt;&lt;span style=&quot;color: rgb(64, 64, 64); line-height: normal; font-size: 12pt;&quot;&gt;&lt;/span&gt;&lt;span style=&quot;color: rgb(64, 64, 64); font-size: 14px; line-height: normal; background-color: rgb(248, 248, 248);&quot;&gt;&lt;/span&gt;&lt;/font&gt;&lt;/p&gt;&lt;p style=&quot;color: rgb(64, 64, 64); font-size: 14px; line-height: normal;&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Из офиса нашей компании.&amp;nbsp;&lt;br&gt;&lt;br&gt;Если Вы хотите забрать товар в городе, где есть наш офис – можете сделать это в рабочее время соответствующего офиса. Способы доставки согласовываются с каждым клиентом индивидуально в начале сотрудничества.&lt;/font&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;color: rgb(64, 64, 64); font-size: 14px; line-height: normal;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;color: rgb(64, 64, 64); font-size: 14px; line-height: normal;&quot;&gt;&lt;br&gt;&lt;/p&gt;\r\n', 'Доставка', '', ''),
(8, 1, 'Contact', '&lt;p&gt;Contact&lt;/p&gt;', 'Contact', '', ''),
(7, 1, 'order info', '&lt;p&gt;order info&lt;/p&gt;', 'order info', '', ''),
(9, 2, 'Услуги по ремонту', '&lt;h4 style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;&lt;b&gt;Наша мастерская занимается ремонтом двигателей БМВ. Приоритетные направления нашей деятельности:&lt;/b&gt;&lt;/font&gt;&lt;/h4&gt;&lt;ul style=&quot;margin-right: 0px; margin-bottom: 0px; margin-left: 40px; padding: 0px; list-style-image: url(&amp;quot;Kartinki/bmw_spisok.png&amp;quot;); font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Ремонт Vanos (Ванос)&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Ремонт исполнительных блоков DISA&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Ремонт КВКГ (в том числе встроенных в клапанную крышку)&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Регулировка фаз ГРМ&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Замена МСК&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Замена поршневых колец&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Замена цепи (цепей)&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Замена прокладки ГБЦ&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Ремонт ГБЦ&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Удаление клапана ЕГР&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Удаление вихревых заслонок дизелей&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Замена шлейфов приборных панелей&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Замена шлейфов MID (радио)&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Компьютерная диагностика.&lt;/font&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Также мы осуществляем продажу ремкомплектов для самостоятельной установки и отправляем комплекты в регионы. В ассортименте присутствуют:&lt;/font&gt;&lt;/p&gt;&lt;ul style=&quot;margin-right: 0px; margin-bottom: 0px; margin-left: 40px; padding: 0px; list-style-image: url(&amp;quot;Kartinki/bmw_spisok.png&amp;quot;); font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Ремкомплекты Ванос&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Ремкомплекты ДИСА&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Мембраны КВКГ&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Заглушки и диффузоры ЕГР&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Вихревые заглушки дизелей&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Шестерни раздаток&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Уплотнения для компрессоров пневмоподвески&lt;/font&gt;&lt;/li&gt;&lt;li style=&quot;margin: 0px; padding: 0px;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Шлейфы, кольца для приборной панели и радио&lt;/font&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Помимо этого продаём и сдаём в аренду специнструмент для выставления фаз ГРМ БМВ. Осуществляем выездную настройку фаз ГРМ и ремонт ваноса.&lt;/font&gt;&lt;/p&gt;&lt;h4 style=&quot;margin: 10px auto 0px; padding: 0px; font-size: 18px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Наши преимущества&lt;/font&gt;&lt;/h4&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Мы бережно относимся к автомобилям наших клиентов и учитываем все пожелания в плане достижения наилучшего качества наших работ.&lt;/font&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Мы не делаем лишних работ и берёмся только за тот ремонт, результат которого можем спрогнозировать.&lt;/font&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;По любому сделанному ремонту готовы рассказать все трудности и нестандартные моменты, замеченные в процессе, чтобы их можно было учесть в дальнейшей эксплуатации автомобиля.&lt;/font&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Мы ценим время клиента и заранее оговариваем максимально точно, насколько возможно, сроки проведения ремонта и требующиеся запчасти.&lt;/font&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;При ремонте мы используем техническую документацию компании БМВ и соблюдаем рекомендации и технические нормативы. Также постоянно накапливаем собственные наработки по тем моментам, которые не отражены в официальной документации.&lt;/font&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Мы не гонимся за сверхприбылью, но считаем, что качественный ремонт не может стоить копейки. В то же время всегда готовы обсудить с клиентом способы снижения стоимости ремонта путём выявления действительно важных и критичных аспектов ремонта и отсеивания второстепенных.&lt;/font&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 0px; padding: 10px 0px; font-size: 14px; font-variant-ligatures: normal; line-height: normal; orphans: 2; widows: 2;&quot;&gt;&lt;font face=&quot;Arial&quot;&gt;Наш девиз: «Чёткость во всём!». Чёткая диагностика проблемы, чёткая стоимость ремонта, чёткое выполнение ремонта и чёткие сроки ремонта!&lt;/font&gt;&lt;/p&gt;', 'Услуги', '', ''),
(9, 1, 'services', '&lt;p&gt;services&lt;br&gt;&lt;/p&gt;', 'services', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `oc_information_to_layout`
--

--
-- Очистить таблицу перед добавлением данных `oc_information_to_layout`
--

TRUNCATE TABLE `oc_information_to_layout`;
--
-- Дамп данных таблицы `oc_information_to_layout`
--

INSERT INTO `oc_information_to_layout` (`information_id`, `store_id`, `layout_id`) VALUES
(4, 0, 0),
(7, 0, 0),
(6, 0, 0),
(8, 0, 0),
(9, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `oc_information_to_store`
--

--
-- Очистить таблицу перед добавлением данных `oc_information_to_store`
--

TRUNCATE TABLE `oc_information_to_store`;
--
-- Дамп данных таблицы `oc_information_to_store`
--

INSERT INTO `oc_information_to_store` (`information_id`, `store_id`) VALUES
(3, 0),
(4, 0),
(5, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0);

--
-- Индексы сохранённых таблиц--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
