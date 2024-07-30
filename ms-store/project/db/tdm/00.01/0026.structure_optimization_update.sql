DROP PROCEDURE IF EXISTS `pricesGroupNullify`;
DELIMITER $$
CREATE PROCEDURE `pricesGroupNullify`(IN `inBKEY` VARCHAR(64) CHARSET utf8mb4, IN `inAKEY` VARCHAR(64) CHARSET utf8mb4, IN `inCode` VARCHAR(32) CHARSET utf8mb4)
BEGIN

  DECLARE done INT DEFAULT FALSE;
  DECLARE vFIELD VARCHAR(32);
  DECLARE vFirstGo INT DEFAULT TRUE;
  DECLARE sqlInput VARCHAR(2048);

  DECLARE groupsCursor CURSOR FOR
    (SELECT SUBSTRING( FIELD, 16 ) AS FIELD
    FROM  TDM_SETTINGS 
    WHERE  FIELD LIKE  '%PRICE_DISCOUNT_%');

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  SET sqlInput = '';

  OPEN groupsCursor;

  read_loop: LOOP

  
  FETCH groupsCursor INTO vFIELD;
  
  IF done THEN
    LEAVE read_loop;
  END IF;
  
  IF vFirstGo THEN
    SET vFirstGo = FALSE;
  ELSE
    SET sqlInput = CONCAT(sqlInput,',');
  END IF;

  SET sqlInput = CONCAT(sqlInput,'PRICE_ID_',vFIELD,'=0');

  END LOOP;

  CLOSE groupsCursor;

  SET @sqlLine = CONCAT(
  	'UPDATE TDM_PRICES SET ', sqlInput,' WHERE CODE=''',inCODE,''',BKEY=''',inBKEY,''',AKEY=''',inAKEy,''';'
  );
  PREPARE newStmt FROM @sqlLine;
  EXECUTE newStmt;
  DEALLOCATE PREPARE newStmt;

END $$
DELIMITER ;



DROP PROCEDURE IF EXISTS `pricesGroupUpdate`;
DELIMITER $$
CREATE PROCEDURE `pricesGroupUpdate`(IN `inStatMD5` VARCHAR(32) CHARSET utf8mb4)
BEGIN

  DECLARE done INT DEFAULT FALSE;
  DECLARE vFIELD VARCHAR(32);
  DECLARE vVALUE INT;
  DECLARE vFirstGo INT DEFAULT TRUE;
  DECLARE sqlInput VARCHAR(2048);

  DECLARE groupsCursor CURSOR FOR
    (SELECT SUBSTRING( FIELD, 16 ) AS FIELD, CAST( VALUE AS INTEGER ) AS VALUE
    FROM  TDM_SETTINGS 
    WHERE  FIELD LIKE  '%PRICE_DISCOUNT_%');

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  SET sqlInput = '';

  OPEN groupsCursor;

  read_loop: LOOP
  
  FETCH groupsCursor INTO vFIELD,vVALUE;
  
  IF done THEN
    LEAVE read_loop;
  END IF;
  
  IF vFirstGo THEN
    SET vFirstGo = FALSE;
  ELSE
    SET sqlInput = CONCAT(sqlInput,',');
  END IF;

  SET sqlInput = CONCAT(sqlInput,'PRICE_ID_',vFIELD,'=CEIL(PRICE_SUPP*(1+',vVALUE,'/100))');
  
  END LOOP;

  CLOSE groupsCursor;

  SET @sqlLine = CONCAT(
    'UPDATE TDM_PRICES SET ', sqlInput,' WHERE STATIC_MD5=''',inStatMD5,''';'
  );
  PREPARE newStmt FROM @sqlLine;
  EXECUTE newStmt;
  DEALLOCATE PREPARE newStmt;

END $$
DELIMITER ;



DROP PROCEDURE IF EXISTS `pricesNullify`;
DELIMITER $$
CREATE PROCEDURE `pricesNullify`(IN `inBKEY` VARCHAR(64) CHARSET utf8mb4, IN `inAKEY` VARCHAR(64) CHARSET utf8mb4, IN `inCODE` VARCHAR(32) CHARSET utf8mb4, IN `inTIME` VARCHAR(10) CHARSET utf8mb4)
BEGIN
  UPDATE 
    TDM_PRICES
  SET 
    AVAILABLE='0',
    DAY='0',
    PRICE='0',
    PRICE_ORIG='0',
    PRICE_SUPP='0',
    DATE=inTIME 
  WHERE 
    BKEY=inBKEY 
    AND
    AKEY=inAKEY
    AND
    CODE=inCODE;
    CALL pricesGroupNullify(inBKEY, inAKEY, inCODE);
END $$
DELIMITER ;



DROP PROCEDURE IF EXISTS `pricesUpdate`;
DELIMITER $$
CREATE PROCEDURE `pricesUpdate`(IN `inAKEY` VARCHAR(64) CHARSET utf8mb4, IN `inBKEY` VARCHAR(64) CHARSET utf8mb4, IN `inArticle` VARCHAR(32) CHARSET utf8mb4, IN `inAltName` VARCHAR(128) CHARSET utf8mb4, IN `inAvailable` INT(4), IN `inBrand` VARCHAR(32) CHARSET utf8mb4, IN `inCurrency` VARCHAR(3) CHARSET utf8mb4, IN `inDay` INT(4), IN `inPrice` FLOAT(12,2), IN `inStock` VARCHAR(32) CHARSET utf8mb4, IN `inOptions` VARCHAR(64) CHARSET utf8mb4, IN `inSupplierOptions` VARCHAR(1024) CHARSET utf8mb4, IN `inPriceOrig` FLOAT(12,2), IN `staticHash` VARCHAR(32) CHARSET utf8mb4, IN `dynamicHash` VARCHAR(32) CHARSET utf8mb4, IN `inCode` VARCHAR(32) CHARSET utf8mb4, IN `inType` INT(2), IN `inDate` VARCHAR(10) CHARSET utf8mb4, IN `inSupplier` VARCHAR(32) CHARSET utf8mb4, IN `inPriceSupp` FLOAT(12,2))
BEGIN
  IF EXISTS (SELECT * FROM TDM_PRICES WHERE STATIC_MD5=staticHash)
  THEN 
    BEGIN
      IF EXISTS (SELECT * FROM TDM_PRICES WHERE STATIC_MD5=staticHash AND DYNAMIC_MD5!=dynamicHash)
      THEN 
        UPDATE TDM_PRICES
        SET
          AVAILABLE=inAvailable,
          CURRENCY=inCurrency,
          DAY=inDay,
          PRICE=inPrice,
          PRICE_ORIG=inPriceOrig,
          PRICE_SUPP=inPriceSupp
        WHERE
          STATIC_MD5=staticHash;
                CALL pricesGroupUpdate(staticHash);
      END IF;
    END;
  ELSE
    INSERT INTO TDM_PRICES
    (AKEY, BKEY, ARTICLE, ALT_NAME, AVAILABLE, BRAND,
    CURRENCY, DAY, PRICE, STOCK, OPTIONS, SUPPLIER_OPTIONS,
        PRICE_ORIG, CODE, TYPE, DATE, SUPPLIER, PRICE_SUPP)
    VALUES
    (inAKEY, inBKEY, inArticle, inAltName, inAvailable,
         inBrand, inCurrency, inDay, inPrice, inStock,
         inOptions, inSupplierOptions, inPriceOrig, inCode, inType,
         inDate, inSupplier, inPriceSupp);
         CALL pricesGroupUpdate(staticHash);
  END IF;
  
END $$
DELIMITER ;


DROP PROCEDURE IF EXISTS `updateGroupPriceColumn`;
DELIMITER $$
CREATE PROCEDURE `updateGroupPriceColumn`(IN `grID` INT)
BEGIN

  SET @sqlGetExtra = CONCAT(
    'SELECT CAST( VALUE AS INTEGER ) AS VALUE\r\n    INTO @grExtra\r\n    FROM  TDM_SETTINGS\r\n    WHERE  FIELD =''PRICE_DISCOUNT_',grID,''';'
  );
  PREPARE getExtraStmt FROM @sqlGetExtra;
  EXECUTE getExtraStmt;
  DEALLOCATE PREPARE getExtraStmt;

  SET @sqlExec = CONCAT(
    'UPDATE TDM_PRICES SET PRICE_ID_',grID,'=CEIL(PRICE_SUPP*(1+',@grExtra,'/100));'
  );
  PREPARE execStmt FROM @sqlExec;
  EXECUTE execStmt;
  DEALLOCATE PREPARE execStmt;

