<?php
class ControllerAccountMessage extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/message');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/message', '', 'SSL')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['entry_message'] = $this->language->get('entry_message');
		
		$data['button_send'] = $this->language->get('button_send');

		$data['recurring'] = $this->url->link('account/recurring', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '../message_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . 'account/message_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('account/message_form.tpl', $data));
		}
	}

	public function sendMessage() {
		$json = array();
		$this->load->model('account/message');
		$this->language->load('account/message');
		if($this->customer->getId()) {
			if(empty($this->request->post['message'])) {
				$json['error_warning'] = $this->language->get('error_message');
			}
		
			if(!$json){
				$filter_data = array(
					'customer_id'          => $this->customer->getId(),
					'sender'               => 'customer',
					'message'              => $this->request->post['message'],
				);
				$this->model_account_message->sendMessage($filter_data);
				$json['success'] = $this->language->get('text_success');
			}
		}else{
			$json['redirect'] = $this->url->link('account/login');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	public function viewMessage() {
			if($this->customer->getId()) {
			$data['messages'] =array();
			$this->load->language('account/message');
			$this->load->model('account/customer');
			$this->load->model('account/message');
			$data['text_administrator']  = $this->language->get('text_administrator');
			$data['text_no_message']  = $this->language->get('text_no_message');
			
			$customer_info =  $this->model_account_customer->getCustomer($this->customer->getId());
			
			$data['customer_name'] = ($customer_info) ?  $customer_info['firstname'].' '.$customer_info['lastname'] : '';
			if($customer_info) {
				$filter_data =array(
					'customer_id' => $this->customer->getId(),
					'filter_sender' => 'user',
					'filter_status' => '0',
				);
				$messages =  $this->model_account_message->getMessagesByCustomerId($filter_data);
				
				$this->model_account_message->updateReadStatus($filter_data);
				
				$total_unread = $this->model_account_message->getTotalUnreadMessages($filter_data);
				if($total_unread){
					$data['total_unread'] = '<b>'. $total_unread .'</b>';
				}else{
					$data['total_unread'] = '';
				}
				
				foreach($messages as $message) {
					$data['messages'][] = array(
						'sender'          => $message['sender'],
						'message'         => html_entity_decode($message['message'], ENT_QUOTES, 'UTF-8'),
						'name'            => $customer_info['firstname'].' '.$customer_info['lastname'],
						//'date_added'      => $this->time_ago($message['date_added']),
						'date_added'      => $message['date_added'],
					);
				}
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'account/customer_message_view.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . 'account/customer_message_view.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('account/customer_message_view.tpl', $data));
			}
		}
	}
	
	public function time_ago($date) {

		if(empty($date)) {

		return "No date provided";

		}

		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");

		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();

		$unix_date = strtotime($date);

		// check validity of date

		if(empty($unix_date)) {

		return "Bad date";

		}

		// is it future date or past date

		if($now > $unix_date) {

		$difference = $now - $unix_date;

		$tense = "ago";

		} else {

		$difference = $unix_date - $now;
		$tense = "from now";}

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {

		$difference /= $lengths[$j];

		}

		$difference = round($difference);

		if($difference != 1) {

		$periods[$j].= "s";

		}

		return "$difference $periods[$j] {$tense}";
	}
}