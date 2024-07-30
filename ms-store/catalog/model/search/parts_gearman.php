<?php
class ModelSearchPartsGearman extends Model {
    private $data_buffer = array();
    
    public function searchPartsGearman($operational_data) {
        // Подключение Gearman
        $this->load->library('gearmancli');
        $this->gearmancli->addServer();
        
        $this->data_buffer = array();
        
        $this->gearmancli->setCallbackData(array($this, 'callbackSearchData'));
        
        $task_list[] = $this->gearmancli->addTaskHigh('search_article_only', $operational_data, JSON_UNESCAPED_UNICODE);
        
        $this->gearmancli->runTasks();
        
        return $this->data_buffer;
    }
    
    public function callbackSearchData($task) {
        $this->load->model('search/parts');
        
        $this->data_buffer['raw'] = $task->data();
        
        $arResData = json_decode($task->data(), true);
        $arParts = (array)$arResData['PARTS'];

        foreach ($arParts as $arPart)
        {
            $type = urldecode($arPart['BKEY_TYPE']);
            $BKEY = urldecode($arPart['BKEY']);

            if ($type === "NAME") {
                $brandId = $this->model_search_parts->brandReplace($BKEY);
                $BKEY = $this->model_search_parts->getNameByBrandId($brandId);
                $BKEY_FORMATTED = $this->model_search_parts->inputFormat($BKEY);
            } elseif ($type === "ID") {
                $brandId = $BKEY;
                $BKEY = $this->model_search_parts->getNameByBrandId($brandId);
                $BKEY_FORMATTED=$this->model_search_parts->inputFormat($BKEY);
            }

            $AKEY = urldecode($arPart['AKEY']);
            $AKEY = $this->model_search_parts->inputFormat($AKEY);
            
            $NAME = urldecode($arPart['NAME']);
            
            $this->data_buffer['prepared'][$BKEY_FORMATTED . $AKEY] = array(
                'BKEY' => $BKEY,
                'AKEY' => $AKEY,
                'NAME' => $NAME,
                'ID'   => $brandId);
        }
        
    }
}