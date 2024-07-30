<?php
class ModelAccountMessage extends Model {
	public function sendMessage($data) {
	
		$this->db->query("INSERT INTO " . DB_PREFIX . "msp_message SET customer_id = '" . (int)$data['customer_id'] . "', message = '" . $this->db->escape($data['message']) . "', sender = 'customer', date_added=NOW()");
	}
	
	public function getMessagesByCustomerId($data) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "msp_message WHERE customer_id = '" . (int)$data['customer_id'] . "' AND hide_customer='0' ORDER BY date_added DESC");
		
		return $query->rows;
	}	
	
	
	public function deleteCustomerMessages($customer_id){
		$this->db->query("UPDATE ". DB_PREFIX ."msp_message SET hide_customer=1 WHERE customer_id='".(int)$customer_id."'");
	}
	
	public function CreateTable(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "msp_message` (`message_id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL,`customer_id` int(11) NOT NULL,`sender` varchar(20) NOT NULL,`message` text NOT NULL,`read_status` tinyint(1) NOT NULL,`hide_admin` tinyint(1) NOT NULL,`hide_customer` tinyint(1) NOT NULL,`date_added` datetime NOT NULL,PRIMARY KEY (`message_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		
		$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "msp_message");
		
		foreach ($query->rows as $field) {
			$fields[] = $field['Field']; // Build array of all table columns
		}
		
		if(!in_array('hide_customer', $fields)) { // Database entry not found... 
			 $this->db->query("ALTER TABLE " . DB_PREFIX . "msp_message ADD `hide_customer` tinyint(1) NOT NULL");
		}
		
		if(!in_array('hide_admin', $fields)) { // Database entry not found... 
			 $this->db->query("ALTER TABLE " . DB_PREFIX . "msp_message ADD `hide_admin` tinyint(1) NOT NULL");
		}
	}
	
	public function getTotalUnreadMessages($data) {
		
		$this->CreateTable();
		
		$sql ="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "msp_message WHERE hide_customer='0'";
		
		if(isset($data['filter_sender'])) {
			$sql .=" AND sender ='". $this->db->escape($data['filter_sender']) ."'";
		}
		
		if(isset($data['filter_status'])) {
			$sql .=" AND read_status='". (int)$data['filter_status'] ."'";
		}
		
		$sql .=" AND customer_id='". (int)$data['customer_id'] ."'";	
		
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