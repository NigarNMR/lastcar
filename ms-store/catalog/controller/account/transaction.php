<?php
class ControllerAccountTransaction extends Controller {
	public function index() {
        //Верификация пользователя
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/transaction', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
        //Подгрузка текстов на активном языке
		$this->load->language('account/transaction');

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
			'text' => $this->language->get('text_transaction'),
			'href' => $this->url->link('account/transaction', '', true)
		);

        //Подключение модели
		$this->load->model('account/transaction');

        //Заголовок
		$data['heading_title'] = $this->language->get('heading_title');
        
        //Заголовки колонок в таблице
		$data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_operation'] = $this->language->get('column_operation');
        $data['column_product'] = $this->language->get('column_product');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');
        $data['button_export'] = $this->language->get('button_export');

        //Проверка передаваемого параметра page - текущая страница
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
        
        //Проверка передаваемого параметра page-limit - ограничение постраничного вывода
        if (isset($this->request->get['page-limit'])) {
			$page_limit = $this->request->get['page-limit'];
		} else {
			$page_limit = 25;
		}
        
        //Проверка передаваемого параметра date-start - начало в фильтре по дате
        if (isset($this->request->get['date-start'])) {
			$date_start = $this->request->get['date-start'];
		} else {
			$date_start = false;
		}

        //Проверка передаваемого параметра date-end - конец в фильтре по дате
        if (isset($this->request->get['date-end'])) {
			$date_end = $this->request->get['date-end'];
		} else {
			$date_end = false;
		}
        
        //Проверка передаваемого параметра tab - текущая вкладка
        if (isset($this->request->get['tab'])) {
			$tab_name = $this->request->get['tab'];
		} else {
			$tab_name = 'current';
		}
        
        $data['tabs'] = array();
        //Установка параметров отображения для таб-панелей и выбора необходимых строк из БД
        switch ($tab_name)
        {
            case 'current':
                $data['tabs']['balance_current_class'] = 'class="active"';
                $data['tabs']['balance_reserve_class'] = '';
                $tab_operations = [1];
                break;
            case 'reserve':
                $data['tabs']['balance_current_class'] = '';
                $data['tabs']['balance_reserve_class'] = 'class="active"';
                $tab_operations = [2];
                break;
            default:
                $data['tabs']['balance_current_class'] = 'class="active"';
                $data['tabs']['balance_reserve_class'] = '';
                break;
        }
        
        //Установка ссылок для таб-панелей
        $data['tabs']['balance_current_url'] = $this->url->link('account/transaction', 'page-limit=' . $page_limit . '&tab=current&date-start=' . $date_start . '&date-end=' . $date_end, true);
        $data['tabs']['balance_reserve_url'] = $this->url->link('account/transaction', 'page-limit=' . $page_limit . '&tab=reserve&date-start=' . $date_start . '&date-end=' . $date_end, true);
        
        //Настройки выпадающего списка постраничного ограничения
        $data['page_limit_url'][1] = $this->url->link('account/transaction', 'page-limit=25&tab=' . $tab_name . '&date-start=' . $date_start . '&date-end=' . $date_end, true);
        $data['page_limit_url'][2] = $this->url->link('account/transaction', 'page-limit=50&tab=' . $tab_name . '&date-start=' . $date_start . '&date-end=' . $date_end, true);
        $data['page_limit_url'][3] = $this->url->link('account/transaction', 'page-limit=100&tab=' . $tab_name . '&date-start=' . $date_start . '&date-end=' . $date_end, true);
        $data['page_limit_url'][4] = $this->url->link('account/transaction', 'page-limit=250&tab=' . $tab_name . '&date-start=' . $date_start . '&date-end=' . $date_end, true);
        $data['page_limit_url'][5] = $this->url->link('account/transaction', 'page-limit=500&tab=' . $tab_name . '&date-start=' . $date_start . '&date-end=' . $date_end, true);
        
        $data['page_limit_text'][1] = '25';
        $data['page_limit_text'][2] = '50';
        $data['page_limit_text'][3] = '100';
        $data['page_limit_text'][4] = '250';
        $data['page_limit_text'][5] = '500';
        
        //Текущее ограничение вывода
        $data['page_limit_value'] = $page_limit;
        //Описание меню
        $data['page_limit_description']  = $this->language->get('text_page_limit');
        
