DROP PROCEDURE IF EXISTS `pricesGroupUpdate`;

DELIMITER $$
CREATE PROCEDURE `pricesGroupUpdate`(IN `inStatMD5` VARCHAR(32))
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

  /* Построчное чтение списка настроек групп */
  FETCH groupsCursor INTO vFIELD,vVALUE;
  /* Выход из цикла */
  IF done THEN
    LEAVE read_loop;
  END IF;
  
  /* Проверка на первый проход цикла для добавления разделителя в строку */
  IF vFirstGo THEN
    SET vFirstGo = FALSE;
  ELSE
    SET sqlInput = CONCAT(sqlInput,',');
  END IF;

  /* Добавляем к строке ИМЯ_ЯЧЕЙКИ=ЗНАЧЕНИЕ_ЯЧЕЙКИ */
  SET sqlInput = CONCAT(sqlInput,'PRICE_ID_',vFIELD,'=CEIL(PRICE_SUPP*(1+',vVALUE,'/100))');

  END LOOP;

  /*  */
  SET @sqlLine = CONCAT(
  	'UPDATE TDM_PRICES SET ', sqlInput,' WHERE STATIC_MD5=\'',inStatMD5,'\';'
  );
  PREPARE newStmt FROM @sqlLine;
  EXECUTE newStmt;
  DEALLOCATE PREPARE newStmt;

  CLOSE groupsCursor;

END $$
DELIMITER ;