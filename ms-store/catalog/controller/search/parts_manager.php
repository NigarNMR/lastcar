<?php
class ControllerSearchPartsManager extends Controller {
    public function index() {
        $this->load->model('search/parts');
        // Подключение класса логгера
        $this->load->library('logger');
        
        if (isset($this->request->get['article'])) {
            $article = urldecode($this->request->get['article']);
        } else {
            $article = '';
        }
        
        $this->logger->open(
            date('Ymd') 
                . '_' 
                . str_replace('/', '', $article),
            'SNM_',
            '.log');

        $search_key = md5($article);

        $search_status = $this->model_search_parts->checkSearchStatus($search_key);
        $this->logger->notice('Запрос поискового менеджера по серийному номеру - ' . $article);
        $this->logger->info('Хэш ключ для фонового поиска: ' . $search_key);
        $this->logger->info('Статус поиска для ключа ' . $search_key . ': ' . $search_status);
        if ($search_status === 0) {
            $this->model_search_parts->updateSearchStatus($search_key, 1);
            $search_url =  $this->url->link('search/prices/collect');
            $exec_path = 'nohup ' . PHP_INTERPRETER . ' -f ' . realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'background_parts_component.php ' . urlencode($article) . ' ' . urlencode($this->request->server['SERVER_NAME']) . ' > /dev/null 2>&1 &';
            $this->logger->info('Команда запуска фонового поиска: "' . $exec_path . '"');
            exec($exec_path);
        }
        $this->logger->close();
        $this->response->setOutput(json_encode(['status' => $search_status]));
    }
}