END $$
DELIMITER ;


ALTER TABLE `TDM_BRANDS`
  ADD PRIMARY KEY (`b__id`),
  ADD UNIQUE KEY `idxTDM_BRANDS_b__id` (`b__id`),
  ADD UNIQUE KEY `Id-ParentId` (`b__id`,`b__name`);

ALTER TABLE `TDM_BRANDS` CHANGE `b__name` `b__name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;

ALTER TABLE `TDM_BRANDS`
  MODIFY `b__id` int(11) NOT NULL AUTO_INCREMENT;

DELIMITER $$
CREATE TRIGGER `InsertAliasBrand` BEFORE INSERT ON `TDM_BRANDS` FOR EACH ROW BEGIN
  CALL clearBrand(NEW.b__name, NEW.BKEY);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `UpdateAliasBrand` BEFORE UPDATE ON `TDM_BRANDS` FOR EACH ROW BEGIN
  CALL clearBrand(NEW.b__name,NEW.BKEY);
END
$$
DELIMITER ;





DELIMITER $$
CREATE TRIGGER `InsertPrice` BEFORE INSERT ON `TDM_PRICES` FOR EACH ROW BEGIN
SET NEW.PHID = md5(CONCAT(NEW.BKEY, NEW.AKEY, NEW.ARTICLE, NEW.ALT_NAME, NEW.BRAND, NEW.PRICE, NEW.TYPE, NEW.CURRENCY, NEW.DAY, NEW.AVAILABLE, NEW.SUPPLIER, NEW.STOCK, NEW.OPTIONS, NEW.CODE, NEW.DATE));
SET NEW.STATIC_MD5 = md5(CONCAT(NEW.BKEY, NEW.AKEY, NEW.ARTICLE, NEW.BRAND, NEW.SUPPLIER, NEW.STOCK, NEW.CODE, NEW.SUPPLIER_OPTIONS));
SET NEW.DYNAMIC_MD5 = md5(CONCAT(NEW.DAY,NEW.PRICE,NEW.AVAILABLE, NEW.CURRENCY));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `UpdatePrice` BEFORE UPDATE ON `TDM_PRICES` FOR EACH ROW BEGIN
SET NEW.PHID = md5(CONCAT(NEW.BKEY, NEW.AKEY, NEW.ARTICLE, NEW.ALT_NAME, NEW.BRAND, NEW.PRICE, NEW.TYPE, NEW.CURRENCY, NEW.DAY, NEW.AVAILABLE, NEW.SUPPLIER, NEW.STOCK, NEW.OPTIONS, NEW.CODE, NEW.DATE));
SET NEW.DYNAMIC_MD5 = md5(CONCAT(NEW.DAY,NEW.PRICE,NEW.AVAILABLE,NEW.CURRENCY));
END
$$
DELIMITER ;

ALTER TABLE `TDM_PRICES`
  ADD PRIMARY KEY (`PRODUCT_ID`),
  ADD KEY `AKEY` (`BKEY`,`AKEY`) USING HASH;

ALTER TABLE `TDM_PRICES`
  MODIFY `PRODUCT_ID` int(12) NOT NULL AUTO_INCREMENT;



ALTER TABLE `TDM_WS` ADD `RQ_ONLY` BOOLEAN NOT NULL DEFAULT FALSE AFTER `REFRESH_TIME`, ADD `Q_LIMIT` INT(11) NOT NULL DEFAULT '3' AFTER `RQ_ONLY`;



DELETE FROM TDM_SETTINGS WHERE ITEM='pricetype';
INSERT INTO `TDM_SETTINGS` (`ITEM`, `FIELD`, `VALUE`) VALUES
('pricetype', 'PRICE_DISCOUNT_1', '55'),
('pricetype', 'PRICE_DISCOUNT_2', '35'),
('pricetype', 'PRICE_DISCOUNT_3', '20'),
('pricetype', 'PRICE_DISCOUNT_4', '14'),
('pricetype', 'PRICE_DISCOUNT_5', '8'),
('pricetype', 'PRICE_GID_1', '1'),
('pricetype', 'PRICE_GID_2', '2'),
('pricetype', 'PRICE_GID_3', '3'),
('pricetype', 'PRICE_GID_4', '4'),
('pricetype', 'PRICE_GID_5', '5'),
('pricetype', 'PRICE_TYPE_1', 'Розница'),
('pricetype', 'PRICE_TYPE_2', 'Оптовые продажи'),
('pricetype', 'PRICE_TYPE_3', 'Партнеры'),
('pricetype', 'PRICE_TYPE_4', 'Дилеры'),
('pricetype', 'PRICE_TYPE_5', 'Скидка'),
('pricetype', 'PRICE_VIEW_1', '2'),
('pricetype', 'PRICE_VIEW_2', '2'),
('pricetype', 'PRICE_VIEW_3', '2'),
('pricetype', 'PRICE_VIEW_4', '2'),
('pricetype', 'PRICE_VIEW_5', '2');