<?php
class ControllerPaymentMethodCash extends Controller {
    public function index() {
        //Верификация пользователя
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('payment/method_cash', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
        
        //Подгрузка текстов на активном языке
        $this->load->language('payment/method_cash');
        
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
			'text' => $this->language->get('text_refill_cash'),
			'href' => $this->url->link('payment/method_cash', '', true)
		);
        
        //Подключение модели
		$this->load->model('payment/method_cash');
        
        $data['timetable_day_names'][1] = 'Понедельник';
        $data['timetable_day_names'][2] = 'Вторник';
        $data['timetable_day_names'][3] = 'Среда';
        $data['timetable_day_names'][4] = 'Четверг';
        $data['timetable_day_names'][5] = 'Пятница';
        $data['timetable_day_names'][6] = 'Суббота';
        $data['timetable_day_names'][7] = 'Воскресенье';
        
        $data['contact_number'] = $this->config->get('config_pinfo_contact_number');
        $data['contact_number'] = explode("\r\n", $data['contact_number']);
        $data['contact_email'] = $this->config->get('config_pinfo_contact_email');
        $data['contact_address'] = $this->config->get('config_pinfo_contact_address');
        $data['timetable_days'] = $this->config->get('config_pinfo_timetable_days');
        if (is_array($data['timetable_days'])) {
            foreach ($data['timetable_days'] as $key => $timetable_day) {
                $data['timetable_days'][$key] = $data['timetable_day_names'][intval($timetable_day)];
            }
        }
        $data['timetable_weekdays'] = $this->config->get('config_pinfo_timetable_weekdays');
        $data['timetable_weekends'] = $this->config->get('config_pinfo_timetable_weekends');
        
        //Заголовок
		$data['heading_title'] = $this->language->get('heading_title');
        
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
        
        //Генерация вывода по шаблону
		$this->response->setOutput($this->load->view('payment/method_cash', $data));
    }
}
