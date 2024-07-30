<?php
class ControllerPaymentMethodOnline extends Controller {
    public function index() {
        //Верификация пользователя
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('payment/method_online', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
        
        //Подгрузка текстов на активном языке
        $this->load->language('payment/method_online');
        
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
        
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_refill_online'),
			'href' => $this->url->link('payment/method_online', '', true)
		);
        
        //Подключение модели
		$this->load->model('payment/method_online');
        
        $data['org_name'] = $this->config->get('config_pinfo_org_name');
        $data['taxpayer_id'] = $this->config->get('config_pinfo_taxpayer_id');
        $data['paccount_id'] = $this->config->get('config_pinfo_paccount_id');
        $data['bank_id'] = $this->config->get('config_pinfo_bank_id');
        $data['bank_name'] = $this->config->get('config_pinfo_bank_name');
        
        //Заголовок
		$data['heading_title'] = $this->language->get('heading_title');
        
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
        
        //Генерация вывода по шаблону
		$this->response->setOutput($this->load->view('payment/method_online', $data));
    }
}
