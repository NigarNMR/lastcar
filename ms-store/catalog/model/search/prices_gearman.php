<?php
class ModelSearchPricesGearman extends Model {
    private $data_buffer = array();
    private $task_list = array();
    
    public function searchAnalogsGearman($operational_data_list) {
        // Подключение Gearman
        $this->load->library('gearmancli');
        $this->gearmancli->addServer();
                
        $this->gearmancli->setCallbackData(array($this, 'callbackAnalogsData'));
        foreach ($operational_data_list as $operational_data) {
            $this->task_list[] = $this->gearmancli->addTaskHigh('get_analogs_list', $operational_data, JSON_UNESCAPED_UNICODE);
        }
        
        $this->gearmancli->runTasks();
        return $this->data_buffer;
    }
    
    public function clearBuffer() {
        $this->data_buffer = array();
    }
    
    public function callbackAnalogsData($task) {
        $this->load->model('search/parts');
        $result_data = json_decode($task->data(), true);
        
        $result_type = (string)$result_data['TYPE'];
        
        switch ($result_type) {
            //Обработка результатов запроса к TD и TDM_LINKS на поиск аналогов
            case 'ANALOGS': {
                $data = (array)$result_data['DATA'];
                $parts = $data['PARTS'];
                $part_ids = $data['PAIDS'];
                if (!empty($parts)) {
                    foreach ($parts as $part) {
                        if (empty($part['PKEY'])) {
                            $part['BKEY'] = $this->model_search_parts->brandReplace($part['BRAND']);
                            $part['AKEY'] = $this->model_search_parts->inputFormat($part['ARTICLE']);
                            $part['PKEY'] = $part['BKEY'] . '_' . $part['AKEY'];
                        }
                        $this->data_buffer['parts'][$part['PKEY']] = $part;
                    }
                }
          
                if (!empty($part_ids)) {
                    $part_unique_ids = array_unique($part_ids);
                    foreach ($part_unique_ids as $part_id) {
                        $this->data_buffer['part_ids'][] = $part_id;
                    }
                }
            }
                break;
            default:
                break;
        }
    }
    
    public function searchOrigPricesGearman($task_list) {
        // Подключение Gearman
        $this->load->library('gearmancli');
        $this->gearmancli->addServer();
        
        $this->gearmancli->setCallbackData(array($this, 'callbackPricesData'));
        
        $this->task_list = [];
        foreach ($task_list as $task_data) {
            $this->task_list[] = $this->gearmancli->addTaskHigh('get_price_orig', json_encode($task_data), JSON_UNESCAPED_UNICODE);
        }
        
        $this->gearmancli->runTasks();
        return $this->data_buffer;
    }
    
    public function callbackPricesData($task) {
        $this->data_buffer[] = urldecode($task->data());
    }
    
    public function searchPricesGearman($task_list) {
        // Подключение Gearman
        $this->load->library('gearmancli');
        $this->gearmancli->addServer();
        
        $this->gearmancli->setCallbackData(array($this, 'callbackPricesData'));
        
        $this->task_list = [];
        foreach ($task_list as $task_data) {
            $this->task_list[] = $this->gearmancli->addTaskHigh('get_price', json_encode($task_data), JSON_UNESCAPED_UNICODE);
        }
        
        $this->gearmancli->runTasks();
        return $this->data_buffer;
    }
}