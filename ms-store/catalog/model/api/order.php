<?php
class ModelApiOrder extends Model {
    
    public function getAscendantData($ascendant_product_id) {
        $query = $this->db->query("SELECT op.*, ops.bg_color as bg_color, ops.text_color as text_color, ops.name as ops_name, o.currency_code, o.currency_value  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id INNER JOIN " . DB_PREFIX . "order o ON op.order_id = o.order_id WHERE op.order_product_id = '" . (int) $ascendant_product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        
        $current_product_data = $query->row;
        
        if (intval($current_product_data['order_product_parent_id'])) {
            $current_product_data = $this->getAscendantData($current_product_data['order_product_parent_id']);
        }
        
        return $current_product_data;
    }
    
    public function getDescendantsList($product_root_id) {
        $product_tree = $this->getProductBranch($product_root_id);
        $product_tree_leaves = $this->getBranchLeaves($product_tree);
        
        return $product_tree_leaves;
    }
    
    public function getAscendantStatus($order_product_status_id) {
        $query = $this->db->query("SELECT status_parent_id FROM " . DB_PREFIX . "order_product_status WHERE order_product_status_id = '" . (int) $order_product_status_id . "';");
        
        if (intval($query->row['status_parent_id'])) {
            return intval($query->row['status_parent_id']);
        } else {
            return $order_product_status_id;
        }
    }
    
    public function getProductBranch($product_id) {
        // Выбор всех потомков по переданному product_id
        $query = $this->db->query("SELECT op.*, ops.bg_color as bg_color, ops.text_color as text_color, ops.name as status  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id WHERE op.order_product_parent_id = '" . $product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        $ar_results = array();
        $ar_pre_results = array();

        // Проверка полученных данных на пустоту
        if ($query->num_rows) {
            $ar_pre_results = $query->rows;
            // Заполнение массива по каждой найденной позиции
            foreach ($ar_pre_results as $ar_pre_result) {
                $branch_data = array();
                // Получение информации по потомку
                $branch_data['data'] = $ar_pre_result;
                // Если потомок также разбит на части, то вызов этой самой функции на него
                if ($ar_pre_result['fragmentation_status']) {
                    $branch_data['branch'] = $this->getProductBranch($ar_pre_result['order_product_id']);
                } else {
                    $branch_data['branch'] = array();
                }
                $ar_results[] = $branch_data;
                unset($branch_data);
            }
        }
        return $ar_results;
    }
    
    public function getRootData($descendant_product_id) {
        $query = $this->db->query("SELECT op.*, ops.bg_color as bg_color, ops.text_color as text_color, ops.name as ops_name, o.currency_code, o.currency_value  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id INNER JOIN " . DB_PREFIX . "order o ON op.order_id = o.order_id WHERE op.order_product_id = '" . (int) $descendant_product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        
        $current_product_data = $query->row;
        
        if (intval($current_product_data['order_product_parent_id'])) {
            $current_product_data = $this->getAscendantData($current_product_data['order_product_parent_id']);
        }
        
        return $current_product_data;
    }
    
    public function getBranchLeaves($parent_branch) {
        $ar_result = array();
        foreach ($parent_branch as $branch) {
            if (empty($branch['branch'])) {
                $ar_result[] = $branch['data'];
            } else {
                $ar_result = array_merge($ar_result, $this->getBranchLeaves($branch['branch']));
            }
        }
        return $ar_result;
    }
    
    public function getSiblingsProductStatusList($descendant_product_id) {
        $query = $this->db->query("SELECT op.*, ops.bg_color as bg_color, ops.text_color as text_color, ops.name as ops_name, o.currency_code, o.currency_value  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id INNER JOIN " . DB_PREFIX . "order o ON op.order_id = o.order_id WHERE op.order_product_id = '" . (int) $descendant_product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        
        $current_product_data = $query->row;
        
        if (intval($current_product_data['order_product_parent_id'])) {
            $current_product_data = $this->getAscendantData($current_product_data['order_product_parent_id']);
        }
        
        $descendants_data = $this->getDescendantsList($current_product_data['order_product_id']);
        
        $descendants_status_list = array();
        
        foreach ($descendants_data as $descendant_data) {
            $descendants_status_list[] = intval($descendant_data['order_product_status_id']);
        }
        
        return $descendants_status_list;
    }
    
    public function getMainProductStatuses() {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX . "order_product_status WHERE status_parent_id = '0';");
        
        return $query->rows;
    }
    
    public function getOrderStatusChangeRules() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status_product_status_workflow");
        $status_change_raw_rules = $query->rows;
        
        $status_including_rules = array();
        $status_excluding_rules = array();
        $status_total_rules = array();
        
        $result_status_change_rules = array();
        
        foreach ($status_change_raw_rules as $status_change_rule) {
            if (intval($status_change_rule['type']) === 1) {
                $status_including_rules[intval($status_change_rule['order_status_id'])][] = (int) $status_change_rule['order_product_status_id'];
            }
            $status_total_rules[intval($status_change_rule['order_status_id'])][] = (int) $status_change_rule['order_product_status_id'];
        }
        
        $main_product_statuses = $this->getMainProductStatuses();
        
        foreach ($status_total_rules as $key => $status_total_rule) {
            $status_excluding_rules[$key] = array();
            foreach ($main_product_statuses as $main_product_status) {
                if (!in_array(intval($main_product_status['order_product_status_id']), $status_total_rule)) {
                    $status_excluding_rules[$key][] = (int) $main_product_status['order_product_status_id'];
                }
            }
        }
        
        foreach ($status_total_rules as $key => $status_total_rule) {
            $result_status_change_rules[] = array('order_status_id' => $key, 'including' => $status_including_rules[$key], 'excluding' => $status_excluding_rules[$key]);
        }
        
        return $result_status_change_rules;
    }
    
    public function getCorrespondingStatus($order_status_id) {
        $query = $this->db->query("SELECT order_product_status_id FROM " . DB_PREFIX . "order_status_product_status_correlation WHERE order_status_id = '" . (int) $order_status_id . "';");
        
        return $query->row['order_product_status_id'];
    }
    
    public function getOrderProduct($order_product_id) {
        $order_product_query = $this->db->query("SELECT op.*, ops.bg_color as bg_color, ops.text_color as text_color, ops.name as ops_name, o.currency_code, o.currency_value  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id INNER JOIN " . DB_PREFIX . "order o ON op.order_id = o.order_id WHERE op.order_product_id = '" . (int) $order_product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        if ($order_product_query->num_rows) {
          //(int)$query->row['payment_country_id']
          //$product = array();
          //$product['order_product_id'] = (int)$query->row['order_product_id'];
          return array(
              'order_product_id' => $order_product_query->row['order_product_id'],
              'product_id' => $order_product_query->row['product_id'],
              'order_id' => $order_product_query->row['order_id'],
              'name' => $order_product_query->row['name'],
              'model' => $order_product_query->row['model'],
              'quantity' => $order_product_query->row['quantity'],
              'price' => $this->currency->format($order_product_query->row['price'] + ($this->config->get('config_tax') ? $order_product_query->row['tax'] : 0), $order_product_query->row['currency_code'], $order_product_query->row['currency_value']),
              'price_raw' => $order_product_query->row['price'],
              'total' => $this->currency->format($order_product_query->row['total'] + ($this->config->get('config_tax') ? ($order_product_query->row['tax'] * $order_product_query->row['quantity']) : 0), $order_product_query->row['currency_code'], $order_product_query->row['currency_value']),
              //@note заменить на ссылку TDM запчасти
              'status_id' => $order_product_query->row['order_product_status_id'],
              'status' => $order_product_query->row['ops_name'],
              'bg_color' => $order_product_query->row['bg_color'],
              'text_color' => $order_product_query->row['text_color'],
              'acquiring_price' => $order_product_query->row['acquiring_price'],
              'fragmentation_status' => $order_product_query->row['fragmentation_status'],
              'fragmentation_date' => $order_product_query->row['fragmentation_date'],
              'fragmentation_description' => $order_product_query->row['fragmentation_description'],
              'order_product_parent_id' => $order_product_query->row['order_product_parent_id'],
          );
        }
        else {
          return;
        }
    }
    
    public function getOrderRootProducts($order_id) {
    $query = $this->db->query("SELECT op.*, ops.name as ops_name  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id WHERE op.order_id = '" . (int) $order_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "' AND op.order_product_parent_id = '0'");    
    
    return $query->rows;
	}
    
    public function getOrderStatusId($order_id) {
    $query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int) $order_id . "'");
    
    return $query->row['order_status_id'];
    }
    
    public function updateOrderStatus($order_id, $order_status_id, $comment = '') {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `order_status_id` = '" . (int)$order_status_id . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_history` SET `order_id` = '" . (int)$order_id . "', `order_status_id` = '" . (int)$order_status_id . "', `notify` = '0', `comment` = '" . $this->db->escape($comment) . "', `date_added` = NOW()");
	}
    
    public function getOrderId($order_product_id) {
        $query = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_product_id . "'");
        
        return $query->row['order_id'];
    }
    
