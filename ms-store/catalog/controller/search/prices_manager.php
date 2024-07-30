<?php
class ControllerSearchPricesManager extends Controller {
    public function index() {
        $this->load->model('search/prices');
        // Подключение класса логгера
        $this->load->library('logger');
        
        if (isset($this->request->get['article'])) {
            $article = urldecode($this->request->get['article']);
        } else {
            $article = '';
        }
        
        if (isset($this->request->get['brand'])) {
            $brand = urldecode($this->request->get['brand']);
        } else {
            $brand = '';
        }
        
        $this->logger->open(
            date('Ymd') 
                . '_' 
                . str_replace('/', '', $article)
                . '_'
                . str_replace('/', '', $brand),
            'SNBM_',
            '.log');

        $search_key = md5(md5($brand) . md5($article));

        $search_status = $this->model_search_prices->checkSearchStatus($search_key);
        $this->logger->notice('Запрос поискового менеджера по серийному номеру - ' . $article . ' и марке - ' . $brand);
        $this->logger->info('Хэш ключ для фонового поиска: ' . $search_key);
        $this->logger->info('Статус поиска для ключа ' . $search_key . ': ' . $search_status);
        if ($search_status === 0) {
            $this->model_search_prices->updateSearchStatus($search_key, 1);
            $search_url =  $this->url->link('search/prices/collect');
            $exec_path = 'nohup ' . PHP_INTERPRETER . ' -f ' . realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR
                . 'background_prices_component.php ' . urlencode($article) . ' ' . urlencode($brand) . ' '
                . urlencode($this->request->server['SERVER_NAME'])
                . ' > /dev/null 2>&1 &';
            $this->logger->info('Команда запуска фонового поиска: "' . $exec_path . '"');
            exec($exec_path);
        }
        $this->logger->close();
        $this->response->setOutput(json_encode(['status' => $search_status]));
    }
}