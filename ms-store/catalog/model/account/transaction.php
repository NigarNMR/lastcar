<?php
class ModelAccountTransaction extends Model {
	public function getTransactions($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_transaction` WHERE customer_id = '" . (int)$this->customer->getId() . "'";

		$sort_data = array(
			'amount',
			'description',
			'date_added'
		);

        if (isset($data['tab_operations'])) {
            $sql .= " AND operation_status IN ('" . implode("','", $data['tab_operations']) . "')";
        }
        
        if (isset($data['date_start']) && $data['date_start']) {
            $sql .= " AND date_added > '" . $data['date_start'] . "'";
        }
        
        if (isset($data['date_end']) && $data['date_end']) {
            $sql .= " AND date_added < '" . $data['date_end'] . "'";
        }
        
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
		}

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

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalTransactions($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_transaction` WHERE customer_id = '" . (int)$this->customer->getId() . "'";

        if (isset($data['tab_operations'])) {
            $sql .= " AND operation_status IN ('" . implode("','", $data['tab_operations']) . "')";
        }
        
        if (isset($data['date_start']) && $data['date_start']) {
            $sql .= " AND date_added > '" . $data['date_start'] . "'";
        }
        
        if (isset($data['date_end']) && $data['date_end']) {
            $sql .= " AND date_added < '" . $data['date_end'] . "'";
        }
        
        $query = $this->db->query($sql);
        
		return $query->row['total'];
	}

	public function getTotalAmount() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "customer_transaction` WHERE customer_id = '" . (int)$this->customer->getId() . "' GROUP BY customer_id");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
    
    public function getOrderProductName($order_product_id) {
        $query = $this->db->query("SELECT name FROM oc_order_product WHERE order_product_id = '" . $order_product_id . "'");
        return $query->row['name'];
    }
}