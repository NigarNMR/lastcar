<?php

class ModelOrderWorkflowOrderWorkflow extends Model {

    public function addOrderStatus($data) {
        foreach ($data['order_status'] as $language_id => $value) {
            if (isset($order_status_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int) $order_status_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', bg_color = '" . $this->db->escape($data['bg_color']) . "' , text_color = '" . $this->db->escape($data['text_color']) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', bg_color = '" . $this->db->escape($data['bg_color']) . "' , text_color = '" . $this->db->escape($data['text_color']) . "'");

                $order_status_id = $this->db->getLastId();
            }
        }
        $this->cache->delete('order_status');

        return $order_status_id;
    }

    public function getOrderStatus($order_status_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getStatusProductOrder() {

        $query = $this->db->query("SELECT order_product_status_id, name FROM " . DB_PREFIX . "order_product_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' AND status_parent_id = '0' ORDER BY name");

        return $query->rows;
    }

    public function getOrderStatuses($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "'";

            $sql .= " ORDER BY name";

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $order_status_data = $this->cache->get('order_status.' . (int) $this->config->get('config_language_id'));

            if (!$order_status_data) {
                $query = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status_product_status_workflow WHERE type = 2 ORDER BY name");

                $order_status_data = $query->rows;

                $this->cache->set('order_status.' . (int) $this->config->get('config_language_id'), $order_status_data);
            }

            return $order_status_data;
        }
    }
    /**
    public function editOrderStatusProductAllWorkflow($order_product_status_id, $order_status_id) {

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_status_product_status_workflow WHERE order_status_id = '" . (int) $order_status_id['order_status_id'] . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "order_status_product_status_workflow SET order_status_id = '" . (int) $order_status_id['order_status_id'] . "', "
                . "order_product_status_id = '" . (int) $order_product_status_id['order_product_status_id'] . "', type = 2 ON DUPLICATE KEY UPDATE `order_status_id` =  '" . (int) $order_status_id['order_status_id'] . "' , `order_product_status_id` = '" . (int) $order_product_status_id['order_product_status_id'] . "'");
    }
    */
    public function editOrderStatusProductIfOneWorkflow($order_product_status_id, $order_status_id) {        
        foreach ($order_product_status_id['selected_if_one'] as $key => $value) {
            foreach ($value as $order_product_status_id => $value2) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_status_product_status_workflow SET order_status_id = '" . (int) $order_status_id['order_status_id'] . "', "
                        . "order_product_status_id = '" . (int) $order_product_status_id . "', type = 1 ON DUPLICATE KEY UPDATE `order_status_id` =  '" . (int) $order_status_id['order_status_id'] . "' , `order_product_status_id` = '" . (int) $order_product_status_id . "'");
            }
        }
    }

    public function editOrderStatusProductAnyWorkflow($order_product_status_id, $order_status_id) {
        foreach ($order_product_status_id['selected_any'] as $key1 => $value1) {
            foreach ($value1 as $order_product_status_id => $value3) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_status_product_status_workflow SET order_status_id = '" . (int) $order_status_id['order_status_id'] . "', "
                        . "order_product_status_id = '" . (int) $order_product_status_id . "', type = 2 ON DUPLICATE KEY UPDATE `order_status_id` =  '" . (int) $order_status_id['order_status_id'] . "' , `order_product_status_id` = '" . (int) $order_product_status_id . "'");
            }
        }
    }
    
    public function editOrderStatusProductPreClear($order_status_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_status_product_status_workflow WHERE order_status_id = '" . (int) $order_status_id['order_status_id'] . "'");
    }

    public function getOrderProductStatusWorkflow($order_status_id) {
        $query = $this->db->query("SELECT " . DB_PREFIX . "order_status_product_status_workflow.order_product_status_id,  " . DB_PREFIX . "order_status_product_status_workflow.order_status_id,   " . DB_PREFIX . "order_product_status.name, " . DB_PREFIX . "order_status_product_status_workflow.type FROM  " . DB_PREFIX . "order_status_product_status_workflow,  " . DB_PREFIX . "order_product_status WHERE  " . DB_PREFIX . "order_status_product_status_workflow.order_product_status_id =  " . DB_PREFIX . "order_product_status.order_product_status_id AND  " . DB_PREFIX . "order_status_product_status_workflow.order_status_id = " . $order_status_id . " AND  " . DB_PREFIX . "order_product_status.language_id =  '" . (int) $this->config->get('config_language_id') . "' ORDER BY name");

        return $query->rows;
    }
    
    public function getOrderProductStatusCorrelation($order_status_id) {
        $query = $this->db->query();
        
        if ($query->num_rows) {
            return $query->row;
        } else {
            return array();
        }
    }

}