    public function getMainStatus($order_product_status_id) {
        $query = $this->db->query("SELECT status_parent_id FROM " . DB_PREFIX . "order_product_status WHERE order_product_status_id = '" . (int) $order_product_status_id . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");
        
        if (intval($query->row['status_parent_id'])) {
            return intval($query->row['status_parent_id']);
        } else {
            return $order_product_status_id;
        }
    }
    
    public function getStatusChangeTransactions($previous_status_id, $new_status_id) {
        $query = $this->db->query("SELECT operation_id FROM " . DB_PREFIX . "order_product_transaction_dependencies WHERE previous_status_id = '" . (int) $previous_status_id . "' AND new_status_id = '" . (int) $new_status_id . "' AND account_id = '1'");
        
        $current_operation_id = $query->row['operation_id'];
        
        $query = $this->db->query("SELECT operation_id FROM " . DB_PREFIX . "order_product_transaction_dependencies WHERE previous_status_id = '" . (int) $previous_status_id . "' AND new_status_id = '" . (int) $new_status_id . "' AND account_id = '2'");
        
        $reserve_operation_id = $query->row['operation_id'];
        
        return array('reserve_operation' => intval($reserve_operation_id), 'current_operation' => intval($current_operation_id));
    }
    
    public function executeTransaction($order_product_id, $operation_status, $operation_code = '') {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_product_id . "'");
        
        $product_total_price = $operation_code . $query->row['total'];
        
        $query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int) $query->row['order_id'] . "'");
        
        $customer_id = $query->row['customer_id'];
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int) $customer_id . "', order_product_id = '" . (int) $order_product_id . "', description = '', amount = '" . (float) $product_total_price . "', date_added = NOW(), operation_status = '" . (int) $operation_status . "'");

        if ($operation_status == 1) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance_now = balance_now + '" . (float) $product_total_price . "' WHERE customer_id = '" . (int) $customer_id . "'");
        } elseif ($operation_status == 2) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance_freeze = balance_freeze + '" . (float) $product_total_price . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }
    }
}