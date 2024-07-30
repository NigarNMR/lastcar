ALTER TABLE  `TDM_PRICES` ADD  `PHID` VARCHAR( 32 ) NOT NULL COMMENT  'Идентификатор товара(md5)';

CREATE TRIGGER InsertPrice
  BEFORE INSERT ON TDM_PRICES
  FOR EACH ROW
    SET NEW.PHID = md5(CONCAT(NEW.BKEY, NEW.AKEY, NEW.ARTICLE, NEW.ALT_NAME, NEW.BRAND, NEW.PRICE, NEW.TYPE, NEW.CURRENCY, NEW.DAY, NEW.AVAILABLE, NEW.SUPPLIER, NEW.STOCK, NEW.OPTIONS, NEW.CODE, NEW.DATE))

CREATE TRIGGER `UpdatePrice` BEFORE UPDATE ON `TDM_PRICES` FOR EACH ROW SET NEW.PHID = md5(CONCAT(NEW.BKEY, NEW.AKEY, NEW.ARTICLE, NEW.ALT_NAME, NEW.BRAND, NEW.PRICE, NEW.TYPE, NEW.CURRENCY, NEW.DAY, NEW.AVAILABLE, NEW.SUPPLIER, NEW.STOCK, NEW.OPTIONS, NEW.CODE, NEW.DATE))