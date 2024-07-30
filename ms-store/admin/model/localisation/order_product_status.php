<?php

class ModelLocalisationOrderProductStatus extends Model {

    public function addOrderProductStatus($data) {

        foreach ($data['order_product_status'] as $language_id => $value) {
            if (isset($order_product_status_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_status SET order_product_status_id = '" . (int) $order_product_status_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', bg_color = '" . $this->db->escape($data['bd_color']) . "' , text_color = '" . $this->db->escape($data['text_color']) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_status SET language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', bg_color = '" . $this->db->escape($value['bd_color']) . "' , text_color = '" . $this->db->escape($value['text_color']) . "'");

                $order_product_status_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('order_product_status');

        return $order_product_status_id;
    }
    
    public function addOrderProductDescendantStatus($data) {
        $data = $data['partition_data'];
        $data = json_decode(htmlspecialchars_decode(stripslashes($data)), true);
        
        $parent_status_id = $data['status_parent'];
        
        foreach ($data['status_names'] as $status_lang_id => $status_lang_name) {
            if (isset($order_product_status_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_status SET order_product_status_id = '" . (int) $order_product_status_id . "', language_id = '" . (int) $status_lang_id . "', name = '" . $this->db->escape($status_lang_name) . "', status_parent_id = '" . $this->db->escape($parent_status_id) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_status SET language_id = '" . (int) $status_lang_id . "', name = '" . $this->db->escape($status_lang_name) . "', status_parent_id = '" . $this->db->escape($parent_status_id) . "'");

                $order_product_status_id = $this->db->getLastId();
            }
        }
        
        $this->cache->delete('order_product_status');

        return $order_product_status_id;
    }

    public function editOrderProductStatus($order_product_status_id, $data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product_status WHERE order_product_status_id = '" . (int) $order_product_status_id . "'");

        foreach ($data['order_product_status'] as $language_id => $value) {
            /* $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_status SET order_product_status_id = '" . (int)$order_product_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'"); */
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_status SET order_product_status_id = '" . (int) $order_product_status_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', bg_color = '" . $this->db->escape($data['bg_color']) . "' , text_color = '" . $this->db->escape($data['text_color']) . "'");
        }

        $this->cache->delete('order_product_status');
    }

    public function deleteOrderProductStatus($order_product_status_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product_status WHERE order_product_status_id = '" . (int) $order_product_status_id . "'");

        $this->cache->delete('order_product_status');
    }

    public function getOrderProductStatus($order_product_status_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_status WHERE order_product_status_id = '" . (int) $order_product_status_id . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getOrderProductStatuses($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "order_product_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' AND status_parent_id = '0'";

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
            $order_product_status_data = $this->cache->get('order_product_status.' . (int) $this->config->get('config_language_id'));

            if (!$order_product_status_data) {
                $query = $this->db->query("SELECT order_product_status_id, name FROM " . DB_PREFIX . "order_product_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY name");

                $order_product_status_data = $query->rows;

                $this->cache->set('order_product_status.' . (int) $this->config->get('config_language_id'), $order_product_status_data);
            }

            return $order_product_status_data;
        }
    }
    
    /**
     * Получение дочерних (расширяющих) статусов
     * @param type $status_id Id родительского статуса
     */
    public function getOrderProductDescendantStatuses($status_id) {
        if (is_array($status_id)) {
            $status_id = implode("', '", $status_id);
        }
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_status WHERE status_parent_id IN ('" . $status_id . "') AND language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->rows;
    }

    public function getOrderProductStatusesWorkflow($status_id, $bg_color, $text_color) {
        
        $order_product_status_data = $this->cache->get('order_product_status.' . (int) $status_id .(int) $bg_color . (int) $text_color . (int) $this->config->get('config_language_id'));
        //echo '<pre>'; var_dump($order_product_status_data); echo '</pre>';

        if (!$order_product_status_data) {
            $query = $this->db->query("SELECT ops.order_product_status_id, ops.name FROM " . DB_PREFIX . "order_product_status_workflow opsw INNER JOIN " . DB_PREFIX . "order_product_status ops ON opsw.order_product_status_id_B = ops.order_product_status_id WHERE opsw.order_product_status_id_A = '" . (int) $status_id . "' AND opsw.value = 1 AND  ops.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY ops.name");
            //echo "SELECT ops.order_product_status_id, ops.name FROM " . DB_PREFIX ."order_product_status_workflow opsw INNER JOIN " . DB_PREFIX ."order_product_status ops ON opsw.order_product_status_id_B = ops.order_product_status_id WHERE opsw.order_product_status_id_A = '" . (int) $status_id . "' AND opsw.value = 1 AND  ops.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ops.name";

            $order_product_status_data = $query->rows;

            $this->cache->set('order_product_status.' . (int) $status_id . (int) $bg_color . (int) $text_color . (int) $this->config->get('config_language_id'), $order_product_status_data);
        }

        return $order_product_status_data;
    }

    public function getOrderProductStatusDescriptions($order_product_status_id) {
        $order_product_status_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_status WHERE order_product_status_id = '" . (int) $order_product_status_id . "'");

        foreach ($query->rows as $result) {
            $order_product_status_data[$result['language_id']] = array('name' => $result['name']);
        }

        return $order_product_status_data;
    }

    public function getOrderProductStatusTextColor($order_product_status_id) {
        $order_product_status_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_status WHERE order_product_status_id = '" . (int) $order_product_status_id . "'");

        foreach ($query->rows as $result) {
            $text_color = $result['text_color'];
        }

        return $text_color;
    }

    public function getOrderProductStatusBgColor($order_product_status_id) {
        $order_product_status_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_status WHERE order_product_status_id = '" . (int) $order_product_status_id . "'");

        foreach ($query->rows as $result) {
            $bg_color = $result['bg_color'];
        }

        return $bg_color;
    }

    public function getTotalOrderProductStatuses() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }
    
    /**
     * Проверка существования статусов с именами из набора
     * @param array $data Набор имен (ключ -> имя)
     * @return boolean Результат проверки
     */
    public function checkOrderProductStatusNameExistance($data) {
        $query = $this->db->query("SELECT EXISTS (SELECT * FROM " . DB_PREFIX . "order_product_status WHERE name IN ('" . implode("', '", $data) . "')) as 'check'");

        return intval($query->row['check']);
    }
    
    public function checkOrderProductStatusInUse($status_id) {
        $query = $this->db->query("SELECT EXISTS (SELECT * FROM " . DB_PREFIX . "order_product WHERE order_product_status_id IN ('" . $status_id . "')) as 'check'");

        return intval($query->row['check']);
    }
    
    public function checkOrderProductStatusInHistory($status_id) {
        $query = $this->db->query("SELECT EXISTS (SELECT * FROM " . DB_PREFIX . "order_product_history WHERE order_product_status_id IN ('" . $status_id . "')) as 'check'");

        return intval($query->row['check']);
    }
    
    public function validateDescendantCreationStatusData($data) {
        $data = $data['partition_data'];
        $data = json_decode(htmlspecialchars_decode(stripslashes($data)), true);
        
        // Проверка наличия переданных данных
        if (isset($data['status_names']) && isset($data['status_parent'])) {
            $parent_status_data = $this->getOrderProductStatus($data['status_parent']);
        } else {
            $error_code = -1;
            return $error_code;
        }
        
        // Проверка статуса на возможность расширения
        if (intval($parent_status_data['status_parent_id'])) {
            $error_code = 1;
            return $error_code;
        }
        
        // Предустановка триггера
        $trigger_empty_name = False;

        // Проход по переданным именам и изменение состояния триггера
        // в случае нахождения пустых данных
        foreach ($data['status_names'] as $key => $status_name) {
            if (empty($status_name)) {
                $trigger_empty_name = True;
            }
        }

        if ($trigger_empty_name) {
            $error_code = 2;
            return $error_code;
        }
        
        // Проверка переданных имен на совпадения
        $trigger_name_exists = $this->checkOrderProductStatusNameExistance($data['status_names']);

        if ($trigger_name_exists) {
            $error_code = 3;
            return $error_code;
        }
        
        $error_code = 0;
        return $error_code;
    }
    
    public function validateDescendantStatusRemoval($order_product_status_id) {
        $status_data = $this->getOrderProductStatus($order_product_status_id);
        
        // Проверка на то, является ли статус основым
        if (!intval($status_data['status_parent_id'])) {
            $validation = 0;
            return $validation;
        }
        
        if ($this->checkOrderProductStatusInUse($order_product_status_id)) {
            $validation = 0;
            return $validation;
        }
        
        if ($this->checkOrderProductStatusInHistory($order_product_status_id)) {
            $validation = 0;
            return $validation;
        }
        
        $validation = 1;
        return $validation;
    }
}
