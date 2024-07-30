<?php

class ControllerApiOrder extends Controller {

    public function add() {
        $this->load->language('api/order');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            // Customer
            if (!isset($this->session->data['customer'])) {
                $json['error'] = $this->language->get('error_customer');
            }

            // Payment Address
            if (!isset($this->session->data['payment_address'])) {
                $json['error'] = $this->language->get('error_payment_address');
            }

            // Payment Method
            if (!$json && !empty($this->request->post['payment_method'])) {
                if (empty($this->session->data['payment_methods'])) {
                    $json['error'] = $this->language->get('error_no_payment');
                } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                    $json['error'] = $this->language->get('error_payment_method');
                }

                if (!$json) {
                    $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
                }
            }

            if (!isset($this->session->data['payment_method'])) {
                $json['error'] = $this->language->get('error_payment_method');
            }

            // Shipping
            if ($this->cart->hasShipping()) {
                // Shipping Address
                if (!isset($this->session->data['shipping_address'])) {
                    $json['error'] = $this->language->get('error_shipping_address');
                }

                // Shipping Method
                if (!$json && !empty($this->request->post['shipping_method'])) {
                    if (empty($this->session->data['shipping_methods'])) {
                        $json['error'] = $this->language->get('error_no_shipping');
                    } else {
                        $shipping = explode('.', $this->request->post['shipping_method']);

                        if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                            $json['error'] = $this->language->get('error_shipping_method');
                        }
                    }

                    if (!$json) {
                        $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                    }
                }

