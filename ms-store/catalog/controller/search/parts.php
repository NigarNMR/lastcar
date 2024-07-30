<?php
class ControllerSearchParts extends Controller {    
    public function index() {
        $this->load->model('search/parts');
            
        if (isset($this->request->get['article'])) {
            $article = urldecode($this->request->get['article']);
        } else {
            $article = '';
        }
        
        $data['article'] = $article;
        $data['url_collect'] = $this->url->link('search/parts/collect');
        $data['url_collect_prices'] = $this->url->link('search/prices');
        $data['url_search_parts_manager'] = $this->url->link('search/parts_manager');
        
        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $data['breadcrumbs'] = array();


        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home'),
            'text' => $this->language->get('<i class="fa fa-home"></i>'),

        );


        $this->load->model('search/parts');

        $data['breadcrumbs'][] = array(
            'href' => NULL,
            'text' => $this->language->get('Поиск'),

        );

        $data['breadcrumbs'][] = array(
            'href' => NULL,
            'text' => $this->language->get($article),

        );


        
        $this->response->setOutput($this->load->view('search/parts', $data));
    }
    
    public function collect() {
        $this->load->model('search/parts');
        $this->load->model('search/parts_gearman');
        // Подключение класса логгера
        $this->load->library('logger');
        // Подключение Redis
        $this->load->library('rediscache');
            
        // Подключение файла для логгирования
        if (isset($this->request->get['article'])) {
            // Если артикул был передан
            $article = urldecode($this->request->get['article']);
            $this->logger->open(
                date('Ymd') 
                    . '_' 
                    . str_replace('/', '', $this->request->get['article']),
                'SN_',
                '.log');
        } else {
            // Если артикул не был передан
            $article = '';
            $this->logger->open( date('Ymd') . '_' . 'empty', 'SN_', '.log');
        }
        
        $search_key = md5($article);
        
        // Запись лога обращения к поиску
        $this->logger->notice('Запрос на поиск запчастей с серийным номером: ' . $article);
        
        // Сохранение старого значения артикула
        $article_original = $article;
        // Форматирование артикула
        $article = $this->model_search_parts->inputFormat($article);
        
        $this->logger->info('Отформатированный серийный номер: ' . $article);
        
        $cache_name = 'SEARCH_A_' . $article;
        
        $this->logger->write('Имя кэша: ' . $cache_name);
        
        if ($this->rediscache->existsHash('SEARCH', $cache_name . '_TIMER') && (intval($this->rediscache->getHash('SEARCH', $cache_name . '_TIMER'))) > time()) {
            $this->logger->info('Кэш с именем ' . $cache_name . ' был найден.');
            // Если существует актуальный кэш, то вытащить его
            $result = $this->rediscache->getHash('SEARCH', $cache_name);
        } else {
            $this->logger->warning('Кэш с именем ' . $cache_name . ' не был найден');
            // Если актуального кэша нет, то выполнить поиск
            $parts  = array();
            $raw_results = array();
            
            // Заполнение массива настроек и генерация задания по БД TecDoc
            $connection_config = array(
              'SERVER' => $this->config->get('db_td_hostname'),
              'LOGIN' => $this->config->get('db_td_username'),
              'PASS' => $this->config->get('db_td_password'),
              'NAME' => $this->config->get('db_td_database'),
              'PORT' => $this->config->get('db_td_port'));
            // Массив данных передаваемых worker'у
            $operational_data = json_encode(array('conType' => 'tecdoc', 'conOptions' => $connection_config, 'srchData' => $article));
            $this->logger->info('Поиск по серийному номеру в каталоге: ' . $operational_data);
            
            // Объединение массива сырых данных с данными полученными от worker'а
            $worker_data = $this->model_search_parts_gearman->searchPartsGearman($operational_data);
            $this->logger->write('Полученные данные: ' . urldecode($worker_data['raw']));
            if (!empty($worker_data['prepared'])) {
                $raw_results = array_merge($raw_results, $worker_data['prepared']);
            }

            // Заполнение массива настроек и генерация задания по БД Модуля
            $connection_config = array(
              'SERVER' => $this->config->get('db_tdm_hostname'),
              'LOGIN' => $this->config->get('db_tdm_username'),
              'PASS' => $this->config->get('db_tdm_password'),
              'NAME' => $this->config->get('db_tdm_database'),
              'PORT' => $this->config->get('db_tdm_port'));
            // Массив данных передаваемых worker'у
            $operational_data = json_encode(array('conType' => 'tdmprices', 'conOptions' => $connection_config, 'srchData' => $article));
            $this->logger->info('Поиск по серийному номеру в сохраненных прайсах: ' . $operational_data);
            // Объединение массива сырых данных с данными полученными от worker'а
            $worker_data = $this->model_search_parts_gearman->searchPartsGearman($operational_data);
            $this->logger->write('Полученные данные: ' . urldecode($worker_data['raw']));
            if (!empty($worker_data['prepared'])) {
                $raw_results = array_merge($raw_results, $worker_data['prepared']);
            }
            
            // Получение списка активированных веб-поставщиков
            $ws_list = $this->model_search_parts->getActiveWs();
            foreach ($ws_list as $ws) {
                // Массив данных передаваемых worker'у
                $operational_data = json_encode(array('conType' => 'ws', 'conOptions' => $ws, 'srchData' => $article));
                $this->logger->info('Поиск по серийному номеру у веб-поставщика ' . $ws['NAME'] . ': ' . $operational_data);
                
                // Объединение массива сырых данных с данными полученными от worker'а
                $worker_data = $this->model_search_parts_gearman->searchPartsGearman($operational_data);
                $this->logger->write('Полученные данные: ' . urldecode($worker_data['raw']));
                if (!empty($worker_data['prepared'])) {
                    $raw_results = array_merge($raw_results, $worker_data['prepared']);
                }
            }
            
            // Подготовка результирующих данных
            $part_fields = array('brand','article','description');
            $parts = array();
            
            $parts = $this->model_search_parts->partslistFormat($raw_results);
            // Сортировка списка по брендам (индекс - 0)
            $parts = $this->model_search_parts->dimentionalSort($parts,0);
            
            $result['PARTS'] = array();
            $result['PARTS']['fields'] = $part_fields;
            $result['PARTS']['data'] = $parts;
            $result = json_encode($result, JSON_UNESCAPED_UNICODE);
            
            // Занесение данных в кэш
            $this->rediscache->setHash('SEARCH', $cache_name, $result);
            $this->rediscache->setHash('SEARCH', $cache_name . '_TIMER', $this->model_search_parts->setPriceDate() + 24*60*60);
            $this->logger->info('Данные были занесены в кэш под именем: ' . $cache_name);
        }
        $this->logger->warning('Ключ активного поиска: ' . $search_key);
        $this->model_search_parts->updateSearchStatus($search_key, 2);
        
        $this->logger->write('Итоговый список деталей: ' . $result);
        
        $this->logger->notice("Завершение поиска\r\n");
        // Штатное сохранение и закрытие файла
        $this->logger->close();
        
        $this->response->setOutput($result);
    }
    
}