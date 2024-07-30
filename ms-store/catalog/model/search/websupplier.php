<?php
class ModelSearchWebsupplier extends Model {
    /**
     * Получение списка активных веб-поставщиков
     * 
     * @return array Список веб-поставщиков
     */
    private function getWebSuppliersData() {
        $query = $this->db_tdm->query('SELECT * FROM TDM_WS WHERE ACTIVE = \'' . 1 . '\'');
        if (!$query->num_rows) {
            return array();
        }
        
        return $query->rows;
    }
    
    private function getLastSearchTime($key, $ws_id, $analog_search = false) {
        if ($analog_search) {
            $query = $this->db_tdm->query("SELECT TIME FROM TDM_WS_ANALOG_TIME WHERE PKEY = \"" . $key . "\" AND WSID = \"" . $ws_id . "\";");
        } else {
            $query = $this->db_tdm->query("SELECT TIME FROM TDM_WS_TIME WHERE PKEY = \"" . $key . "\" AND WSID = \"" . $ws_id . "\";");
        }
        
        if (!$query->num_rows) {
            return 0;
        }
        
        return $query->row['TIME'];
    }
    
    public function searchPrices($parts_list) {
        
    }
}