ALTER TABLE `TDM_GROUP_BRAND` 
ADD `smlr_percent` INT NOT NULL DEFAULT '0' AFTER `b__id`, 
ADD `smlr_checked` BOOLEAN NOT NULL DEFAULT FALSE AFTER `smlr_percent`;

ALTER TABLE `tdm_web_eijen_ru`.`TDM_GROUP_BRAND` ADD INDEX `Percents Index` (`smlr_percent`);