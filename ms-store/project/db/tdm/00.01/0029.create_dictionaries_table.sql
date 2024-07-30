CREATE TABLE `TDM_DICTIONARIES` (
  `REC_ID` int(11) NOT NULL,
  `REPL_BRAND` varchar(100) NOT NULL,
  `DESC_BRAND` varchar(100) NOT NULL,
  `DICT_CODE` varchar(32) NOT NULL,
  `CONNECTED` tinyint(1) NOT NULL DEFAULT '0',
  `GROUP_ID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `TDM_DICTIONARIES`
  ADD PRIMARY KEY (`REC_ID`),
  ADD UNIQUE KEY `BrandLink` (`REPL_BRAND`,`DICT_CODE`) USING BTREE;

ALTER TABLE `TDM_DICTIONARIES`
  MODIFY `REC_ID` int(11) NOT NULL AUTO_INCREMENT;