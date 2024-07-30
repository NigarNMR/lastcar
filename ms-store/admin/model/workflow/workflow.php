<?php
class ModelWorkflowWorkflow extends Model {

    public function getStatusProductOrder() {

        $query = $this->db->query("SELECT order_product_status_id, name FROM " . DB_PREFIX . "order_product_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY name");

        return $query->rows;
    }

    public function addOrderProductStatusWorkflow($data) {
        
        $status = $data['status'];
        $this->db->query("TRUNCATE " . DB_PREFIX . "order_product_status_workflow");
        
        foreach ($status as $key_A => $value_A) {
            foreach ($value_A as $key_B => $value_B) {              
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_status_workflow SET order_product_status_id_A = '" . (int) $key_A . "', order_product_status_id_B = '" . (int) $key_B . "', value = 1 ON DUPLICATE KEY UPDATE order_product_status_id_A = '" . (int) $key_A . "', order_product_status_id_B = '" . (int) $key_B . "', value = 1 ");
            }
        }
    }

    public function getOrderProductStatusWorkflow() {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_status_workflow WHERE value = 1");
        
        return $query->rows;
    }

}