        //Настройки фильтра по дате
        $data['date_filter_description'] = $this->language->get('text_date_filter_description');
        $data['date_filter_placeholder'] = $this->language->get('text_date_filter_placeholder');
        if ($date_start && $date_end) {
            $data['date_filter_value'] = $date_start . ' - ' . $date_end;
        }
        $data['date_filter_start'] = $date_start;
        $data['date_filter_end'] = $date_end;
        
        //Активная таб-панель
        $data['tab_active'] = $tab_name;
        
		$data['transactions'] = array();

		$filter_data = array(
			'sort'           => 'date_added',
			'order'          => 'DESC',
			'start'          => ($page - 1) * $page_limit,
			'limit'          => $page_limit,
            'tab_operations' => $tab_operations,
            'date_start'     => $date_start,
            'date_end'       => $date_end
		);

        //Общее число транзакций по текущему фильтру
		$transaction_total = $this->model_account_transaction->getTotalTransactions($filter_data);

        //Массив транзакций по текущему фильтру (страницы)
		$results = $this->model_account_transaction->getTransactions($filter_data);

		foreach ($results as $result) {
            //Указание на тип операции
            if (intval($result['amount']) > 0) {
                $operation_description = $this->language->get('text_operation_increase_' . intval($result['operation_status']));
            } else {
                $operation_description = $this->language->get('text_operation_decrease_' . intval($result['operation_status']));
            }
            //Указание данных по товару, если есть
            if (empty($result['order_product_id']) || is_null($result['order_product_id'])) {
                $product_description = 'N/A';
            } else {
                $product_description = $this->model_account_transaction->getOrderProductName($result['order_product_id']);
            }
            //Занесение в массив данных по транзакциям
			$data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'operation'   => $operation_description,
                'product'     => $product_description,
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

        //Указание настроек генерации пагинатора
		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
        $pagination->num_links = 7;
		$pagination->url = $this->url->link('account/transaction', 'page={page}&page-limit=' . $page_limit . '&tab=' . $tab_name . '&date-start=' . $date_start . '&date-end=' . $date_end, true);

        //Генерация пагинатора
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * $page_limit) + 1 : 0, ((($page - 1) * $page_limit) > ($transaction_total - $page_limit)) ? $transaction_total : ((($page - 1) * $page_limit) + $page_limit), $transaction_total, ceil($transaction_total / $page_limit));

		$data['continue'] = $this->url->link('account/account', '', true);
        
        //Установка данных по балансу
        $data['data_balance_current'] = $this->currency->format($this->customer->getBalance(['tab_operations' => [1]]), $this->session->data['currency']);
        $data['data_balance_reserve'] = $this->currency->format($this->customer->getBalance(['tab_operations' => [2]]), $this->session->data['currency']);
        $data['data_balance_limit'] = $this->currency->format($this->customer->getLimit(), $this->session->data['currency']);
        $balance_available = $this->customer->getBalance(['tab_operations' => [1]]) - $this->customer->getBalance(['tab_operations' => [2]]);
        if ($balance_available > 0) {
            $data['data_balance_available'] = $this->currency->format($balance_available, $this->session->data['currency']);
        } else {
            $data['data_balance_available'] = $this->currency->format(0, $this->session->data['currency']);
        }
        
        $data['data_balance_limit_available'] = $this->currency->format($this->customer->getLimit() + $balance_available, $this->session->data['currency']);;
        
        // Ссылка на страницу пополнения баланса
        $data['payment_methods_link'] = $this->url->link('payment/method_select', '', true);
        
        //Установка текстов по балансу
        $data['text_balance_current'] = $this->language->get('text_balance_current');
        $data['text_balance_reserve'] = $this->language->get('text_balance_reserve');
        $data['text_balance_limit'] = $this->language->get('text_balance_limit');
        $data['text_balance_available'] = $this->language->get('text_balance_available');
        $data['text_balance_limit_available'] = $this->language->get('text_balance_limit_available');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

        //Генерация вывода по шаблону
		$this->response->setOutput($this->load->view('account/transaction', $data));
	}
    
    public function export() {        
        //Верификация пользователя
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/transaction', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
        //Подгрузка текстов на активном языке
		$this->load->language('account/transaction');
        
        //Подключение библиотеки экспорта
        $this->load->library('xlsxexport');
        
        //Подключение модели
		$this->load->model('account/transaction');
        
        //Проверка передаваемого параметра date-end - конец в фильтре по дате
        if (isset($this->request->get['date-start'])) {
			$date_start = $this->request->get['date-start'];
		} else {
			$date_start = false;
		}
        
        //Проверка передаваемого параметра tab - текущая вкладка
        if (isset($this->request->get['date-end'])) {
			$date_end = $this->request->get['date-end'];
		} else {
			$date_end = false;
		}
        
        // Расчет хэша к имени файла для избежания коллизий
        $name_hash = md5($this->customer->getId() . time() . $date_start . $date_end);
        
        // Установка заголовков на соотв языке
        $headers = array(
            $this->language->get('column_date_added') => 'date',
            $this->language->get('column_operation') => 'string',
            $this->language->get('column_product') => 'string',
            $this->language->get('column_description') => 'string',
            sprintf($this->language->get('column_amount'), $this->config->get('config_currency')) => 'string'
        );
        
        // Запись в первую страницу
        $filter_data = array(
			'sort'           => 'date_added',
			'order'          => 'DESC',
            'date_start'     => $date_start,
            'date_end'       => $date_end,
            'tab_operations' => array(1)
		);
        
        // Получение данных по фильтру
        $results = $this->model_account_transaction->getTransactions($filter_data);
        
        // Добавление заголовков
        $this->xlsxexport->writeHeader($this->language->get('text_balance_current'),$headers);
        
        foreach ($results as $result) {
            $export_row = array();
            
            // Форматирование полученных данных
            $export_row[] =$result['date_added'];
            if (intval($result['amount']) > 0) {
                $export_row[] = $this->language->get('text_operation_increase_' . intval($result['operation_status']));
            } else {
                $export_row[] = $this->language->get('text_operation_decrease_' . intval($result['operation_status']));
            }
            if (empty($result['order_product_id']) || is_null($result['order_product_id'])) {
                $export_row[] = 'N/A';
            } else {
                $export_row[] = $result['order_product_id'];
            }
            $export_row[] = $result['description'];
            $export_row[] = $this->currency->format($result['amount'], $this->config->get('config_currency'));
            
            // Построчное добавление данных
            $this->xlsxexport->writeRowData(
                $this->language->get('text_balance_current'),
                $export_row
            );
        }
        
        // Запись во вторую страницу
        $filter_data = array(
			'sort'           => 'date_added',
			'order'          => 'DESC',
            'date_start'     => $date_start,
            'date_end'       => $date_end,
            'tab_operations' => array(2)
		);
        
        // Получение данных по фильтру
        $results = $this->model_account_transaction->getTransactions($filter_data);
        
        // Добавление заголовков
        $this->xlsxexport->writeHeader($this->language->get('text_balance_reserve'),$headers);
        
        foreach ($results as $result) {
            $export_row = array();
            
            // Форматирование полученных данных
            $export_row[] = $result['date_added'];
            if (intval($result['amount']) > 0) {
                $export_row[] = $this->language->get('text_operation_increase_' . intval($result['operation_status']));
            } else {
                $export_row[] = $this->language->get('text_operation_decrease_' . intval($result['operation_status']));
            }
            if (empty($result['order_product_id']) || is_null($result['order_product_id'])) {
                $export_row[] = 'N/A';
            } else {
                $export_row[] = $result['order_product_id'];
            }
            $export_row[] = $result['description'];
            $export_row[] = $this->currency->format($result['amount'], $this->config->get('config_currency'));
            
            // Построчное добавление данных
            $this->xlsxexport->writeRowData(
                $this->language->get('text_balance_reserve'),
                $export_row
            );
        }
        
        // Запись данных в файл
        $this->xlsxexport->exportFile("transactions_" . $name_hash . ".xlsx");
        
        // Установка заголовков страницы
        $this->response->addheader('Pragma: public');
        $this->response->addheader('Expires: 0');
        $this->response->addheader('Content-Description: File Transfer');
        $this->response->addheader('Content-Type: application/octet-stream');
        $this->response->addHeader('Content-Length: ' . $this->xlsxexport->sizeExportFile('transactions_' . $name_hash . '.xlsx') );
        $this->response->addHeader('Content-Disposition: attachment; filename=transactions.xlsx');
        $this->response->addheader('Content-Transfer-Encoding: binary');
        // Вывод данных файла
        $this->response->setOutput($this->xlsxexport->readExportFile("transactions_" . $name_hash . ".xlsx"));
        // Удаление экспортного файла
        $this->xlsxexport->deleteExportFile("transactions_" . $name_hash . ".xlsx");
    }
}