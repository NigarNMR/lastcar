<?php
class ModelMessageMessage extends Model {
	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group, (SELECT msg.date_added FROM " . DB_PREFIX . "msp_message msg WHERE msg.customer_id=c.customer_id ORDER BY msg.date_added DESC LIMIT 0,1) AS message_date, (SELECT count(*) FROM " . DB_PREFIX . "msp_message msg WHERE msg.customer_id=c.customer_id AND msg.read_status='0' AND msg.sender='customer') AS total_message FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && $data['filter_newsletter'] !== null) {
			$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && $data['filter_approved'] !== null) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added',
			'message_date'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function sendMessage($data) {
	
		$this->db->query("INSERT INTO " . DB_PREFIX . "msp_message SET user_id = '". (int)$data['user_id'] ."', customer_id = '" . (int)$data['customer_id'] . "', message = '" . $this->db->escape($data['message']) . "', sender = 'user', date_added = NOW()");
		
		$this->load->model('sale/customer');
		$customer_info = $this->model_sale_customer->getCustomer($data['customer_id']);
		if($customer_info) {
			$this->language->load('message/message');
			$this->load->model('setting/store');
			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}
			
			$link = HTTP_CATALOG .'index.php?route=account/message'; 
			$message = sprintf($this->language->get('mail_message'), $store_name, $link);
			$message .= $data['message'];
			$message .= $this->language->get('mail_thanks');
			$message .= $store_name;
			
			
			$mail = new Mail();
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('mail_subject'), $store_name));
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}	
	
	public function getMessagesByCustomerId($data) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "msp_message WHERE customer_id = '" . (int)$data['customer_id'] . "' ORDER BY date_added DESC");
		
		return $query->rows;
	}	
	
	public function CreateTable(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "msp_message` (`message_id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL,`customer_id` int(11) NOT NULL,`sender` varchar(20) NOT NULL,`message` text NOT NULL,`read_status` tinyint(1) NOT NULL,`date_added` datetime NOT NULL,PRIMARY KEY (`message_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}
	
	public function getTotalUnreadMessages($data) {
	
		$this->CreateTable();
	
		$sql ="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "msp_message WHERE message_id>0";
		
		if(isset($data['filter_sender'])) {
			$sql .=" AND sender ='". $this->db->escape($data['filter_sender']) ."'";
		}
		
		if(isset($data['filter_status'])) {
			$sql .=" AND read_status='". (int)$data['filter_status'] ."'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
	
	public function updateReadStatus($data) {
		$sql = "UPDATE " . DB_PREFIX . "msp_message SET read_status=1 WHERE customer_id='". (int)$data['customer_id'] ."'";
		
		if(isset($data['filter_sender'])) {
			$sql .=" AND sender ='". $this->db->escape($data['filter_sender']) ."'";
		}
		
		if(isset($data['filter_status'])) {
			$sql .=" AND read_status='". (int)$data['filter_status'] ."'";
		}
		
		$query = $this->db->query($sql);
	}
}