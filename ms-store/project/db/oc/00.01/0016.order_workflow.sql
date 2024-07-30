ALTER TABLE `oc_customer_transaction`
  	DROP `product_id`,
  	DROP `quantity`,
  	DROP `price`;

ALTER TABLE `oc_order_product` 
	ADD `acquiring_price` DECIMAL(15,4) NOT NULL DEFAULT '0' AFTER `order_product_status_id`, 
	ADD `fragmentation_status` TINYINT(1) NOT NULL DEFAULT '0' AFTER `acquiring_price`, 
	ADD `fragmentation_date` DATETIME NULL AFTER `fragmentation_status`, 
	ADD `fragmentation_description` TEXT NOT NULL AFTER `fragmentation_date`, 
	ADD `order_product_parent_id` INT(11) NOT NULL DEFAULT '0' AFTER `fragmentation_description`;

ALTER TABLE `oc_order_product_status` 
	ADD `status_parent_id` INT(11) NOT NULL DEFAULT '0' AFTER `text_color`;

TRUNCATE TABLE `oc_order_product_status`;

INSERT INTO `oc_order_product_status` (`order_product_status_id`, `language_id`, `name`, `required`, `bg_color`, `text_color`, `status_parent_id`) VALUES
(2, 1, 'Pull off', 0, 'e6e6e6', '000000', 7),
(3, 2, 'В доставке', 0, 'feffb8', 'bd8842', 16),
(5, 2, 'Выдано', 0, 'c6ffa3', '000000', 0),
(9, 2, 'Пришло на склад', 0, 'feffd1', '800080', 16),
(10, 2, 'Утилизированно', 0, '#ffffff', '#000000', 7),
(12, 2, 'Приостановлен', 0, 'b8c9ff', '000000', 16),
(13, 1, 'In transit', 0, 'ffd1ed', '800080', 16),
(16, 2, 'В работе', 0, 'ff8afd', '000000', 0),
(2, 2, 'Снято', 0, 'e6e6e6', '000000', 7),
(3, 1, 'Shipped', 0, 'feffb8', 'bd8842', 16),
(7, 2, 'Отказ', 0, 'ff5266', '000000', 0),
(5, 1, 'Complete', 0, 'c6ffa3', '000000', 0),
(8, 2, 'Пришло на склад региона', 0, '008000', 'ffff00', 16),
(9, 1, 'Canceled Reversal', 0, 'feffd1', '800080', 16),
(10, 1, 'Failed', 0, '#ffffff', '#000000', 7),
(11, 2, 'Закуплено', 0, 'a8ffa3', '800080', 16),
(13, 2, 'В пути', 0, 'ffd1ed', '800080', 16),
(1, 1, 'Получен', 0, '', '', 0),
(15, 2, 'Ожидает выдачи', 0, 'ffffff', '938fff', 16),
(14, 2, 'Пришло на склад МСК', 0, 'b7e1bf', '000000', 16),
(7, 1, 'Canceled', 0, 'ff5266', '000000', 0),
(1, 2, 'Получен', 0, '', '', 0),
(16, 1, 'Voided', 0, 'ff8afd', '000000', 0),
(12, 1, 'Reversed', 0, 'b8c9ff', '000000', 16),
(11, 1, 'Refunded', 0, 'a8ffa3', '800080', 16),
(8, 1, 'Сame to  warehouse region', 0, '008000', 'ffff00', 16),
(14, 1, 'On a warehouse ', 0, 'b7e1bf', '000000', 16),
(15, 1, 'Awaits extradition', 0, 'ffffff', '938fff', 16),
(17, 2, 'Возврат', 0, '', '', 0),
(17, 1, 'Return', 0, '', '000000', 0);

CREATE TABLE IF NOT EXISTS `oc_order_product_transaction_dependencies` (
  `previous_status_id` int(11) NOT NULL,
  `new_status_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `operation_id` int(11) NOT NULL,
  UNIQUE KEY `previous_status_id` (`previous_status_id`,`new_status_id`,`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `oc_order_product_transaction_dependencies` (`previous_status_id`, `new_status_id`, `account_id`, `operation_id`) VALUES
(1, 1, 1, 0),
(1, 1, 2, 0),
(1, 5, 1, 0),
(1, 5, 2, 0),
(1, 7, 1, 0),
(1, 7, 2, 0),
(1, 16, 1, 0),
(1, 16, 2, 1),
(1, 17, 1, 0),
(1, 17, 2, 0),
(5, 1, 1, 0),
(5, 1, 2, 0),
(5, 5, 1, 0),
(5, 5, 2, 0),
(5, 7, 1, 0),
(5, 7, 2, 0),
(5, 16, 1, 0),
(5, 16, 2, 0),
(5, 17, 1, 1),
(5, 17, 2, 0),
(7, 1, 1, 0),
(7, 1, 2, 0),
(7, 5, 1, 0),
(7, 5, 2, 0),
(7, 7, 1, 0),
(7, 7, 2, 0),
(7, 16, 1, 0),
(7, 16, 2, 1),
(7, 17, 1, 0),
(7, 17, 2, 0),
(16, 1, 1, 0),
(16, 1, 2, 0),
(16, 5, 1, 2),
(16, 5, 2, 2),
(16, 7, 1, 0),
(16, 7, 2, 2),
(16, 16, 1, 0),
(16, 16, 2, 0),
(16, 17, 1, 0),
(16, 17, 2, 0),
(17, 1, 1, 0),
(17, 1, 2, 0),
(17, 5, 1, 0),
(17, 5, 2, 0),
(17, 7, 1, 0),
(17, 7, 2, 0),
(17, 16, 1, 0),
(17, 16, 2, 0),
(17, 17, 1, 0),
(17, 17, 2, 0);

CREATE TABLE IF NOT EXISTS `oc_order_status_product_status_correlation` (
  `order_status_id` int(11) NOT NULL,
  `order_product_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `oc_order_status_product_status_correlation` (`order_status_id`, `order_product_status_id`) VALUES
(1, 1),
(5, 5),
(9, 16),
(11, 17),
(13, 7);