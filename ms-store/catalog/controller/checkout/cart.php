<?php

class ControllerCheckoutCart extends Controller {

  public function index() {
    $this->load->language('checkout/cart');

    $this->document->setTitle($this->language->get('heading_title'));

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'href' => $this->url->link('common/home'),
        'text' => $this->language->get('text_home')
    );

    $data['breadcrumbs'][] = array(
        'href' => $this->url->link('checkout/cart'),
        'text' => $this->language->get('heading_title')
    );

    if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
      $data['heading_title'] = $this->language->get('heading_title');

      $data['text_recurring_item'] = $this->language->get('text_recurring_item');
      $data['text_next'] = $this->language->get('text_next');
      $data['text_next_choice'] = $this->language->get('text_next_choice');
      $data['text_more'] = $this->language->get('text_more');

      $data['column_image'] = $this->language->get('column_image');
      $data['column_name'] = $this->language->get('column_name');
      $data['column_model'] = $this->language->get('column_model');
      $data['column_quantity'] = $this->language->get('column_quantity');
      $data['column_price'] = $this->language->get('column_price');
      $data['column_total'] = $this->language->get('column_total');

      //////////////////////////////////////////////////////////////////////////////////
      $data['column_day'] = $this->language->get('column_day');
      $data['column_model'] = $this->language->get('column_model');
      $data['column_article'] = $this->language->get('column_article');
      $data['column_description'] = $this->language->get('column_description');
      $data['column_comment'] = $this->language->get('column_comment');
      $data['column_remove'] = $this->language->get('column_remove');


      ///////////////////////////////////////////////////////////////////////////////////
      $data['sum_check_items_text'] = $this->language->get('sum_check_items_text');
      $data['sum_check_items_value'] = $this->language->get('sum_check_items_value');
      ///////////////////////////////////////////////////////////////////////////////////

      $data['button_update'] = $this->language->get('button_update');
      $data['button_remove'] = $this->language->get('button_remove');
      $data['button_shopping'] = $this->language->get('button_shopping');
      $data['button_checkout'] = $this->language->get('button_checkout');
      $data['column_date'] = $this->language->get('column_date');

      ///////////////////////////////////////////////////////////////////////////////////
      $data['button_save'] = $this->language->get('button_save');
      $data['button_delete_selected'] = $this->language->get('button_delete_selected');
      $data['button_clean'] = $this->language->get('button_clean');

