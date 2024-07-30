CREATE TABLE IF NOT EXISTS `TDM_CATALOG_ALIAS` (
  `tca_group_id` int(11) NOT NULL,
  `tca_catalog_id` int(6) NOT NULL,
  `tca_brand_name` varchar(100) DEFAULT NULL,
  UNIQUE KEY `group_catalog_key` (`tca_group_id`,`tca_catalog_id`) COMMENT 'Ключ по паре группа бренда + каталог'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `TDM_WS_ALIAS` (
  `twa_group_id` int(11) NOT NULL,
  `twa_ws_id` int(6) NOT NULL,
  `twa_brand_name` varchar(100) DEFAULT NULL,
  UNIQUE KEY `ws_group_key` (`twa_group_id`,`twa_ws_id`) COMMENT 'Ключ по паре группа бренда + web-поставщик'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Сопоставление группам имени бренда, который находится у web-поставщиков';

ALTER TABLE  `TDM_WS_TIME` DROP  `BKEY` , DROP  `AKEY` ;