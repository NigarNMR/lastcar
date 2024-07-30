DROP PROCEDURE IF EXISTS `pricesGroupNullify`;

DELIMITER $$
CREATE PROCEDURE `pricesGroupNullify`(IN `inBKEY` VARCHAR(64), IN `inAKEY` VARCHAR(64), IN `inCode` VARCHAR(32)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN

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

  /* Построчное чтение списка настроек групп */
  FETCH groupsCursor INTO vFIELD;
  /* Выход из цикла */
  IF done THEN
    LEAVE read_loop;
  END IF;
  
  IF vFirstGo THEN
    SET vFirstGo = FALSE;
  ELSE
    SET sqlInput = CONCAT(sqlInput,',');
  END IF;

  SET sqlInput = CONCAT(sqlInput,'PRICE_ID_',vFIELD,'=0');
  
  /* Подготовка запроса на обновление группы и его выполнение */

  END LOOP;

  SET @sqlLine = CONCAT(
  	'UPDATE TDM_PRICES SET ', sqlInput,' WHERE CODE=\'',inCODE,'\',BKEY=\'',inBKEY,'\',AKEY=\'',inAKEy,'\';'
  );
  PREPARE newStmt FROM @sqlLine;
  EXECUTE newStmt;
  DEALLOCATE PREPARE newStmt;

  CLOSE groupsCursor;

END $$
DELIMITER ;