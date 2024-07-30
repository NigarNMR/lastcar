ALTER TABLE  `TDM_BRANDS` ADD  `BKEY` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;

SET GLOBAL log_bin_trust_function_creators = 1;

DELIMITER //
CREATE FUNCTION `aliasCheck`(`brandName` VARCHAR(100)) RETURNS tinyint(1)
BEGIN
	DECLARE existStatus BOOLEAN;
	SET existStatus = EXISTS
		(SELECT UT.parent_id
		FROM (
			SELECT TB1.b__id AS orig_b_id, TB1.b__name AS orig_b_name, TB1.b__parent_id AS parent_id
			FROM TDM_BRANDS TB1
			WHERE TB1.b__parent_id !=0 AND BINARY TB1.b__name = brandName
		) AS UT);
	RETURN existStatus;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `clearBrand`(IN `inputString` VARCHAR(100), OUT `outputString` VARCHAR(100))
BEGIN

	SET inputString = UPPER(inputString);
	
	SET inputString = REPLACE(inputString,'Ë','E');
	SET inputString = REPLACE(inputString,'Ö','O');
	SET inputString = REPLACE(inputString,'Ò','O');
	SET inputString = REPLACE(inputString,'Ä','A');
	SET inputString = REPLACE(inputString,'Ü','U');
	SET inputString = REPLACE(inputString,'O\'','O');
	SET inputString = REPLACE(inputString,'№','');

	SET outputString = REGEXP_REPLACE(inputString , '[^A-ZА-Я0-9a-zа-я]', '');
END; //
DELIMITER ;

DELIMITER //
CREATE FUNCTION `clearBrandFunc`( `inputString` VARCHAR(100)) RETURNS varchar(100) CHARSET utf8
BEGIN
	SET inputString = UPPER(inputString);
	SET inputString = REPLACE(inputString,'Ë','E');
	SET inputString = REPLACE(inputString,'Ö','O');
	SET inputString = REPLACE(inputString,'Ò','O');
	SET inputString = REPLACE(inputString,'Ä','A');
	SET inputString = REPLACE(inputString,'Ü','U');
	SET inputString = REPLACE(inputString,'O\'','O');
	SET inputString = REPLACE(inputString,'№','');
	SET `inputString` = REGEXP_REPLACE(`inputString` , '[^A-ZА-Я0-9a-zа-я]', '');
	RETURN `inputString`;
END; //
DELIMITER ;

DELIMITER //
CREATE FUNCTION `getParentFunc`(`brandName` VARCHAR(100)) RETURNS varchar(100) CHARSET utf8
    NO SQL
BEGIN
	/*	Процедура для получения родительского бренда	*/
	/*	для неосновного бренда							*/
	DECLARE parentName VARCHAR(100);
	SELECT UT.target_b_name
	INTO parentName
	FROM (
		SELECT TB1.b__id AS orig_b_id, TB1.b__name AS orig_b_name, TB2.b__id AS target_b_id, TB2.b__name AS target_b_name
		FROM TDM_BRANDS TB1
		INNER JOIN TDM_BRANDS TB2 ON TB1.b__parent_id = TB2.b__id
		WHERE TB1.b__parent_id !=0 and TB1.b__name = brandName
	) AS UT;
	RETURN parentName;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `insertBrand`(IN newBrandName VARCHAR(100))
BEGIN
	IF (NOT EXISTS(
        SELECT * FROM TDM_BRANDS WHERE binary b__name=newBrandName
    ))
	THEN
		INSERT INTO TDM_BRANDS (b__name,b__parent_id,b__status) VALUES (newBrandName,'0','0');
	END IF;
END; //
DELIMITER ;

DELIMITER //
CREATE TRIGGER `InsertAliasBrand` 
BEFORE INSERT ON `TDM_BRANDS`
FOR EACH ROW 
BEGIN
	CALL clearBrand(NEW.b__name, NEW.BKEY);
END; //
DELIMITER ;

DELIMITER //
CREATE TRIGGER `UpdateAliasBrand`
BEFORE UPDATE ON `TDM_BRANDS`
FOR EACH ROW
BEGIN
	CALL clearBrand(NEW.b__name,NEW.BKEY);
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `tablesBrandUpdate`()
BEGIN
DECLARE n INT DEFAULT 0;
DECLARE i INT DEFAULT 0;
DECLARE oldBrandName VARCHAR(100);
DECLARE oldBKEY VARCHAR(100);
SELECT COUNT(*) FROM (SELECT BRAND FROM TDM_PRICES GROUP BY BINARY BRAND) as SortBrands INTO n;
SET i=0;
WHILE i<n DO 
  SELECT SortBrands.BKEY, SortBrands.BRAND INTO oldBrandName, oldBKEY FROM
    (SELECT BRAND, BKEY	FROM TDM_PRICES	GROUP BY BINARY BRAND) as SortBrands
  LIMIT i,1;
  IF aliasCheck(oldBrandName)
  THEN
	BEGIN
	DECLARE newBrandName VARCHAR(100);
	DECLARE newBKEY VARCHAR(100);
	SET newBrandName = getParentFunc(oldBrandName);
	SET newBKEY = clearBrandFunc(newBrandName);
	UPDATE TDM_PRICES
	SET BRAND=newBrandName, BKEY=newBKEY
	WHERE BRAND=oldBrandName;
	UPDATE TDM_LINKS
	SET BKEY1=newBKEY,PKEY1=CONCAT(newBKEY,AKEY1)
	WHERE BKEY1=oldBKEY;
	UPDATE TDM_LINKS
	SET BKEY2=newBKEY,PKEY2=CONCAT(newBKEY,AKEY2)
	WHERE BKEY2=oldBKEY;
	END;
  ELSE
	CALL insertBrand(oldBrandName);
  END IF;
  SET i = i + 1;
END WHILE;
END; //
DELIMITER ;

UPDATE TDM_BRANDS SET BKEY = clearBrandFunc(b__name);