      if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
        $data['error_warning'] = $this->language->get('error_stock');
      }
      elseif (isset($this->session->data['error'])) {
        $data['error_warning'] = $this->session->data['error'];

        unset($this->session->data['error']);
      }
      else {
        $data['error_warning'] = '';
      }

      if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
        $data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/simpleregister'));
      }
      else {
        $data['attention'] = '';
      }

      if (isset($this->session->data['success'])) {
        $data['success'] = $this->session->data['success'];

        unset($this->session->data['success']);
      }
      else {
        $data['success'] = '';
      }

      $data['action'] = $this->url->link('checkout/cart/edit', '', true);

      if ($this->config->get('config_cart_weight')) {
        $data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
      }
      else {
        $data['weight'] = '';
      }

      $this->load->model('tool/image');
      $this->load->model('tool/upload');

      $data['products'] = array();

      $products = $this->cart->getProducts();

      foreach ($products as $product) {
        $product_total = 0;

        foreach ($products as $product_2) {
          if ($product_2['product_id'] == $product['product_id']) {
            $product_total += $product_2['quantity'];
          }
        }

        if ($product['minimum'] > $product_total) {
          $data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
        }

        if ($product['image']) {
          $image = $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
        }
        else {
          $image = '';
        }

        $option_data = array();

        foreach ($product['option'] as $option) {
          if ($option['type'] != 'file') {
            $value = $option['value'];
          }
          else {
            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

            if ($upload_info) {
              $value = $upload_info['name'];
            }
            else {
              $value = '';
            }
          }

          $option_data[] = array(
              'name' => $option['name'],
              'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
          );
        }

        // Display prices
        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
          $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
        }
        else {
          $price = false;
        }

        // Display prices
        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
          $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']);
        }
        else {
          $total = false;
        }

        $recurring = '';

        if ($product['recurring']) {
          $frequencies = array(
              'day' => $this->language->get('text_day'),
              'week' => $this->language->get('text_week'),
              'semi_month' => $this->language->get('text_semi_month'),
              'month' => $this->language->get('text_month'),
              'year' => $this->language->get('text_year'),
          );

          if ($product['recurring']['trial']) {
            $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
          }

          if ($product['recurring']['duration']) {
            $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
          }
          else {
            $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
          }
        }

        if (isset($product['tecdoc']) && $product['tecdoc'] == 1) {
          $data['products'][] = array(
              'cart_id' => $product['cart_id'],
              'thumb' => $image,
              'name' => $product['name'],
              'model' => $product['model'],
              'option' => $option_data,
              'recurring' => $recurring,
              'quantity' => $product['quantity'],
              'date_added' => $product['date_added'],
              'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
              'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
              'price' => $price,
              'total' => $total,
              'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
              'tecdoc' => 1,
              'product_url' => $product['product_url'],
              'day' => $product['day'],
              'article' => $product['article'],
              'comment' => $product['comment'],
              'checkbox' => $product['checkbox'],
          );
        }
        else {

          $data['products'][] = array(
              'cart_id' => $product['cart_id'],
              'thumb' => $image,
              'name' => $product['name'],
              'model' => $product['model'],
              'option' => $option_data,
              'recurring' => $recurring,
              'quantity' => $product['quantity'],
              'date_added' => $product['date_added'],
              'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
              'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
              'price' => $price,
              'total' => $total,
              'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
              'comment' => $product['comment'],
              'checkbox' => $product['checkbox'],
          );
        }
      }

      // Gift Voucher
      $data['vouchers'] = array();

      if (!empty($this->session->data['vouchers'])) {
        foreach ($this->session->data['vouchers'] as $key => $voucher) {
          $data['vouchers'][] = array(
              'key' => $key,
              'description' => $voucher['description'],
              'amount' => $this->currency->format($voucher['amount'], $this->session->data['currency']),
              'remove' => $this->url->link('checkout/cart', 'remove=' . $key)
          );
        }
      }

      // Totals
      $this->load->model('extension/extension');

      $totals = array();
      $taxes = $this->cart->getTaxes();
      $total = 0;

      // Because __call can not keep var references so we put them into an array. 			
      $total_data = array(
          'totals' => &$totals,
          'taxes' => &$taxes,
          'total' => &$total
      );
      //print '<pre>';
      //var_dump($total_data);
      // Display prices
      if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
        $sort_order = array();

        $results = $this->model_extension_extension->getExtensions('total');
        //var_dump($results);

        foreach ($results as $key => $value) {
          $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }
        //var_dump($sort_order);

        array_multisort($sort_order, SORT_ASC, $results);
        //var_dump($results);

        foreach ($results as $result) {
          if ($this->config->get($result['code'] . '_status')) {
            $this->load->model('total/' . $result['code']);
            //print '========<br>code<br>=========';
            //var_dump('total/' . $result['code']);
            // We have to put the totals in an array so that they pass by reference.
            $this->{'model_total_' . $result['code']}->getTotal($total_data);
            //var_dump($total_data);
          }
        }

        $sort_order = array();

        foreach ($totals as $key => $value) {
          $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $totals);
      }
      //var_dump($total_data);

      $data['totals'] = array();

      foreach ($totals as $total) {
        $data['totals'][] = array(
            'code' => '_' . $total['code'],
            'title' => $total['title'],
            'text' => $this->currency->format($total['value'], $this->session->data['currency'])
        );
      }

      $data['continue'] = $this->url->link('common/home');

      $data['checkout'] = $this->url->link('checkout/checkout', '', true);

      $this->load->model('extension/extension');

      $data['modules'] = array();

      $files = glob(DIR_APPLICATION . '/controller/total/*.php');

      if ($files) {
        foreach ($files as $file) {
          $result = $this->load->controller('total/' . basename($file, '.php'));

          if ($result) {
            $data['modules'][] = $result;
          }
        }
      }

      $data['column_left'] = $this->load->controller('common/column_left');
      $data['column_right'] = $this->load->controller('common/column_right');
      $data['content_top'] = $this->load->controller('common/content_top');
      $data['content_bottom'] = $this->load->controller('common/content_bottom');
      $data['footer'] = $this->load->controller('common/footer');
      $data['header'] = $this->load->controller('common/header');

      $this->response->setOutput($this->load->view('checkout/cart', $data));
    }
    else {
      $data['heading_title'] = $this->language->get('heading_title');

      $data['text_error'] = $this->language->get('text_empty');

      $data['button_continue'] = $this->language->get('button_continue');

      $data['continue'] = $this->url->link('common/home');

      unset($this->session->data['success']);

      $data['column_left'] = $this->load->controller('common/column_left');
      $data['column_right'] = $this->load->controller('common/column_right');
      $data['content_top'] = $this->load->controller('common/content_top');
      $data['content_bottom'] = $this->load->controller('common/content_bottom');
      $data['footer'] = $this->load->controller('common/footer');
      $data['header'] = $this->load->controller('common/header');

      $this->response->setOutput($this->load->view('error/not_found', $data));
    }
  }

  public function add() {
    $this->load->language('checkout/cart');

    $json = array();

    if (isset($this->request->post['product_id'])) {
      $product_id = (int) $this->request->post['product_id'];
    }
    else {
      $product_id = 0;
    }

    $this->load->model('catalog/product');

    $product_info = $this->model_catalog_product->getProduct($product_id);

    if ($product_info) {
      if (isset($this->request->post['quantity']) && ((int) $this->request->post['quantity'] >= $product_info['minimum'])) {
        $quantity = (int) $this->request->post['quantity'];
      }
      else {
        $quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
      }

      if (isset($this->request->post['option'])) {
        $option = array_filter($this->request->post['option']);
      }
      else {
        $option = array();
      }

      $product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

      foreach ($product_options as $product_option) {
        if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
          $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
        }
      }

      if (isset($this->request->post['recurring_id'])) {
        $recurring_id = $this->request->post['recurring_id'];
      }
      else {
        $recurring_id = 0;
      }

      $recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

      if ($recurrings) {
        $recurring_ids = array();

        foreach ($recurrings as $recurring) {
          $recurring_ids[] = $recurring['recurring_id'];
        }

        if (!in_array($recurring_id, $recurring_ids)) {
          $json['error']['recurring'] = $this->language->get('error_recurring_required');
        }
      }

      if (!$json) {
        $this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);

        $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

        // Unset all shipping and payment methods
        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);

        // Totals
        $this->load->model('extension/extension');

        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        // Because __call can not keep var references so we put them into an array. 			
        $total_data = array(
            'totals' => &$totals,
            'taxes' => &$taxes,
            'total' => &$total
        );

        // Display prices
        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
          $sort_order = array();

          $results = $this->model_extension_extension->getExtensions('total');

          foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
          }

          array_multisort($sort_order, SORT_ASC, $results);

          foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
              $this->load->model('total/' . $result['code']);

              // We have to put the totals in an array so that they pass by reference.
              $this->{'model_total_' . $result['code']}->getTotal($total_data);
            }
          }

          $sort_order = array();

          foreach ($totals as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
          }

          array_multisort($sort_order, SORT_ASC, $totals);
        }

        $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
      }
      else {
        $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function addFromTDM() {
    $this->load->language('checkout/cart');
    $json = array();
    
    if ($this->request->post['product-id']) {
      $this->load->model('tecdoc/cart');

      //$results = $this->model_extension_extension->getExtensions('total');

      $json['product-id'] = $this->request->post['product-id'];

      if ($this->request->post['quantity'] > 0) {
        $json['quantity'] = $this->request->post['quantity'];
      }
      else {
        $json['quantity'] = 1;
      }
      if ($this->customer->isLogged()) {
          
          $u_group_id = $this->customer->getGroupId();
          $this->load->model('account/customer_group');
          $customerGroup=$this->model_account_customer_group->getCustomerGroup($u_group_id);
          $mark_up=$customerGroup['mark_up'];
            
        }  
       else {
          $u_group_id = 1;
           $mark_up=0;
      }
     
         
          //$u_group_id = $this->customer->getGroupId();
        
      $product = $this->model_tecdoc_cart->getProduct($this->request->get['product-id'], $u_group_id,$mark_up);
      if($product){
        $product['quantity'] = $json['quantity'];
        $this->cart->add($product['product_id'], $json['quantity'], $product, 0);
        
        $json['success'] = sprintf($this->language->get('text_success'), $product['product_url'], $product['name'], $this->url->link('checkout/cart'));

        // Unset all shipping and payment methods
        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);

        // Totals
        $this->load->model('extension/extension');

        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        // Because __call can not keep var references so we put them into an array. 			
        $total_data = array(
            'totals' => &$totals,
            'taxes' => &$taxes,
            'total' => &$total
        );

        // Display prices
        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
          $sort_order = array();

          $results = $this->model_extension_extension->getExtensions('total');

          foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
          }

          array_multisort($sort_order, SORT_ASC, $results);

          foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
              $this->load->model('total/' . $result['code']);

              // We have to put the totals in an array so that they pass by reference.
              $this->{'model_total_' . $result['code']}->getTotal($total_data);
            }
          }

          
          $sort_order = array();

          foreach ($totals as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
          }

          array_multisort($sort_order, SORT_ASC, $totals);
        }

        $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
      }      
    }
    
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function edit_check() {
    if (!empty($this->request->post['key']) && isset($this->request->post['check'])) {
      $this->cart->update_selected($this->request->post['key'], $this->request->post['check']);
    }
  }

  public function edit_ajax() {
    $this->load->language('checkout/cart');

    $json = array();
    //data: 'key=' + key + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1) + '&comment=' + (typeof (comment) != 'undefined' ? comment : ''),
    // Update
    if (!empty($this->request->post['quantity'])) {
      if (is_array($this->request->post['quantity'])) {
        foreach ($this->request->post['quantity'] as $key => $value) {
          $this->cart->update($key, $value);
        }
      }
      else {
        $this->cart->update($this->request->post['key'], $this->request->post['quantity'], $this->request->post['comment'] /* , $this->request->post['checkbox'] */);
      }

      unset($this->session->data['shipping_method']); //unset удаляет переменную
      unset($this->session->data['shipping_methods']);
      unset($this->session->data['payment_method']);
      unset($this->session->data['payment_methods']);
      unset($this->session->data['reward']);

      //$this->response->redirect($this->url->link('checkout/cart_ajax'));
      // Totals edit
      $this->load->model('extension/extension');

      $totals = array();
      $taxes = $this->cart->getTaxes();
      $total = 0;

      // Because __call can not keep var references so we put them into an array. 			
      $total_data = array(
          'totals' => &$totals,
          'taxes' => &$taxes,
          'total' => &$total
      );
      // Display prices
      if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
        $sort_order = array();

        $results = $this->model_extension_extension->getExtensions('total');

        foreach ($results as $key => $value) {
          $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        } // обновление ajax

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
          if ($this->config->get($result['code'] . '_status')) {
            $this->load->model('total/' . $result['code']);

            // We have to put the totals in an array so that they pass by reference.
            $this->{'model_total_' . $result['code']}->getTotal($total_data);
            $json['totals']['_' . $result['code']] = $this->currency->format($total_data['total'], $this->session->data['currency']);
          }
        } //сохранение

        $sort_order = array();

        foreach ($totals as $key => $value) {
          $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $totals);
      }

      $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function edit() {
    $this->load->language('checkout/cart');

    $json = array();

    // Update
    if (!empty($this->request->post['quantity'])) {
      if (is_array($this->request->post['quantity'])) {
        foreach ($this->request->post['quantity'] as $key => $value) {
          $this->cart->update($key, $value);
        }
      }
      else {
        $this->cart->update($this->request->post['key'], $this->request->post['quantity']);
      }

      unset($this->session->data['shipping_method']);
      unset($this->session->data['shipping_methods']);
      unset($this->session->data['payment_method']);
      unset($this->session->data['payment_methods']);
      unset($this->session->data['reward']);

      $this->response->redirect($this->url->link('checkout/cart'));
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function remove() {
    $this->load->language('checkout/cart');

    $json = array();

    // Remove
    if (isset($this->request->post['key'])) {
      $this->cart->remove($this->request->post['key']);

      unset($this->session->data['vouchers'][$this->request->post['key']]);

      $this->session->data['success'] = $this->language->get('text_remove');

      unset($this->session->data['shipping_method']);
      unset($this->session->data['shipping_methods']);
      unset($this->session->data['payment_method']);
      unset($this->session->data['payment_methods']);
      unset($this->session->data['reward']);

      // Totals
      $this->load->model('extension/extension');

      $totals = array();
      $taxes = $this->cart->getTaxes();
      $total = 0;

      // Because __call can not keep var references so we put them into an array. 			
      $total_data = array(
          'totals' => &$totals,
          'taxes' => &$taxes,
          'total' => &$total
      );

      // Display prices
      if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
        $sort_order = array();

        $results = $this->model_extension_extension->getExtensions('total');

        foreach ($results as $key => $value) {
          $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
          if ($this->config->get($result['code'] . '_status')) {
            $this->load->model('total/' . $result['code']);

            // We have to put the totals in an array so that they pass by reference.
            $this->{'model_total_' . $result['code']}->getTotal($total_data);
            $json['totals']['_' . $result['code']] = $this->currency->format($total_data['total'], $this->session->data['currency']);
          }
        }

        $sort_order = array();

        foreach ($totals as $key => $value) {
          $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $totals);
      }

      $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
    //echo $this->response;
  }

}
