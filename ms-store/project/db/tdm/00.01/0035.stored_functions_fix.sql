DELIMITER $$
CREATE FUNCTION `aliasIdCheck`(`brandId` INT(11)) RETURNS tinyint(1)
BEGIN
	DECLARE existStatus BOOLEAN;
	SET existStatus = EXISTS
		(SELECT UT.parent_id
		FROM (
			SELECT TB1.b__id AS orig_b_id, TB1.b__name AS orig_b_name, TB1.b__parent_id AS parent_id
			FROM TDM_BRANDS TB1
			WHERE TB1.b__parent_id !=0 AND BINARY TB1.b__id = brandId
		) AS UT);
	RETURN existStatus;
END $$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION `getParentIdFunc`(`brandId` INT(11)) RETURNS INT(11)
BEGIN
	/*	Процедура для получения родительского бренда	*/
	/*	для неосновного бренда							*/
	DECLARE parentId INT(11);
	SELECT UT.target_b_id
	INTO parentId
	FROM (
		SELECT TB1.b__id AS orig_b_id, TB1.b__name AS orig_b_name, TB2.b__id AS target_b_id, TB2.b__name AS target_b_name
		FROM TDM_BRANDS TB1
		INNER JOIN TDM_BRANDS TB2 ON TB1.b__parent_id = TB2.b__id
		WHERE TB1.b__parent_id !=0 and TB1.b__id = brandId
	) AS UT;
	RETURN parentId;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS `singleBrandUpdate`;

DELIMITER $$
CREATE PROCEDURE `singleBrandUpdate`(IN `inputBrandId` INT(11))
BEGIN
  DECLARE newBrandId INT(11);
  DECLARE checkHld TINYINT(1);
  
  SELECT aliasIdCheck(inputBrandId) INTO checkHld;
  IF checkHld
  THEN
	BEGIN
	
	SELECT getParentIdFunc(inputBrandId) INTO newBrandId;
	UPDATE TDM_PRICES
	SET BRAND_ID=newBrandId
	WHERE BRAND_ID=inputBrandId;
	UPDATE TDM_LINKS
	SET BRAND_ID1=newBKEY
	WHERE BRAND_ID1=inputBrandId;
	UPDATE TDM_LINKS
	SET BRAND_ID2=newBrandId
	WHERE BRAND_ID2=inputBrandId;
	END;
  END IF;

END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS `pricesUpdate`;

DELIMITER $$
CREATE PROCEDURE `pricesUpdate`(IN `inAKEY` VARCHAR(64) CHARSET utf8mb4, IN `inArticle` VARCHAR(32) CHARSET utf8mb4, IN `inAltName` VARCHAR(128) CHARSET utf8mb4, IN `inAvailable` INT(4), IN `inBrandId` INT(11), IN `inCurrency` VARCHAR(3) CHARSET utf8mb4, IN `inDay` INT(4), IN `inPrice` FLOAT(12,2), IN `inStock` VARCHAR(32) CHARSET utf8mb4, IN `inOptions` VARCHAR(64) CHARSET utf8mb4, IN `inSupplierOptions` VARCHAR(1024) CHARSET utf8mb4, IN `inPriceOrig` FLOAT(12,2), IN `staticHash` VARCHAR(32) CHARSET utf8mb4, IN `dynamicHash` VARCHAR(32) CHARSET utf8mb4, IN `inCode` VARCHAR(32) CHARSET utf8mb4, IN `inType` INT(2), IN `inDate` VARCHAR(10) CHARSET utf8mb4, IN `inSupplier` VARCHAR(32) CHARSET utf8mb4, IN `inPriceSupp` FLOAT(12,2))
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
      END IF;
    END;
  ELSE
    INSERT INTO TDM_PRICES
		(AKEY, ARTICLE, ALT_NAME, AVAILABLE, BRAND_ID,
		CURRENCY, DAY, PRICE, STOCK, OPTIONS, SUPPLIER_OPTIONS,
        PRICE_ORIG, CODE, TYPE, DATE, SUPPLIER, PRICE_SUPP)
    VALUES
		(inAKEY, inArticle, inAltName, inAvailable,
         inBrandId, inCurrency, inDay, inPrice, inStock,
         inOptions, inSupplierOptions, inPriceOrig, inCode, inType,
         inDate, inSupplier, inPriceSupp);
  END IF;
  
END $$
DELIMITER ;