                // Shipping Method
                if (!isset($this->session->data['shipping_method'])) {
                    $json['error'] = $this->language->get('error_shipping_method');
                }
            } else {
                unset($this->session->data['shipping_address']);
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
            }

            // Cart
            if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
                $json['error'] = $this->language->get('error_stock');
            }

            // Validate minimum quantity requirements.
            $products = $this->cart->getProducts();

            foreach ($products as $product) {
                $product_total = 0;

                foreach ($products as $product_2) {
                    if ($product_2['product_id'] == $product['product_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                if ($product['minimum'] > $product_total) {
                    $json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

                    break;
                }
            }

            if (!$json) {
                $json['success'] = $this->language->get('text_success');

                $order_data = array();

                // Store Details
                $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                $order_data['store_id'] = $this->config->get('config_store_id');
                $order_data['store_name'] = $this->config->get('config_name');
                $order_data['store_url'] = $this->config->get('config_url');

                // Customer Details
                $order_data['customer_id'] = $this->session->data['customer']['customer_id'];
                $order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
                $order_data['firstname'] = $this->session->data['customer']['firstname'];
                $order_data['lastname'] = $this->session->data['customer']['lastname'];
                $order_data['email'] = $this->session->data['customer']['email'];
                $order_data['telephone'] = $this->session->data['customer']['telephone'];
                $order_data['fax'] = $this->session->data['customer']['fax'];
                $order_data['custom_field'] = $this->session->data['customer']['custom_field'];

                // Payment Details
                $order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
                $order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
                $order_data['payment_company'] = $this->session->data['payment_address']['company'];
                $order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
                $order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
                $order_data['payment_city'] = $this->session->data['payment_address']['city'];
                $order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
                $order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
                $order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
                $order_data['payment_country'] = $this->session->data['payment_address']['country'];
                $order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
                $order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
                $order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

                if (isset($this->session->data['payment_method']['title'])) {
                    $order_data['payment_method'] = $this->session->data['payment_method']['title'];
                } else {
                    $order_data['payment_method'] = '';
                }

                if (isset($this->session->data['payment_method']['code'])) {
                    $order_data['payment_code'] = $this->session->data['payment_method']['code'];
                } else {
                    $order_data['payment_code'] = '';
                }

                // Shipping Details
                if ($this->cart->hasShipping()) {
                    $order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
                    $order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
                    $order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
                    $order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
                    $order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
                    $order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
                    $order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
                    $order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
                    $order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
                    $order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
                    $order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
                    $order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
                    $order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

                    if (isset($this->session->data['shipping_method']['title'])) {
                        $order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
                    } else {
                        $order_data['shipping_method'] = '';
                    }

                    if (isset($this->session->data['shipping_method']['code'])) {
                        $order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
                    } else {
                        $order_data['shipping_code'] = '';
                    }
                } else {
                    $order_data['shipping_firstname'] = '';
                    $order_data['shipping_lastname'] = '';
                    $order_data['shipping_company'] = '';
                    $order_data['shipping_address_1'] = '';
                    $order_data['shipping_address_2'] = '';
                    $order_data['shipping_city'] = '';
                    $order_data['shipping_postcode'] = '';
                    $order_data['shipping_zone'] = '';
                    $order_data['shipping_zone_id'] = '';
                    $order_data['shipping_country'] = '';
                    $order_data['shipping_country_id'] = '';
                    $order_data['shipping_address_format'] = '';
                    $order_data['shipping_custom_field'] = array();
                    $order_data['shipping_method'] = '';
                    $order_data['shipping_code'] = '';
                }

                // Products
                $order_data['products'] = array();

                foreach ($this->cart->getProducts() as $product) {
                    $option_data = array();

                    foreach ($product['option'] as $option) {
                        $option_data[] = array(
                            'product_option_id' => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'option_id' => $option['option_id'],
                            'option_value_id' => $option['option_value_id'],
                            'name' => $option['name'],
                            'value' => $option['value'],
                            'type' => $option['type']
                        );
                    }

                    $order_data['products'][] = array(
                        'product_id' => $product['product_id'],
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'option' => $option_data,
                        'download' => $product['download'],
                        'quantity' => $product['quantity'],
                        'subtract' => $product['subtract'],
                        'price' => $product['price'],
                        'total' => $product['total'],
                        'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                        'reward' => $product['reward']
                    );
                }

                // Gift Voucher
                $order_data['vouchers'] = array();

                if (!empty($this->session->data['vouchers'])) {
                    foreach ($this->session->data['vouchers'] as $voucher) {
                        $order_data['vouchers'][] = array(
                            'description' => $voucher['description'],
                            'code' => token(10),
                            'to_name' => $voucher['to_name'],
                            'to_email' => $voucher['to_email'],
                            'from_name' => $voucher['from_name'],
                            'from_email' => $voucher['from_email'],
                            'voucher_theme_id' => $voucher['voucher_theme_id'],
                            'message' => $voucher['message'],
                            'amount' => $voucher['amount']
                        );
                    }
                }

                // Order Totals
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

                foreach ($total_data['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data['totals']);

                $order_data = array_merge($order_data, $total_data);

                if (isset($this->request->post['comment'])) {
                    $order_data['comment'] = $this->request->post['comment'];
                } else {
                    $order_data['comment'] = '';
                }

                if (isset($this->request->post['affiliate_id'])) {
                    $subtotal = $this->cart->getSubTotal();

                    // Affiliate
                    $this->load->model('affiliate/affiliate');

                    $affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

                    if ($affiliate_info) {
                        $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                        $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                    } else {
                        $order_data['affiliate_id'] = 0;
                        $order_data['commission'] = 0;
                    }

                    // Marketing
                    $order_data['marketing_id'] = 0;
                    $order_data['tracking'] = '';
                } else {
                    $order_data['affiliate_id'] = 0;
                    $order_data['commission'] = 0;
                    $order_data['marketing_id'] = 0;
                    $order_data['tracking'] = '';
                }

                $order_data['language_id'] = $this->config->get('config_language_id');
                $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
                $order_data['currency_code'] = $this->session->data['currency'];
                $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
                $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
                } else {
                    $order_data['forwarded_ip'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data['user_agent'] = '';
                }

                if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                    $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
                } else {
                    $order_data['accept_language'] = '';
                }

                $this->load->model('checkout/order');

                $json['order_id'] = $this->model_checkout_order->addOrder($order_data);

                // Set the order history
                if (isset($this->request->post['order_status_id'])) {
                    $order_status_id = $this->request->post['order_status_id'];
                } else {
                    $order_status_id = $this->config->get('config_order_status_id');
                }

                $this->model_checkout_order->addOrderHistory($json['order_id'], $order_status_id);
            }
        }

        if (isset($this->request->server['HTTP_ORIGIN'])) {
            $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
            $this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            $this->response->addHeader('Access-Control-Max-Age: 1000');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit() {
        $this->load->language('api/order');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $order_info = $this->model_checkout_order->getOrder($order_id);

            if ($order_info) {
                // Customer
                if (!isset($this->session->data['customer'])) {
                    $json['error'] = $this->language->get('error_customer');
                }

                // Payment Address
                if (!isset($this->session->data['payment_address'])) {
                    $json['error'] = $this->language->get('error_payment_address');
                }

                // Payment Method
                if (!$json && !empty($this->request->post['payment_method'])) {
                    if (empty($this->session->data['payment_methods'])) {
                        $json['error'] = $this->language->get('error_no_payment');
                    } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                        $json['error'] = $this->language->get('error_payment_method');
                    }

                    if (!$json) {
                        $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
                    }
                }

                if (!isset($this->session->data['payment_method'])) {
                    $json['error'] = $this->language->get('error_payment_method');
                }

                // Shipping
                if ($this->cart->hasShipping()) {
                    // Shipping Address
                    if (!isset($this->session->data['shipping_address'])) {
                        $json['error'] = $this->language->get('error_shipping_address');
                    }

                    // Shipping Method
                    if (!$json && !empty($this->request->post['shipping_method'])) {
                        if (empty($this->session->data['shipping_methods'])) {
                            $json['error'] = $this->language->get('error_no_shipping');
                        } else {
                            $shipping = explode('.', $this->request->post['shipping_method']);

                            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                                $json['error'] = $this->language->get('error_shipping_method');
                            }
                        }

                        if (!$json) {
                            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                        }
                    }

                    if (!isset($this->session->data['shipping_method'])) {
                        $json['error'] = $this->language->get('error_shipping_method');
                    }
                } else {
                    unset($this->session->data['shipping_address']);
                    unset($this->session->data['shipping_method']);
                    unset($this->session->data['shipping_methods']);
                }

                // Cart
                if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
                    $json['error'] = $this->language->get('error_stock');
                }

                // Validate minimum quantity requirements.
                $products = $this->cart->getProducts();

                foreach ($products as $product) {
                    $product_total = 0;

                    foreach ($products as $product_2) {
                        if ($product_2['product_id'] == $product['product_id']) {
                            $product_total += $product_2['quantity'];
                        }
                    }

                    if ($product['minimum'] > $product_total) {
                        $json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

                        break;
                    }
                }

                if (!$json) {
                    $json['success'] = $this->language->get('text_success');

                    $order_data = array();

                    // Store Details
                    $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                    $order_data['store_id'] = $this->config->get('config_store_id');
                    $order_data['store_name'] = $this->config->get('config_name');
                    $order_data['store_url'] = $this->config->get('config_url');

                    // Customer Details
                    $order_data['customer_id'] = $this->session->data['customer']['customer_id'];
                    $order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
                    $order_data['firstname'] = $this->session->data['customer']['firstname'];
                    $order_data['lastname'] = $this->session->data['customer']['lastname'];
                    $order_data['email'] = $this->session->data['customer']['email'];
                    $order_data['telephone'] = $this->session->data['customer']['telephone'];
                    $order_data['fax'] = $this->session->data['customer']['fax'];
                    $order_data['custom_field'] = $this->session->data['customer']['custom_field'];

                    // Payment Details
                    $order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
                    $order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
                    $order_data['payment_company'] = $this->session->data['payment_address']['company'];
                    $order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
                    $order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
                    $order_data['payment_city'] = $this->session->data['payment_address']['city'];
                    $order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
                    $order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
                    $order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
                    $order_data['payment_country'] = $this->session->data['payment_address']['country'];
                    $order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
                    $order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
                    $order_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];

                    if (isset($this->session->data['payment_method']['title'])) {
                        $order_data['payment_method'] = $this->session->data['payment_method']['title'];
                    } else {
                        $order_data['payment_method'] = '';
                    }

                    if (isset($this->session->data['payment_method']['code'])) {
                        $order_data['payment_code'] = $this->session->data['payment_method']['code'];
                    } else {
                        $order_data['payment_code'] = '';
                    }

                    // Shipping Details
                    if ($this->cart->hasShipping()) {
                        $order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
                        $order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
                        $order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
                        $order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
                        $order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
                        $order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
                        $order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
                        $order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
                        $order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
                        $order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
                        $order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
                        $order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
                        $order_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'];

                        if (isset($this->session->data['shipping_method']['title'])) {
                            $order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
                        } else {
                            $order_data['shipping_method'] = '';
                        }

                        if (isset($this->session->data['shipping_method']['code'])) {
                            $order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
                        } else {
                            $order_data['shipping_code'] = '';
                        }
                    } else {
                        $order_data['shipping_firstname'] = '';
                        $order_data['shipping_lastname'] = '';
                        $order_data['shipping_company'] = '';
                        $order_data['shipping_address_1'] = '';
                        $order_data['shipping_address_2'] = '';
                        $order_data['shipping_city'] = '';
                        $order_data['shipping_postcode'] = '';
                        $order_data['shipping_zone'] = '';
                        $order_data['shipping_zone_id'] = '';
                        $order_data['shipping_country'] = '';
                        $order_data['shipping_country_id'] = '';
                        $order_data['shipping_address_format'] = '';
                        $order_data['shipping_custom_field'] = array();
                        $order_data['shipping_method'] = '';
                        $order_data['shipping_code'] = '';
                    }

                    // Products
                    $order_data['products'] = array();

                    foreach ($this->cart->getProducts() as $product) {
                        $option_data = array();

                        foreach ($product['option'] as $option) {
                            $option_data[] = array(
                                'product_option_id' => $option['product_option_id'],
                                'product_option_value_id' => $option['product_option_value_id'],
                                'option_id' => $option['option_id'],
                                'option_value_id' => $option['option_value_id'],
                                'name' => $option['name'],
                                'value' => $option['value'],
                                'type' => $option['type']
                            );
                        }

                        $order_data['products'][] = array(
                            'product_id' => $product['product_id'],
                            'name' => $product['name'],
                            'model' => $product['model'],
                            'option' => $option_data,
                            'download' => $product['download'],
                            'quantity' => $product['quantity'],
                            'subtract' => $product['subtract'],
                            'price' => $product['price'],
                            'total' => $product['total'],
                            'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                            'reward' => $product['reward']
                        );
                    }

                    // Gift Voucher
                    $order_data['vouchers'] = array();

                    if (!empty($this->session->data['vouchers'])) {
                        foreach ($this->session->data['vouchers'] as $voucher) {
                            $order_data['vouchers'][] = array(
                                'description' => $voucher['description'],
                                'code' => token(10),
                                'to_name' => $voucher['to_name'],
                                'to_email' => $voucher['to_email'],
                                'from_name' => $voucher['from_name'],
                                'from_email' => $voucher['from_email'],
                                'voucher_theme_id' => $voucher['voucher_theme_id'],
                                'message' => $voucher['message'],
                                'amount' => $voucher['amount']
                            );
                        }
                    }

                    // Order Totals
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

                    foreach ($total_data['totals'] as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $total_data['totals']);

                    $order_data = array_merge($order_data, $total_data);

                    if (isset($this->request->post['comment'])) {
                        $order_data['comment'] = $this->request->post['comment'];
                    } else {
                        $order_data['comment'] = '';
                    }

                    if (isset($this->request->post['affiliate_id'])) {
                        $subtotal = $this->cart->getSubTotal();

                        // Affiliate
                        $this->load->model('affiliate/affiliate');

                        $affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

                        if ($affiliate_info) {
                            $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                            $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                        } else {
                            $order_data['affiliate_id'] = 0;
                            $order_data['commission'] = 0;
                        }
                    } else {
                        $order_data['affiliate_id'] = 0;
                        $order_data['commission'] = 0;
                    }

                    $this->model_checkout_order->editOrder($order_id, $order_data);

                    // Set the order history
                    if (isset($this->request->post['order_status_id'])) {
                        $order_status_id = $this->request->post['order_status_id'];
                    } else {
                        $order_status_id = $this->config->get('config_order_status_id');
                    }

                    $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
                }
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        if (isset($this->request->server['HTTP_ORIGIN'])) {
            $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
            $this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            $this->response->addHeader('Access-Control-Max-Age: 1000');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete() {
        $this->load->language('api/order');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $order_info = $this->model_checkout_order->getOrder($order_id);

            if ($order_info) {
                $this->model_checkout_order->deleteOrder($order_id);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        if (isset($this->request->server['HTTP_ORIGIN'])) {
            $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
            $this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            $this->response->addHeader('Access-Control-Max-Age: 1000');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function info() {
        $this->load->language('api/order');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $json = $this->model_checkout_order->getOrder($order_id);

            if ($order_info) {
                $json['order'] = $order_info;

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }


        if (isset($this->request->server['HTTP_ORIGIN'])) {
            $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
            $this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            $this->response->addHeader('Access-Control-Max-Age: 1000');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function history() {
        $this->load->language('api/order');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            // Add keys for missing post vars
            $keys = array(
                'order_status_id',
                'notify',
                'override',
                'comment'
            );

            foreach ($keys as $key) {
                if (!isset($this->request->post[$key])) {
                    $this->request->post[$key] = '';
                }
            }

            $this->load->model('checkout/order');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $order_info = $this->model_checkout_order->getOrder($order_id);

            if ($order_info) {
                $this->model_checkout_order->addOrderHistory($order_id, $this->request->post['order_status_id'], $this->request->post['comment'], $this->request->post['notify'], $this->request->post['override']);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }

            if (isset($this->request->server['HTTP_ORIGIN'])) {
                $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
                $this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
                $this->response->addHeader('Access-Control-Max-Age: 1000');
                $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function historyProductStatus() {
        $this->load->language('api/order');

        $json = array();

        // Проверка разрешения на работу с API
        if (!isset($this->session->data['api_id'])) {
            // Если нет разрешения, получить сообщение ошибки доступа
            $json['error'] = $this->language->get('error_permission');
            //$json['data1'] = $this->session;
        } else {
            // Add keys for missing post vars
            $keys = array(
                'order_product_status_id',
                'notify',
                'comment'
            );

            foreach ($keys as $key) {
                if (!isset($this->request->post[$key])) {
                    $this->request->post[$key] = '';
                }
            }

            $this->load->model('checkout/order');
            $this->load->model('api/order');

            if (isset($this->request->get['order_product_id'])) {
                $order_product_id = $this->request->get['order_product_id'];
            } else {
                $order_product_id = 0;
            }

            // @note тут проверка на существование заказа
            // @todo сделать проверку на существование товара в заказе
            // а так после введения workflow определить возможные доступные следующие статусы
            // и посмотреть влияние на сам статус заказа (тут очень серьезная задача)
            $order_product_info = $this->model_checkout_order->getOrderProduct($order_product_id);
            $order_id = $this->model_api_order->getOrderId($order_product_id);
            
            $order_product_data = $this->model_api_order->getOrderProduct($order_product_id);
            
            $previous_product_status_id = $order_product_data['status_id'];
            $new_product_status_id = $this->request->post['order_product_status_id'];
            
            $previous_product_status_id = $this->model_api_order->getMainStatus($previous_product_status_id);
            $new_product_status_id = $this->model_api_order->getMainStatus($new_product_status_id);
            
            $transaction_operations = $this->model_api_order->getStatusChangeTransactions($previous_product_status_id, $new_product_status_id);
            
            if ($transaction_operations['reserve_operation'] === 1) {
                $this->model_api_order->executeTransaction($order_product_id, 2, '+');
            } elseif ($transaction_operations['reserve_operation'] === 2) {
                $this->model_api_order->executeTransaction($order_product_id, 2, '-');
            }
            
            if ($transaction_operations['current_operation'] === 1) {
                $this->model_api_order->executeTransaction($order_product_id, 1, '+');
            } elseif ($transaction_operations['current_operation'] === 2) {
                $this->model_api_order->executeTransaction($order_product_id, 1, '-');
            }
            
            if ($order_product_info) {
                // Если товар заказа существует, то выполнить обновление с занесением в историю
                $this->model_checkout_order->addOrderProductHistory($order_product_id, $this->request->post['order_product_status_id'], $this->request->post['comment'], $this->request->post['notify']);
                
                // Данные изменяемого товара
                $order_product_data = $this->model_api_order->getOrderProduct($order_product_id);
                
                // Данные родительского товара
                $parent_product_data = $this->model_api_order->getRootData($order_product_id);
                
                // Проверка на то, является ли товар - подтоваром
                if ($order_product_data['order_product_parent_id']) {
                    // Получение списка статусов всех родственных подтоваров для подтовара
                    $siblings_status_list = $this->model_api_order->getSiblingsProductStatusList($order_product_id);
                    
                    // Преобразование всех полученных статусов к основным статусам
                    foreach ($siblings_status_list as $key => $sibling_status) {
                        $siblings_status_list[$key] = $this->model_api_order->getAscendantStatus($sibling_status);
                    }
                    
                    $siblings_status_counter = array();
                    
                    // Подсчет используемых статусов
                    foreach ($siblings_status_list as $sibling_status) {
                        if (!isset($siblings_status_counter[$sibling_status])) {
                            // Если этот статус еще не встречался при подсчете,
                            // установить его
                            $siblings_status_counter[$sibling_status] = 1;
                        } else {
                            // Иначе просто увеличить счетчик
                            $siblings_status_counter[$sibling_status] += 1 ;
                        }
                    }
                                        
                    // Массив с наборами правил изменения статуса
                    $status_change_rules = $this->model_api_order->getOrderStatusChangeRules();
                    
                    $result_status_id = 0;
                    
                    // Цикл проверки всех правил изменения статусов для заказа!
                    foreach ($status_change_rules as $status_change_rule) {
                        $including_rule = 0;
                        $excluding_rule = 1;
                        
                        // Проверка статусов один из которых, обязан присутствовать в наборе
                        foreach ($status_change_rule['including'] as $status_id) {
                            if ( isset( $siblings_status_counter[ intval($status_id) ] ) ) {
                                $including_rule += $siblings_status_counter[ intval($status_id) ];
                            }
                            
                        }
                        
                        // Если не исполняется первое правило, то нет смысла проверять дальше
                        // заведомо отрицательный результат
                        if (!$including_rule) {
                            continue;
                        }
                        
                        // Проверка статусов ни один из которых не должен встречаться в наборе
                        if (!empty($status_change_rule['excluding'])) {
                            foreach ($status_change_rule['excluding'] as $status_id) {
                                if (isset($siblings_status_counter[intval($status_id)])) {
                                    $excluding_rule *= !$siblings_status_counter[intval($status_id)];
                                }
                            }
                        } else {
                            // Если нет статусов по правилу исключения, то и проверять нечего
                            // поэтому передаем true
                            $excluding_rule = true;
                        }
                        
                        // Объединение результатов предыдущих проверок в общий
                        // и его проверка
                        if ($including_rule * $excluding_rule) {
                            $result_status_id = $status_change_rule['order_status_id'];
                            break;
                        }
                    }
                    
                    if ($result_status_id) {
                        // Подмена статуса заказа поставленным в соответствие статусом товара в заказе
                        $result_status_id = intval($this->model_api_order->getCorrespondingStatus($result_status_id));

                        // Если новый статус не совпадает с предыдущим, тогда обновить статус родителя
                        if (intval($parent_product_data['order_product_status_id'])!==$result_status_id) {
                            $this->model_checkout_order->addOrderProductHistory($parent_product_data['order_product_id'], $result_status_id);
                        }
                    }
                }
                
                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        if (isset($this->request->server['HTTP_ORIGIN'])) {
            $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
            $this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            $this->response->addHeader('Access-Control-Max-Age: 1000');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        }
        //  if($this->request->post['order_product_status_id']) 

        /*
        $this->load->model('account/order');

        $products = $this->model_account_order->getOrderProducts($this->request->post['order_id']);

        $data = [];
        foreach ($products as $product) {
            $data[$product['order_product_status_id']] = 1;
        }
        if (count($data == 1)) {
            $this->orderAllStatusWorkflow($data, $product);
        } else {
            $this->orderIfOneAnyStatusWorkflow($data, $product);
        }
        */

        // Получаем значение текущего статуса у заказа
        $order_status_id = $this->model_api_order->getOrderStatusId($order_id);
        
        // Получаем список основных товаров в заказе
        $order_products = $this->model_api_order->getOrderRootProducts($order_id);
        
        // Преобразование всех полученных статусов к основным статусам
        foreach ($order_products as $key => $order_product) {
            $order_product_status_list[$key] = $this->model_api_order->getAscendantStatus($order_product['order_product_status_id']);
        }

        $siblings_status_counter = array();

        // Подсчет используемых статусов
        foreach ($order_product_status_list as $sibling_status) {
            if (!isset($siblings_status_counter[$sibling_status])) {
                // Если этот статус еще не встречался при подсчете,
                // установить его
                $siblings_status_counter[$sibling_status] = 1;
            } else {
                // Иначе просто увеличить счетчик
                $siblings_status_counter[$sibling_status] += 1 ;
            }
        }

        // Массив с наборами правил изменения статуса
        $status_change_rules = $this->model_api_order->getOrderStatusChangeRules();

        $result_status_id = 0;

        // Цикл проверки всех правил изменения статусов для заказа!
        foreach ($status_change_rules as $status_change_rule) {
            $including_rule = 0;
            $excluding_rule = 1;

            // Проверка статусов один из которых, обязан присутствовать в наборе
            foreach ($status_change_rule['including'] as $status_id) {
                if ( isset( $siblings_status_counter[ intval($status_id) ] ) ) {
                    $including_rule += $siblings_status_counter[ intval($status_id) ];
                }

            }

            // Если не исполняется первое правило, то нет смысла проверять дальше
            // заведомо отрицательный результат
            if (!$including_rule) {
                continue;
            }

            // Проверка статусов ни один из которых не должен встречаться в наборе
            if (!empty($status_change_rule['excluding'])) {
                foreach ($status_change_rule['excluding'] as $status_id) {
                    if (isset($siblings_status_counter[intval($status_id)])) {
                        $excluding_rule *= !$siblings_status_counter[intval($status_id)];
                    }
                }
            } else {
                // Если нет статусов по правилу исключения, то и проверять нечего
                // поэтому передаем true
                $excluding_rule = true;
            }

            // Объединение результатов предыдущих проверок в общий
            // и его проверка
            if ($including_rule * $excluding_rule) {
                $result_status_id = $status_change_rule['order_status_id'];
                break;
            }
        }

        if ($result_status_id) {
            // Если новый статус не совпадает с предыдущим, тогда обновить статус родителя
            if (intval($order_status_id)!==$result_status_id) {
                //$this->model_checkout_order->addOrderProductHistory($parent_product_data['order_product_id'], $result_status_id);
                // Заменить обновлением статуса заказа
                $this->model_api_order->updateOrderStatus($order_id, $result_status_id);
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function partitionProductStatus() {
        $this->load->language('api/order');

        $json = array();

        // Проверка разрешения на работу с API
        if (!isset($this->session->data['api_id'])) {
            // Если нет разрешения, получить сообщение ошибки доступа
            $json['error'] = $this->language->get('error_permission');
        } else {
            // Add keys for missing post vars
            $keys = array(
                'order_product_status_id',
                'notify',
                'comment'
            );

            foreach ($keys as $key) {
                if (!isset($this->request->post[$key])) {
                    $this->request->post[$key] = '';
                }
            }

            $this->load->model('checkout/order');
            $this->load->model('api/order');

            if (isset($this->request->post['order_product_id'])) {
                $order_product_id = $this->request->post['order_product_id'];
            } else {
                $order_product_id = 0;
            }

            // @note тут проверка на существование заказа
            // @todo сделать проверку на существование товара в заказе
            // а так после введения workflow определить возможные доступные следующие статусы
            // и посмотреть влияние на сам статус заказа (тут очень серьезная задача)
            $order_product_info = $this->model_checkout_order->getOrderProduct($order_product_id);
            $order_id = $this->model_api_order->getOrderId($order_product_id);
            
            if ($order_product_info) {
                // Данные изменяемого товара
                $order_product_data = $this->model_api_order->getOrderProduct($order_product_id);
                
                // Данные родительского товара
                $parent_product_data = $this->model_api_order->getRootData($order_product_id);
                
                // Проверка на то, является ли товар - подтоваром
                if ($order_product_data['order_product_parent_id']) {
                    // Получение списка статусов всех родственных подтоваров для подтовара
                    $siblings_status_list = $this->model_api_order->getSiblingsProductStatusList($order_product_id);
                    
                    // Преобразование всех полученных статусов к основным статусам
                    foreach ($siblings_status_list as $key => $sibling_status) {
                        $siblings_status_list[$key] = $this->model_api_order->getAscendantStatus($sibling_status);
                    }
                    
                    $siblings_status_counter = array();
                    
                    // Подсчет используемых статусов
                    foreach ($siblings_status_list as $sibling_status) {
                        if (!isset($siblings_status_counter[$sibling_status])) {
                            // Если этот статус еще не встречался при подсчете,
                            // установить его
                            $siblings_status_counter[$sibling_status] = 1;
                        } else {
                            // Иначе просто увеличить счетчик
                            $siblings_status_counter[$sibling_status] += 1 ;
                        }
                    }
                                        
                    // Массив с наборами правил изменения статуса
                    $status_change_rules = $this->model_api_order->getOrderStatusChangeRules();
                    
                    $result_status_id = 0;
                    
                    // Цикл проверки всех правил изменения статусов для заказа!
                    foreach ($status_change_rules as $status_change_rule) {
                        $including_rule = 0;
                        $excluding_rule = 1;
                        
                        // Проверка статусов один из которых, обязан присутствовать в наборе
                        foreach ($status_change_rule['including'] as $status_id) {
                            if ( isset( $siblings_status_counter[ intval($status_id) ] ) ) {
                                $including_rule += $siblings_status_counter[ intval($status_id) ];
                            }
                            
                        }
                        
                        // Если не исполняется первое правило, то нет смысла проверять дальше
                        // заведомо отрицательный результат
                        if (!$including_rule) {
                            continue;
                        }
                        
                        // Проверка статусов ни один из которых не должен встречаться в наборе
                        if (!empty($status_change_rule['excluding'])) {
                            foreach ($status_change_rule['excluding'] as $status_id) {
                                if (isset($siblings_status_counter[intval($status_id)])) {
                                    $excluding_rule *= !$siblings_status_counter[intval($status_id)];
                                }
                            }
                        } else {
                            // Если нет статусов по правилу исключения, то и проверять нечего
                            // поэтому передаем true
                            $excluding_rule = true;
                        }
                        
                        // Объединение результатов предыдущих проверок в общий
                        // и его проверка
                        if ($including_rule * $excluding_rule) {
                            $result_status_id = $status_change_rule['order_status_id'];
                            break;
                        }
                    }
                    
                    if ($result_status_id) {
                        // Подмена статуса заказа поставленным в соответствие статусом товара в заказе
                        $result_status_id = intval($this->model_api_order->getCorrespondingStatus($result_status_id));

                        // Если новый статус не совпадает с предыдущим, тогда обновить статус родителя
                        if (intval($parent_product_data['order_product_status_id'])!==$result_status_id) {
                            $this->model_checkout_order->addOrderProductHistory($parent_product_data['order_product_id'], $result_status_id);
                        }
                    }
                }
                
                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        if (isset($this->request->server['HTTP_ORIGIN'])) {
            $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
            $this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            $this->response->addHeader('Access-Control-Max-Age: 1000');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        }

        // Получаем значение текущего статуса у заказа
        $order_status_id = $this->model_api_order->getOrderStatusId($order_id);
        
        // Получаем список основных товаров в заказе
        $order_products = $this->model_api_order->getOrderRootProducts($order_id);
        
        // Преобразование всех полученных статусов к основным статусам
        foreach ($order_products as $key => $order_product) {
            $order_product_status_list[$key] = $this->model_api_order->getAscendantStatus($order_product['order_product_status_id']);
        }

        $siblings_status_counter = array();

        // Подсчет используемых статусов
        foreach ($order_product_status_list as $sibling_status) {
            if (!isset($siblings_status_counter[$sibling_status])) {
                // Если этот статус еще не встречался при подсчете,
                // установить его
                $siblings_status_counter[$sibling_status] = 1;
            } else {
                // Иначе просто увеличить счетчик
                $siblings_status_counter[$sibling_status] += 1 ;
            }
        }

        // Массив с наборами правил изменения статуса
        $status_change_rules = $this->model_api_order->getOrderStatusChangeRules();

        $result_status_id = 0;

        // Цикл проверки всех правил изменения статусов для заказа!
        foreach ($status_change_rules as $status_change_rule) {
            $including_rule = 0;
            $excluding_rule = 1;

            // Проверка статусов один из которых, обязан присутствовать в наборе
            foreach ($status_change_rule['including'] as $status_id) {
                if ( isset( $siblings_status_counter[ intval($status_id) ] ) ) {
                    $including_rule += $siblings_status_counter[ intval($status_id) ];
                }

            }

            // Если не исполняется первое правило, то нет смысла проверять дальше
            // заведомо отрицательный результат
            if (!$including_rule) {
                continue;
            }

            // Проверка статусов ни один из которых не должен встречаться в наборе
            if (!empty($status_change_rule['excluding'])) {
                foreach ($status_change_rule['excluding'] as $status_id) {
                    if (isset($siblings_status_counter[intval($status_id)])) {
                        $excluding_rule *= !$siblings_status_counter[intval($status_id)];
                    }
                }
            } else {
                // Если нет статусов по правилу исключения, то и проверять нечего
                // поэтому передаем true
                $excluding_rule = true;
            }

            // Объединение результатов предыдущих проверок в общий
            // и его проверка
            if ($including_rule * $excluding_rule) {
                $result_status_id = $status_change_rule['order_status_id'];
                break;
            }
        }

        if ($result_status_id) {
            // Если новый статус не совпадает с предыдущим, тогда обновить статус родителя
            if (intval($order_status_id)!==$result_status_id) {
                //$this->model_checkout_order->addOrderProductHistory($parent_product_data['order_product_id'], $result_status_id);
                // Заменить обновлением статуса заказа
                $this->model_api_order->updateOrderStatus($order_id, $result_status_id);
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    private function orderAllStatusWorkflow($data, $product) {        

        $this->load->model('account/order');

    }

    private function orderIfOneAnyStatusWorkflow($data) {
        
    }

}
