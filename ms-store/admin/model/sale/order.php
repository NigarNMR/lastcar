<?php

class ModelSaleOrder extends Model {

  public function getOrder($order_id) {
    $order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS order_status, (SELECT os.bg_color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS bg_color,  (SELECT os.text_color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS text_color FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");

    if ($order_query->num_rows) {
      $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['payment_country_id'] . "'");

      if ($country_query->num_rows) {
        $payment_iso_code_2 = $country_query->row['iso_code_2'];
        $payment_iso_code_3 = $country_query->row['iso_code_3'];
      }
      else {
        $payment_iso_code_2 = '';
        $payment_iso_code_3 = '';
      }

      $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['payment_zone_id'] . "'");

      if ($zone_query->num_rows) {
        $payment_zone_code = $zone_query->row['code'];
      }
      else {
        $payment_zone_code = '';
      }

      $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['shipping_country_id'] . "'");

      if ($country_query->num_rows) {
        $shipping_iso_code_2 = $country_query->row['iso_code_2'];
        $shipping_iso_code_3 = $country_query->row['iso_code_3'];
      }
      else {
        $shipping_iso_code_2 = '';
        $shipping_iso_code_3 = '';
      }

      $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

      if ($zone_query->num_rows) {
        $shipping_zone_code = $zone_query->row['code'];
      }
      else {
        $shipping_zone_code = '';
      }

      $reward = 0;

      $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

      foreach ($order_product_query->rows as $product) {
        $reward += $product['reward'];
      }

      if ($order_query->row['affiliate_id']) {
        $affiliate_id = $order_query->row['affiliate_id'];
      }
      else {
        $affiliate_id = 0;
      }

      $this->load->model('marketing/affiliate');

      $affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

      if ($affiliate_info) {
        $affiliate_firstname = $affiliate_info['firstname'];
        $affiliate_lastname = $affiliate_info['lastname'];
      }
      else {
        $affiliate_firstname = '';
        $affiliate_lastname = '';
      }

      $this->load->model('localisation/language');

      $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

      if ($language_info) {
        $language_code = $language_info['code'];
      }
      else {
        $language_code = $this->config->get('config_language');
      }

      return array(
          'order_id' => $order_query->row['order_id'],
          'invoice_no' => $order_query->row['invoice_no'],
          'invoice_prefix' => $order_query->row['invoice_prefix'],
          'store_id' => $order_query->row['store_id'],
          'store_name' => $order_query->row['store_name'],
          'store_url' => $order_query->row['store_url'],
          'customer_id' => $order_query->row['customer_id'],
          'customer' => $order_query->row['customer'],
          'customer_group_id' => $order_query->row['customer_group_id'],
          'firstname' => $order_query->row['firstname'],
          'lastname' => $order_query->row['lastname'],
          'email' => $order_query->row['email'],
          'telephone' => $order_query->row['telephone'],
          'fax' => $order_query->row['fax'],
          'custom_field' => json_decode($order_query->row['custom_field'], true),
          'payment_firstname' => $order_query->row['payment_firstname'],
          'payment_lastname' => $order_query->row['payment_lastname'],
          'payment_company' => $order_query->row['payment_company'],
          'payment_address_1' => $order_query->row['payment_address_1'],
          'payment_address_2' => $order_query->row['payment_address_2'],
          'payment_postcode' => $order_query->row['payment_postcode'],
          'payment_city' => $order_query->row['payment_city'],
          'payment_zone_id' => $order_query->row['payment_zone_id'],
          'payment_zone' => $order_query->row['payment_zone'],
          'payment_zone_code' => $payment_zone_code,
          'payment_country_id' => $order_query->row['payment_country_id'],
          'payment_country' => $order_query->row['payment_country'],
          'payment_iso_code_2' => $payment_iso_code_2,
          'payment_iso_code_3' => $payment_iso_code_3,
          'payment_address_format' => $order_query->row['payment_address_format'],
          'payment_custom_field' => json_decode($order_query->row['payment_custom_field'], true),
          'payment_method' => $order_query->row['payment_method'],
          'payment_code' => $order_query->row['payment_code'],
          'shipping_firstname' => $order_query->row['shipping_firstname'],
          'shipping_lastname' => $order_query->row['shipping_lastname'],
          'shipping_company' => $order_query->row['shipping_company'],
          'shipping_address_1' => $order_query->row['shipping_address_1'],
          'shipping_address_2' => $order_query->row['shipping_address_2'],
          'shipping_postcode' => $order_query->row['shipping_postcode'],
          'shipping_city' => $order_query->row['shipping_city'],
          'shipping_zone_id' => $order_query->row['shipping_zone_id'],
          'shipping_zone' => $order_query->row['shipping_zone'],
          'shipping_zone_code' => $shipping_zone_code,
          'shipping_country_id' => $order_query->row['shipping_country_id'],
          'shipping_country' => $order_query->row['shipping_country'],
          'shipping_iso_code_2' => $shipping_iso_code_2,
          'shipping_iso_code_3' => $shipping_iso_code_3,
          'shipping_address_format' => $order_query->row['shipping_address_format'],
          'shipping_custom_field' => json_decode($order_query->row['shipping_custom_field'], true),
          'shipping_method' => $order_query->row['shipping_method'],
          'shipping_code' => $order_query->row['shipping_code'],
          'comment' => $order_query->row['comment'],
          'total' => $order_query->row['total'],
          'reward' => $reward,
          'order_status_id' => $order_query->row['order_status_id'],
          'order_status' => $order_query->row['order_status'],
          'affiliate_id' => $order_query->row['affiliate_id'],
          'affiliate_firstname' => $affiliate_firstname,
          'affiliate_lastname' => $affiliate_lastname,
          'commission' => $order_query->row['commission'],
          'language_id' => $order_query->row['language_id'],
          'language_code' => $language_code,
          'currency_id' => $order_query->row['currency_id'],
          'currency_code' => $order_query->row['currency_code'],
          'currency_value' => $order_query->row['currency_value'],
          'ip' => $order_query->row['ip'],
          'forwarded_ip' => $order_query->row['forwarded_ip'],
          'user_agent' => $order_query->row['user_agent'],
          'accept_language' => $order_query->row['accept_language'],
          'date_added' => $order_query->row['date_added'],
          'date_modified' => $order_query->row['date_modified'],
              'bg_color' => $order_query->row['bg_color'],
              'text_color' => $order_query->row['text_color'],
      );
    }
    else {
      return;
    }
  }

  //"SELECT op.*,o.order_id, o.firstname, o.lastname, o.order_status_id, os.name as status_name, o.date_added, o.total, o.currency_code, o.currency_value  FROM " . DB_PREFIX . "order_product op INNER JOIN `" . DB_PREFIX . "order` o ON op.order_id = o.order_id  LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' AND op.fragmentation_status = '0' ORDER BY o.order_id DESC"
  public function getAllOrder($data = array()) {
   $sql = "SELECT op.*, o.order_id, o.firstname, o.lastname , (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS order_status, (SELECT os.bg_color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS bg_color, (SELECT os.text_color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS text_color, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added FROM " . DB_PREFIX . "order_product op     INNER JOIN `" . DB_PREFIX . "order` o  ON op.order_id = o.order_id   and op.fragmentation_status = '0' ";

    if (isset($data['filter_order_status'])) {
      $implode = array();

      $order_statuses = explode(',', $data['filter_order_status']);

      foreach ($order_statuses as $order_status_id) {
        $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
      }

      if ($implode) {
        $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
      }
    }
    else {
      $sql .= " WHERE o.order_status_id > '0'";
    }

    if (!empty($data['filter_order_id'])) {
      $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
    }
    
     if (!empty($data['filter_order_id'])) {
      $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
    }

    if (!empty($data['filter_description'])) {
      $sql .= " AND op.name = '  LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
    }
    
     if (!empty($data['filter_firm'])) {
       $sql .= " AND op.model LIKE '%" . $this->db->escape($data['filter_firm']) . "%'";
    }
    
    
    if (!empty($data['filter_customer'])) {
      $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
    }

    if (!empty($data['filter_date_added'])) {
      $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    }

    

    if (!empty($data['filter_total'])) {
      $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
    }

    $sort_data = array(
        'o.order_id',
        'customer',
        'status',
        'o.date_added',
        'o.date_modified',
        'o.total'
    );

    if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
      $sql .= " ORDER BY " . $data['sort'];
    }
    else {
      $sql .= " ORDER BY o.order_id";
    }

    if (isset($data['order']) && ($data['order'] == 'DESC')) {
      $sql .= " DESC";
    }
    else {
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
  }
  
  public function getOrders($data = array()) {
    $sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS order_status, (SELECT os.bg_color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS bg_color, (SELECT os.text_color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS text_color, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

    if (isset($data['filter_order_status'])) {
      $implode = array();

      $order_statuses = explode(',', $data['filter_order_status']);

      foreach ($order_statuses as $order_status_id) {
        $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
      }

      if ($implode) {
        $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
      }
    }
    else {
      $sql .= " WHERE o.order_status_id > '0'";
    }

    if (!empty($data['filter_order_id'])) {
      $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
    }

    if (!empty($data['filter_customer'])) {
      $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
    }

    if (!empty($data['filter_date_added'])) {
      $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    }

    if (!empty($data['filter_date_modified'])) {
      $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
    }

    if (!empty($data['filter_total'])) {
      $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
    }

    $sort_data = array(
        'o.order_id',
        'customer',
        'status',
        'o.date_added',
        'o.date_modified',
        'o.total'
    );

    if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
      $sql .= " ORDER BY " . $data['sort'];
    }
    else {
      $sql .= " ORDER BY o.order_id";
    }

    if (isset($data['order']) && ($data['order'] == 'DESC')) {
      $sql .= " DESC";
    }
    else {
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
  }

  public function getOrderProducts($order_id) {
    //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
    //@note добавил выборку статуса товара в заказе
    $query = $this->db->query("SELECT op.*, ops.bg_color as bg_color, ops.text_color as text_color, ops.name as ops_name  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id WHERE op.order_id = '" . (int) $order_id . "' AND op.order_product_parent_id = '0' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "'");

    return $query->rows;
  }
  
  
   
  

  /**
   * 
   * @param type $order_product_id
   * @return type
   */
  public function getOrderProduct($order_product_id) {
    //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
    //@note добавил выборку статуса товара в заказе
    $order_product_query = $this->db->query("SELECT op.*, ops.bg_color as bg_color, ops.text_color as text_color, ops.name as ops_name, o.currency_code, o.currency_value  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id INNER JOIN " . DB_PREFIX . "order_option oo ON op.order_product_id = oo.order_product_id INNER JOIN " . DB_PREFIX . "order o ON op.order_id = o.order_id WHERE op.order_product_id = '" . (int) $order_product_id . "'  AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "'");
    
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
          'href' => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $order_product_query->row['product_id'], true),
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
  
  /**
   * @param type $order_id
   * @param type $order_product_id
   * @return type
   */
  public function getOrderOptions($order_id, $order_product_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

    return $query->rows;
  }

  public function getOrderVouchers($order_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int) $order_id . "'");

    return $query->rows;
  }

  public function getOrderVoucherByVoucherId($voucher_id) {
    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int) $voucher_id . "'");

    return $query->row;
  }

  public function getOrderTotals($order_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");

    return $query->rows;
  }

  public function getTotalOrders($data = array()) {
    $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

    if (isset($data['filter_order_status'])) {
      $implode = array();

      $order_statuses = explode(',', $data['filter_order_status']);

      foreach ($order_statuses as $order_status_id) {
        $implode[] = "order_status_id = '" . (int) $order_status_id . "'";
      }

      if ($implode) {
        $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
      }
    }
    else {
      $sql .= " WHERE order_status_id > '0'";
    }

    if (!empty($data['filter_order_id'])) {
      $sql .= " AND order_id = '" . (int) $data['filter_order_id'] . "'";
    }

    if (!empty($data['filter_customer'])) {
      $sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
    }

    if (!empty($data['filter_date_added'])) {
      $sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    }

    if (!empty($data['filter_date_modified'])) {
      $sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
    }

    if (!empty($data['filter_total'])) {
      $sql .= " AND total = '" . (float) $data['filter_total'] . "'";
    }

    $query = $this->db->query($sql);

    return $query->row['total'];
  }

  public function getTotalOrdersByStoreId($store_id) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int) $store_id . "'");

    return $query->row['total'];
  }

  public function getTotalOrdersByOrderStatusId($order_status_id) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int) $order_status_id . "' AND order_status_id > '0'");

    return $query->row['total'];
  }

  public function getTotalOrdersByProcessingStatus() {
    $implode = array();

    $order_statuses = $this->config->get('config_processing_status');

    foreach ($order_statuses as $order_status_id) {
      $implode[] = "order_status_id = '" . (int) $order_status_id . "'";
    }

    if ($implode) {
      $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode));

      return $query->row['total'];
    }
    else {
      return 0;
    }
  }

  public function getTotalOrdersByCompleteStatus() {
    $implode = array();

    $order_statuses = $this->config->get('config_complete_status');

    foreach ($order_statuses as $order_status_id) {
      $implode[] = "order_status_id = '" . (int) $order_status_id . "'";
    }

    if ($implode) {
      $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode) . "");

      return $query->row['total'];
    }
    else {
      return 0;
    }
  }

  public function getTotalOrdersByLanguageId($language_id) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int) $language_id . "' AND order_status_id > '0'");

    return $query->row['total'];
  }

  public function getTotalOrdersByCurrencyId($currency_id) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int) $currency_id . "' AND order_status_id > '0'");

    return $query->row['total'];
  }

  public function createInvoiceNo($order_id) {
    $order_info = $this->getOrder($order_id);

    if ($order_info && !$order_info['invoice_no']) {
      $query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

      if ($query->row['invoice_no']) {
        $invoice_no = $query->row['invoice_no'] + 1;
      }
      else {
        $invoice_no = 1;
      }

      $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int) $invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int) $order_id . "'");

      return $order_info['invoice_prefix'] . $invoice_no;
    }
  }

  public function getOrderHistories($order_id, $start = 0, $limit = 10) {
    if ($start < 0) {
      $start = 0;
    }

    if ($limit < 1) {
      $limit = 10;
    }
// здесь запрос для истории заказа на странице /admin/index.php?route=sale/order/info
    $query = $this->db->query("SELECT oh.date_added, os.bg_color, os.text_color, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

    return $query->rows;
  }

  public function getOrderProductHistories($order_product_id, $start = 0, $limit = 10) {
    if ($start < 0) {
      $start = 0;
    }

    if ($limit < 1) {
      $limit = 10;
    }
// здесь запрос для модального окна
    $query = $this->db->query("SELECT oph.date_added,ops.bg_color as bg_color, ops.text_color as text_color, ops.name AS status, oph.comment, oph.notify FROM " . DB_PREFIX . "order_product_history oph LEFT JOIN " . DB_PREFIX . "order_product_status ops ON oph.order_product_status_id = ops.order_product_status_id WHERE oph.order_product_id = '" . (int) $order_product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oph.date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

    return $query->rows;
  }

  public function getTotalOrderHistories($order_id) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int) $order_id . "'");

    return $query->row['total'];
  }

  public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int) $order_status_id . "'");

    return $query->row['total'];
  }

  public function getTotalOrderProductHistories($order_product_id) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product_history WHERE order_product_id = '" . (int) $order_product_id . "'");

    return $query->row['total'];
  }

  public function getTotalOrderHistoriesByOrderProductStatusId($order_product_status_id) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product_history WHERE order_product_status_id = '" . (int) $order_product_status_id . "'");

    return $query->row['total'];
  }

  public function getEmailsByProductsOrdered($products, $start, $end) {
    $implode = array();

    foreach ($products as $product_id) {
      $implode[] = "op.product_id = '" . (int) $product_id . "'";
    }

    $query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int) $start . "," . (int) $end);

    return $query->rows;
  }

  public function getTotalEmailsByProductsOrdered($products) {
    $implode = array();

    foreach ($products as $product_id) {
      $implode[] = "op.product_id = '" . (int) $product_id . "'";
    }

    $query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

    return $query->row['total'];
  }

  public function getProductBranch($order_id, $product_id) {
    // Выбор всех потомков по переданному product_id
    //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id='" . $order_id . "' AND (order_product_parent_id='" . $product_id . "')");
    $query = $this->db->query("SELECT op.*, ops.bg_color as bg_color, ops.text_color as text_color, ops.name as status  FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order_product_status ops ON op.order_product_status_id = ops.order_product_status_id WHERE op.order_id = '" . (int) $order_id . "' AND op.order_product_parent_id = '" . $product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "'");
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
                $branch_data['branch'] = $this->getProductBranch($order_id, $ar_pre_result['order_product_id']);
            } else {
                $branch_data['branch'] = array();
            }
            $ar_results[] = $branch_data;
            unset($branch_data);
        }
    }
    return $ar_results;
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
  
  /**
   * Исполнение разбиение товара на подтовары
   * 
   * @param array $partition_parent_data Данные родительского товара в заказе
   * @param array $partition_children_data Данные потомков для разбиения
   * @param string $partition_description Описание разбиения (опц.)
   * @return type
   */
  public function executePartition($partition_parent_data, $partition_children_data, $partition_description = '') {
      $query = $this->db->query("SELECT order_product_status_id, status_parent_id FROM " . DB_PREFIX . "order_product_status WHERE status_parent_id != 0 UNION SELECT ops.order_product_status_id, ops2.order_product_status_id as status_parent_id FROM " . DB_PREFIX . "order_product_status ops INNER JOIN " . DB_PREFIX . "order_product_status ops2 ON ops.order_product_status_id = ops2.order_product_status_id WHERE ops.status_parent_id = 0");
      
      $status_parential_table = array();
      
      foreach ($query->rows as $row) {
          $status_parential_table[intval($row['order_product_status_id'])] = intval($row['status_parent_id']);
      }
      
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_transaction_dependencies WHERE previous_status_id = '" . $status_parential_table[intval($partition_parent_data['status_id'])] . "'");
      
      $status_change_transactions_data = $query->rows;
      $status_change_data = array();
      
      foreach ($status_change_transactions_data as $data) {
          $status_change_data [intval($data['new_status_id'])] [intval($data['account_id'])] = intval($data['operation_id']);
      }
      
      $query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int) $partition_parent_data['order_id'] . "'");
        
      $customer_id = $query->row['customer_id'];
      
      // Начало транзакции
      $sql_expression = "START TRANSACTION; ";
      $this->db->query($sql_expression);
      
      // Обновление родителя
      $sql_expression = "UPDATE " . DB_PREFIX . "order_product SET fragmentation_status = '1', fragmentation_date = NOW(), fragmentation_description = '";
      if (!empty($partition_description)) {
          $sql_expression .= $this->db->escape($partition_description);
      }
      $sql_expression .= "' WHERE order_product_id = '" . $this->db->escape($partition_parent_data['order_product_id']) . "'; ";
      $this->db->query($sql_expression);
      
      // Создание записей по потомкам
      // child_data (0 - кол-во, 1 - номер статуса)
      $child_counter = 0;
      foreach ($partition_children_data as $child_data) {
          $child_counter++;
          $sql_expression = "INSERT INTO " . DB_PREFIX . "order_product (order_product_id, order_id, product_id, name, model, quantity, price, total, tax, reward, order_product_status_id, acquiring_price, fragmentation_status, fragmentation_date, fragmentation_description, order_product_parent_id) VALUES (NULL, '" . $this->db->escape($partition_parent_data['order_id']) . "', '" . $this->db->escape($partition_parent_data['product_id']) . "', '" . $this->db->escape($partition_parent_data['name']) . "', '" . $this->db->escape($partition_parent_data['model']) . "', '" . $this->db->escape($child_data[0]) . "', '" . $this->db->escape($partition_parent_data['price_raw']) . "', '" . $this->db->escape($child_data[0]) . "' * '" . $partition_parent_data['price_raw'] . "', '0.0000', '0', '" . $this->db->escape($child_data[1]) . "', '" . (float) $partition_parent_data['acquiring_price'] . "', '0', NULL, NULL, '" . $this->db->escape($partition_parent_data['order_product_id']) . "'); ";
          $this->db->query($sql_expression);
          $sql_expression = "SET @product_last_id_" . $child_counter . " = LAST_INSERT_ID(); ";
          $this->db->query($sql_expression);
          
          foreach ($status_change_data[ $status_parential_table [intval($child_data[1])] ] as $key => $data) {
                if ($data !== 0) {
                    $product_total_price = (int) $child_data[0] * (float) $partition_parent_data['price_raw'];
                    if ($data === 1) {
                        $product_total_price = '+' . $product_total_price;
                    } elseif ($data === 2) {
                        $product_total_price = '-' . $product_total_price;
                    }
                    
                    $this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int) $customer_id . "', order_product_id = @product_last_id_" . $child_counter . ", description = '', amount = '" . (float) $product_total_price . "', date_added = NOW(), operation_status = '" . (int) $key . "'");

                    if ($key === 1) {
                        $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance_now = balance_now + '" . (float) $product_total_price . "' WHERE customer_id = '" . (int) $customer_id . "'");
                    } elseif ($key === 2) {
                        $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance_freeze = balance_freeze + '" . (float) $product_total_price . "' WHERE customer_id = '" . (int) $customer_id . "'");
                    }
                    
                }
          }
          
          $sql_expression = "INSERT INTO " . DB_PREFIX . "order_product_history (order_product_history_id, order_product_id, order_product_status_id, notify, comment, date_added) VALUES (NULL, @product_last_id_" . $child_counter . ", '" . $this->db->escape($child_data[1]) . "', '0', '', NOW());";
          $this->db->query($sql_expression);
      }
      
      $sql_expression = "COMMIT;";
      
      $query = $this->db->query($sql_expression);
      
      return $query;
  }
  
  /**
   * Сбор идентификаторов подтоваров всей ветки по потомку
   * 
   * @param type $leaf_key Идентификатор потомка
   * @return type
   */
  public function getBranchIdCollection($leaf_key) {
      $ar_collection = array();
      
      $query = $this->db->query("SELECT order_product_id as current_key, order_product_parent_id as parent_key FROM " . DB_PREFIX . "order_product WHERE order_product_id='" . $leaf_key . "';");
      $ar_leaf_parent_data = $query->row;
      
      array_unshift($ar_collection, $ar_leaf_parent_data['current_key']);
      
      while (intval($ar_leaf_parent_data['parent_key'])) {
          $query = $this->db->query("SELECT order_product_id as current_key, order_product_parent_id as parent_key FROM " . DB_PREFIX . "order_product WHERE order_product_id='" . $ar_leaf_parent_data['parent_key'] . "';");
          $ar_leaf_parent_data = $query->row;
          
          array_unshift($ar_collection, $ar_leaf_parent_data['current_key']);
      }
      
      return $ar_collection;
  }
    
  /**
   * Постраничный сбор данных истории по всем переданным идентификаторам товаров
   * 
   * @param array $order_product_id_collection Набор идентификаторов
   * @param int $start Начало страницы
   * @param int $limit Размер страницы
   * @return type
   */
  public function getOrderProductBranchHistories($order_product_id_collection, $start = 0, $limit = 10) {
    if ($start < 0) {
      $start = 0;
    }

    if ($limit < 1) {
      $limit = 10;
    }
// здесь запрос для модального окна
    $ar_sub_query = array();
    //$order_product_id_collection = array_reverse($order_product_id_collection);
    $iteration_count = 0;
    $collection_size = count($order_product_id_collection);
    foreach ($order_product_id_collection as $int_array_key => $order_product_id) {
        if ($iteration_count === $collection_size - 1) {
            $ar_sub_query[] = "(SELECT oph.date_added as date_added,ops.bg_color as bg_color, ops.text_color as text_color, ops.name AS status, oph.comment, oph.notify FROM " . DB_PREFIX . "order_product_history oph LEFT JOIN " . DB_PREFIX . "order_product_status ops ON oph.order_product_status_id = ops.order_product_status_id WHERE oph.order_product_id = '" . (int) $order_product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY date_added DESC)";
        } else {
            $ar_sub_query[] = "(SELECT oph.date_added as date_added,ops.bg_color as bg_color, ops.text_color as text_color, ops.name AS status, oph.comment, oph.notify FROM " . DB_PREFIX . "order_product_history oph LEFT JOIN " . DB_PREFIX . "order_product_status ops ON oph.order_product_status_id = ops.order_product_status_id WHERE oph.order_product_id = '" . (int) $order_product_id . "' AND ops.language_id = '" . (int) $this->config->get('config_language_id') . "' AND oph.date_added <= (SELECT fragmentation_date FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_product_id . "') ORDER BY date_added DESC)";
            $ar_sub_query[] = "(SELECT fragmentation_date as date_added, 'ffffff' as bg_color, '000000' as text_color, 'Разделение товара' as status, fragmentation_description as comment, '0' as notify FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_product_id . "')";
            $iteration_count++;
        }
    }
    
    $query = $this->db->query(implode(" UNION ", $ar_sub_query) . " LIMIT ". (int) $start . "," . (int) $limit);
    return $query->rows;
  }
  
  public function getTotalOrderProductBranchHistories($order_product_id_collection) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product_history WHERE order_product_id IN ('" . implode("', '", $order_product_id_collection) . "');");

    return $query->row['total'];
  }
  
  public function getOrderTotalPrice($order_id) {
      $query = $this->db->query("SELECT SUM(total) as result FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND order_product_parent_id = '0'");
      return $query->row['result'];
  }
  
  public function getOrderTotalCostPrice($order_id) {
      $query = $this->db->query("SELECT SUM(quantity * acquiring_price) as result FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND order_product_parent_id = '0'");
      return $query->row['result'];
  }
  
  public function getOrderTotalProfit($order_id) {
      $query = $this->db->query("SELECT SUM(total - quantity * acquiring_price) as result FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND order_product_parent_id = '0'");
      return $query->row['result'];
  }
  
  public function updateProductQuantity($order_product_id, $quantity) {
      $this->db->query("UPDATE " . DB_PREFIX . "order_product SET quantity = '" . (int) $quantity . "', total = price * '" . (int) $quantity . "' WHERE order_product_id = '" . (int) $order_product_id . "'");
  }
  
  public function updateProductAcquiringPrice($order_product_id, $acquiring_price) {
      $this->db->query("UPDATE " . DB_PREFIX . "order_product SET acquiring_price = '" . (float) $acquiring_price . "' WHERE order_product_id = '" . (int) $order_product_id . "'");
  }
  
  public function updateProductPrice($order_product_id, $price, $price_format) {
      $this->db->query("UPDATE " . DB_PREFIX . "order_product SET price = '" . (float) $price . "', total = quantity * '" . (float) $price . "' WHERE order_product_id = '" . (int) $order_product_id . "'");
      $this->db->query("UPDATE " . DB_PREFIX . "order_option SET value = '" . $price_format . "' WHERE name = 'Price' AND order_product_id = '" . $order_product_id . "'");
  }
  
  public function updateProductDeliveryTime($order_product_id, $delivery_time) {
      $this->db->query("UPDATE " . DB_PREFIX . "order_option SET value = '" . (int) $delivery_time . "' WHERE name = 'Срок поставки (дней)' AND order_product_id = '" . $order_product_id . "'");
  }
  
  public function updateOrderTotalPrice($order_id, $total_price) {
      $this->db->query("UPDATE " . DB_PREFIX . "order SET total = '" . (float) $total_price . "' WHERE order_id = '" . (int) $order_id . "'");
      $this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = '" . (float) $total_price . "' WHERE order_id = '" . (int) $order_id . "' AND code IN ('sub_total', 'total')");
  }
  
  
 }
