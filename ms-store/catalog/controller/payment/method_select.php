<?php
class ControllerPaymentMethodSelect extends Controller {
    public function index() {
        //Верификация пользователя
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('payment/method_select', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
        
        //Подгрузка текстов на активном языке
        $this->load->language('payment/method_select');
        
        //Установка значения оглавления (title)
		$this->document->setTitle($this->language->get('heading_title'));
        
        //Установка текста и ссылок для хлебных крошек
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_balance_refill'),
			'href' => $this->url->link('payment/method_select', '', true)
		);
        
        //Подключение модели
		$this->load->model('payment/method_select');
        
        //Заголовок
		$data['heading_title'] = $this->language->get('heading_title');
        
        $data['method_select'] = $this->language->get('text_method_select');
        $data['method_cash'] = $this->language->get('text_method_cash');
        $data['method_cash_info'] = $this->language->get('text_method_cash_info');
        $data['method_online'] = $this->language->get('text_method_online');
        $data['method_online_info'] = $this->language->get('text_method_online_info');
        $data['agreement'] = $this->language->get('text_agreement');
        $data['agreement_doc'] = $this->language->get('text_agreement_document');
        $data['button_payment_cash'] = $this->language->get('button_payment_cash');
        $data['button_payment_online'] = $this->language->get('button_payment_online');
        
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
        
        $data['url_cash'] = $this->url->link('payment/method_cash', '', true);
        $data['url_online'] = $this->url->link('payment/method_online', '', true);
        
        //Генерация вывода по шаблону
		$this->response->setOutput($this->load->view('payment/method_select', $data));
    }
}
