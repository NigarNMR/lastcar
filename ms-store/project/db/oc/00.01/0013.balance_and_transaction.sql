ALTER TABLE `oc_customer` ADD `balance_now` DECIMAL(15,4) NOT NULL DEFAULT '0' AFTER `date_added`, ADD `balance_freeze` DECIMAL(15,4) NOT NULL DEFAULT '0' AFTER `balance_now`, ADD `balance_limit` DECIMAL(15,4) NOT NULL DEFAULT '0' AFTER `balance_freeze`;

ALTER TABLE `oc_customer_transaction` ADD `payment_code` INT(3) NOT NULL AFTER `date_added`, ADD `operation_status` INT(3) NOT NULL AFTER `payment_code`;

ALTER TABLE `oc_customer_transaction` CHANGE `order_id` `order_id` INT(11) NULL;

ALTER TABLE `oc_customer_transaction` CHANGE `order_id` `order_product_id` INT(11) NULL DEFAULT NULL;