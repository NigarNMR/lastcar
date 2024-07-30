ALTER TABLE `oc_customer_group` ADD `mark-up` INT(32) NOT NULL AFTER `sort_order`;
ALTER TABLE `oc_customer_group` CHANGE `mark-up` `mark_up` INT(32) NOT NULL;