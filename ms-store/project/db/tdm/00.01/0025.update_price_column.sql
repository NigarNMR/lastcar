DROP PROCEDURE IF EXISTS updateGroupPriceColumn;

DELIMITER $$
CREATE PROCEDURE updateGroupPriceColumn( IN grID INT )
BEGIN

  SET @sqlGetExtra = CONCAT(
  	'SELECT CAST( VALUE AS INTEGER ) AS VALUE
    INTO @grExtra
    FROM  TDM_SETTINGS
    WHERE  FIELD =\'PRICE_DISCOUNT_',grID,'\';'
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
