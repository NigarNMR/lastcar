DELIMITER $$
CREATE PROCEDURE pricesUpdate(IN inAKEY VARCHAR(64), IN inBKEY VARCHAR(64),
    						  IN inArticle VARCHAR(32), IN inAltName VARCHAR(128),
                              IN inAvailable INT(4), IN inBrand VARCHAR(32),
                              IN inCurrency VARCHAR(3), IN inDay INT(4),
                              IN inPrice FLOAT(12,2), IN inStock VARCHAR(32),
                              IN inOptions VARCHAR(64), IN inSupplierOptions VARCHAR(128),
                              IN inPriceOrig FLOAT(12,2), IN staticHash VARCHAR(32),
                              IN dynamicHash VARCHAR(32), IN inCode VARCHAR(32),
							  IN inType INT(2), IN inDate VARCHAR(10),
							  IN inSupplier VARCHAR(32))
BEGIN
	IF EXISTS (SELECT * FROM TDM_PRICES WHERE STATIC_MD5=staticHash)
	THEN 
		BEGIN
			IF EXISTS (SELECT * FROM TDM_PRICES WHERE STATIC_MD5=staticHash AND DYNAMIC_MD5=dynamicHash)
			THEN 
				UPDATE TDM_PRICES
				SET
					AVAILABLE=inAvailable,
					CURRENCY=inCurrency,
					DAY=inDay,
					PRICE=inPrice,
					PRICE_ORIG=inPriceOrig
				WHERE
					STATIC_MD5=staticHash;
			END IF;
		END;
	ELSE
		INSERT INTO TDM_PRICES
		(AKEY, BKEY, ARTICLE, ALT_NAME, AVAILABLE, BRAND,
		CURRENCY, DAY, PRICE, STOCK, OPTIONS, SUPPLIER_OPTIONS,
        PRICE_ORIG, CODE, TYPE, DATE, SUPPLIER)
		VALUES
		(inAKEY, inBKEY, inArticle, inAltName, inAvailable,
         inBrand, inCurrency, inDay, inPrice, inStock,
         inOptions, inSupplierOptions, inPriceOrig, inCode, inType,
         inDate, inSupplier);
	END IF;
	
END $$
DELIMITER ;