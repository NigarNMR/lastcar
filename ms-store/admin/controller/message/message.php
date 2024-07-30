<?php
class ControllerMessageMessage extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('message/message');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');
		$this->load->model('message/message');

		$this->getList();
	}

	public function add() {
		$this->load->language('message/message');
		
		if($this->request->get['customer_id']){
			$customer_id = $this->request->get['customer_id'];
		}else{
			$customer_id = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		$customer_info =  $this->model_customer_customer->getCustomer($customer_id);
		if(!$customer_info) {
			$this->response->redirect($this->url->link('message/message', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getForm();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = null;
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'message_date';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('message/message', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);


		$data['customers'] = array();

		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_email,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_status'            => $filter_status,
			'filter_approved'          => $filter_approved,
			'filter_date_added'        => $filter_date_added,
			'filter_ip'                => $filter_ip,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);

		$results = $this->model_message_message->getCustomers($filter_data);

		foreach ($results as $result) {
			$data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'name'           => $result['name'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'total_message'  => $result['total_message'],
				'button_new_message'  => sprintf($this->language->get('button_new_message'),$result['total_message']),
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'href'           => $this->url->link('message/message/add', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
				'link_customer'           => $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_login'] = $this->language->get('text_login');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_customer_group'] = $this->language->get('column_customer_group');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_approved'] = $this->language->get('column_approved');
		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_approved'] = $this->language->get('entry_approved');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_approve'] = $this->language->get('button_approve');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_message'] = $this->language->get('button_message');
		

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('message/message', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_email'] = $this->url->link('message/message', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
		$data['sort_customer_group'] = $this->url->link('message/message', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('message/message', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
		$data['sort_ip'] = $this->url->link('message/message', 'token=' . $this->session->data['token'] . '&sort=c.ip' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('message/message', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('message/message', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		$data['filter_approved'] = $filter_approved;
		$data['filter_ip'] = $filter_ip;
		$data['filter_date_added'] = $filter_date_added;

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('message/message_list.tpl', $data));
	}
	
	
	protected function getForm() {
	
		if($this->request->get['customer_id']){
			$data['customer_id'] = $this->request->get['customer_id'];
		}else{
			$data['customer_id'] = '';
		}
		$customer_info =  $this->model_customer_customer->getCustomer($data['customer_id']);
		if($customer_info) { 
			$data['text_add_message'] = sprintf($this->language->get('text_add_message'), $customer_info['firstname'] .' '.$customer_info['lastname']);
			$data['customer_name'] = $customer_info['firstname'] .' '.$customer_info['lastname'];
		}else{
			$data['text_add_message'] = '';
			$data['customer_name'] = '';
		}
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_message'] = $this->language->get('entry_message');
		

		$data['button_send'] = $this->language->get('button_send');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('message/message', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['back'] = $this->url->link('message/message', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('message/message_form.tpl', $data));
	}
	
	
	public function sendMessage() {
		$json = array();
		$this->load->model('message/message');
		$this->language->load('message/message');
		if(isset($this->request->get['customer_id'])) {
			if(empty($this->request->post['message']) || $this->request->post['message'] == '&lt;p&gt;&lt;br&gt;&lt;/p&gt;') {
				$json['error_warning'] = $this->language->get('error_message');
			}
		
			if(!$json){
				$filter_data = array(
					'customer_id'          => $this->request->get['customer_id'],
					'sender'               => 'user',
					'user_id'                 => $this->user->getId(),
					'message'              => $this->request->post['message'],
				);
				$this->model_message_message->sendMessage($filter_data);
				
				$json['success'] = $this->language->get('text_success');
			}
		}else{
			$json['error_warning'] = $this->language->get('error_no_customer');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	public function viewMessage() {
		$data['messages'] =array();
		$this->load->language('message/message');
		$this->load->model('customer/customer');
		$this->load->model('message/message');
		$data['text_administrator']  = $this->language->get('text_administrator');
		$data['text_no_message']  = $this->language->get('text_no_message');
		if($this->request->get['customer_id']){
			$customer_id = $this->request->get['customer_id'];
		}else{
			$customer_id = '';
		}
		
		$customer_info =  $this->model_customer_customer->getCustomer($customer_id);
		$data['customer_name'] = ($customer_info) ?  $customer_info['firstname'].' '.$customer_info['lastname'] : '';
		if($customer_info) {
			$filter_data =array(
				'customer_id'   => $customer_id,
				'filter_sender' => 'customer',
				'filter_status' => '0',
			);
			$messages =  $this->model_message_message->getMessagesByCustomerId($filter_data);
			
			$this->model_message_message->updateReadStatus($filter_data);
			
			$total_unread = $this->model_message_message->getTotalUnreadMessages($filter_data);
			if($total_unread) {
				$data['total_unread'] = $total_unread;
			}else{
				$data['total_unread'] = '';
			}
			
			foreach($messages as $message) {
				$data['messages'][] = array(
					'sender'          => $message['sender'],
					'message'         => html_entity_decode($message['message'], ENT_QUOTES, 'UTF-8'),
					'name'            => $customer_info['firstname'].' '.$customer_info['lastname'],
					'date_added'      => $this->time_ago($message['date_added']),
				);
			}
		}
		
		$this->response->setOutput($this->load->view('message/message_view.tpl', $data));
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