<?php
class ModelSearchPrices extends Model {
    private $price_buffer = array();

    public function getBrandNameById($brand) {
        $query = $this->db_tdm->query('
                    SELECT *
                    FROM TDM_BRANDS
                    WHERE b__id = "$brand" 
         ');
        if ($query->num_rows) {
            return $query->rows[0];
        } else {
            return [];
        }
        
    }
    
    public function getPricedPartsList($parts_list) {
        $this->load->model('search/parts');
        $search_date = $this->model_search_parts->setPriceDate();
        $sql_conditions = array();
        if (empty($parts_list)) {
            return [];
        } else {
            foreach ((array)$parts_list as $part) {
                $sql_conditions[] = "(BRAND_ID = '{$part['BKEY']}' AND AKEY = '{$part['AKEY']}' AND TIME >= '{$search_date}')";
            }
            $query = $this->db_tdm->query('
                    SELECT *
                    FROM TDM_WS_ANALOG_TIME2
                    WHERE ' . implode(' OR ', $sql_conditions)
                    );
            if ($query->num_rows) {
                return $query->rows;
            } else {
                return [];
            }
        }
    }
    
    public function getPricedOrigPartsList($parts_list) {
        $this->load->model('search/parts');
        $search_date = $this->model_search_parts->setPriceDate();
        $sql_conditions = array();
        if (empty($parts_list)) {
            return [];
        } else {
            foreach ((array)$parts_list as $part) {
                $sql_conditions[] = "(BRAND_ID = '{$part['BKEY']}' AND AKEY = '{$part['AKEY']}' AND TIME >= '{$search_date}')";
            }
            $query = $this->db_tdm->query('
                    SELECT *
                    FROM TDM_WS_TIME2
                    WHERE ' . implode(' OR ', $sql_conditions)
                    );
            if ($query->num_rows) {
                return $query->rows;
            } else {
                return [];
            }
        }
    }
    
    /**
     * Формирование наборов деталей по web-сервисам и фильтрация полученных ранее
     * 
     * @param array $parts_data Набор деталей
     * @return array Набор деталей по каждому активному web-сервису
     */
    public function getWsParts($parts_data) {
        if (empty($parts_data)) {
            $parts_data = array();
        }
        
        $this->load->model('search/parts');
        // Получение набора активных web-сервисов
        $ws_list = $this->model_search_parts->getActiveWs();
        
        // Формирование набора деталей для каждого web-сервиса
        $ws_ops_data = [];
        foreach ($ws_list as $ws) {
            $ws_ops_data[(int)$ws['ID']] = ['data' => $ws, 'parts' => $parts_data];
        }
        
        // Получение списка уже процененных деталей для фильтрации
        $parts_priced = $this->getPricedPartsList($parts_data);
        
        // Фильтрация уже найденных элементов
        foreach ($parts_priced as $part) {
            if (isset($ws_ops_data[(int)$part['WSID']]) && is_array($ws_ops_data[(int)$part['WSID']])) {
                foreach ($ws_ops_data[(int)$part['WSID']]['parts'] as $key => $ws_part) {
                    if ((string) $ws_part['BRAND'] === (string) $part['BRAND_ID'] && $ws_part['AKEY'] === $part['AKEY']) {
                        unset($ws_ops_data[(int)$part['WSID']]['parts'][$key]);
                        break;
                    }
                }
            }
        }
        
        // Получение поисковых брендов по каждой детале web-сервисов
        foreach ($ws_ops_data as $ws_key => $ws) {
            foreach ($ws['parts'] as $part_key => $part_data) {
                $lookup_data = $this->getLookupBrand($part_data['BKEY'], $ws['data']['NAME']);
                
                switch (count($lookup_data)) {
                    case 0:
                        unset($ws_ops_data[$ws_key]['parts'][$part_key]);
                        break;
                    case 1:
                        $ws_ops_data[$ws_key]['parts'][$part_key]['BrandTmp'] = $ws_ops_data[$ws_key]['parts'][$part_key]['BRAND'];
                        $ws_ops_data[$ws_key]['parts'][$part_key]['BKeyTmp'] = $ws_ops_data[$ws_key]['parts'][$part_key]['BKEY'];
                        $ws_ops_data[$ws_key]['parts'][$part_key]['BRAND'] = $lookup_data[0]['REPL_BRAND'];
                        $ws_ops_data[$ws_key]['parts'][$part_key]['BKEY'] = $this->model_search_parts->inputFormat($lookup_data[0]['REPL_BRAND']);
                        break;
                    default:
                        foreach ($lookup_data as $repl_key => $repl_data) {
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key] = $ws_ops_data[$ws_key]['parts'][$part_key];
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key]['BrandTmp'] = $ws_ops_data[$ws_key]['parts'][$part_key]['BRAND'];
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key]['BKeyTmp'] = $ws_ops_data[$ws_key]['parts'][$part_key]['BKEY'];
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key]['BRAND'] = $repl_data['REPL_BRAND'];
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key]['BKEY'] = $this->model_search_parts->inputFormat($repl_data['REPL_BRAND']);
                        }
                        unset($ws_ops_data[$ws_key]['parts'][$part_key]);
                        break;
                }
            }
        }
        
        return $ws_ops_data;
    }
    
    public function getWsOriginalParts($part_data) {
        if (empty($part_data)) {
            $part_data = array();
        }
        $parts_data = [];
        $parts_data[] = $part_data;
        
        $this->load->model('search/parts');
        // Получение набора активных web-сервисов
        $ws_list = $this->model_search_parts->getActiveWs();
        
        // Формирование набора деталей для каждого web-сервиса
        $ws_ops_data = [];
        foreach ($ws_list as $ws) {
            $ws_ops_data[(int)$ws['ID']] = ['data' => $ws, 'parts' => $parts_data];
        }
        
        // Получение списка уже процененных деталей для фильтрации
        $parts_priced = $this->getPricedOrigPartsList($parts_data);
        
        // Фильтрация уже найденных элементов
        foreach ($parts_priced as $part) {
            if (isset($ws_ops_data[(int)$part['WSID']]) && is_array($ws_ops_data[(int)$part['WSID']])) {
                foreach ($ws_ops_data[(int)$part['WSID']]['parts'] as $key => $ws_part) {
                    if ($ws_part['BKEY'] === $part['BRAND_ID'] && $ws_part['AKEY'] === $part['AKEY']) {
                        unset($ws_ops_data[(int)$part['WSID']]['parts'][$key]);
                        break;
                    }
                }
            }
        }
        
        // Получение поисковых брендов по каждой детале web-сервисов
        foreach ($ws_ops_data as $ws_key => $ws) {
            foreach ($ws['parts'] as $part_key => $part_data) {
                $lookup_data = $this->getLookupBrand($part_data['BKEY'], $ws['data']['NAME']);
                
                switch (count($lookup_data)) {
                    case 0:
                        unset($ws_ops_data[$ws_key]['parts'][$part_key]);
                        break;
                    case 1:
                        $ws_ops_data[$ws_key]['parts'][$part_key]['BrandTmp'] = $ws_ops_data[$ws_key]['parts'][$part_key]['BRAND'];
                        $ws_ops_data[$ws_key]['parts'][$part_key]['BKeyTmp'] = $ws_ops_data[$ws_key]['parts'][$part_key]['BKEY'];
                        $ws_ops_data[$ws_key]['parts'][$part_key]['BRAND'] = $lookup_data[0]['REPL_BRAND'];
                        $ws_ops_data[$ws_key]['parts'][$part_key]['BKEY'] = $this->model_search_parts->inputFormat($lookup_data[0]['REPL_BRAND']);
                        break;
                    default:
                        foreach ($lookup_data as $repl_key => $repl_data) {
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key] = $ws_ops_data[$ws_key]['parts'][$part_key];
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key]['BrandTmp'] = $ws_ops_data[$ws_key]['parts'][$part_key]['BRAND'];
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key]['BKeyTmp'] = $ws_ops_data[$ws_key]['parts'][$part_key]['BKEY'];
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key]['BRAND'] = $repl_data['REPL_BRAND'];
                            $ws_ops_data[$ws_key]['parts'][$part_key . '_' . $repl_key]['BKEY'] = $this->model_search_parts->inputFormat($repl_data['REPL_BRAND']);
                        }
                        unset($ws_ops_data[$ws_key]['parts'][$part_key]);
                        break;
                }
            }
        }
        
        return $ws_ops_data;
    }
    
    /**
     * Получение набора поисковых брендов по переданному наименованию для
     * указанного web-сервиса
     * 
     * @param string $brand_name Наименование бренда
     * @param string $ws_name Наименование web-сервиса
     * @return array Набор поисковых брендов
     */
    public function getLookupBrand($brand_id, $ws_name) {
        $query = $this->db_tdm->query('SELECT REPL_BRAND FROM TDM_DICTIONARIES WHERE BRAND_ID IN (SELECT b__id FROM TDM_GROUP_BRAND WHERE gob__id IN (SELECT gob__id FROM TDM_GROUP_BRAND WHERE b__id=\'' . $this->db_tdm->escape($brand_id) . '\')) AND DICT_CODE=\'' . $this->db_tdm->escape(strtoupper($ws_name)) . '\';');
        
        if ($query->num_rows) {
            return $query->rows;
        } else {
            return [];
        }
    }
    
    public function getPrices($ws_ops_data) {
        $this->load->model('search/prices_gearman');
        $this->price_buffer = [];
        
        $task_groups = [];
        foreach ($ws_ops_data as $ws) {
            $query_limit = $ws['data']['Q_LIMIT'];
            $counter = 0;
            $group_counter = 0;
            foreach ($ws['parts'] as $part) {
                if ($counter >= $query_limit) {
                    $counter = 0;
                    $group_counter++;
                }
                $task_groups[$group_counter][] = ['WS' => $ws['data'], 'PARTS' => $part, 'PARAMS' => []];
                $counter++;
            }
        }
        
        $result_data = [];
        foreach ($task_groups as $task_group) {
            $result_data = array_merge($result_data, $this->model_search_prices_gearman->searchOrigPricesGearman($task_group));
        }
        
        return $result_data;
    }
    
    public function getOriginalPrices($ws_ops_data) {
        $this->load->model('search/prices_gearman');
        $this->price_buffer = [];
        
        $task_groups = [];
        foreach ($ws_ops_data as $ws) {
            $query_limit = $ws['data']['Q_LIMIT'];
            $counter = 0;
            $group_counter = 0;
            foreach ($ws['parts'] as $part) {
                if ($counter >= $query_limit) {
                    $counter = 0;
                    $group_counter++;
                }
                $task_groups[$group_counter][] = ['WS' => $ws['data'], 'PARTS' => $part, 'PARAMS' => []];
                $counter++;
            }
        }
        
        $result_data = [];
        foreach ($task_groups as $task_group) {
            $result_data = array_merge($result_data, $this->model_search_prices_gearman->searchPricesGearman($task_group));
        }
        
        return $result_data;
    }
    
    public function nullifyPrices($ws_ops_data) {
        $query_statement = "";
        $all_queries = "";
        $query_pieces = [];
        foreach ($ws_ops_data as $ws) {
            $ws_code = $ws['data']['PRICE_CODE'];
            foreach ($ws['parts'] as $part) {
                $query_pieces[] = ""
                    . "(BRAND_ID='" . $this->db_tdm->escape($part['BKeyTmp']) . "'"
                        . " AND"
                    . " AKEY='" . $this->db_tdm->escape($part['AKEY']) . "'"
                        . " AND"
                    . " CODE='" . $this->db_tdm->escape($ws_code) . "')";
            }
        }
        $current_time = time();
        $query_statement = " UPDATE"
                                . " TDM_PRICES"
                            . " SET" 
                                . " AVAILABLE='0',"
                                . " DAY='0',"
                                . " PRICE='0',"
                                . " PRICE_ORIG='0',"
                                . " PRICE_SUPP='0',"
                                . " DATE='" . $current_time . "'"
                            . " WHERE";
        $pieces_count = count($query_pieces);
        $tmp_pieces = [];
        foreach ($query_pieces as $piece_key => $piece) {
            $tmp_pieces[] = $piece;
            if ((($piece_key + 1) % 250 === 0) || ($piece_key + 1) === $pieces_count) {
                $query_statement .= implode(' OR ', $tmp_pieces) . ';';
                $this->db_tdm->query($query_statement);
                $all_queries .= " " . $query_statement;
                
                $tmp_pieces = [];
                $query_statement = " UPDATE"
                                . " TDM_PRICES"
                            . " SET" 
                                . " AVAILABLE='0',"
                                . " DAY='0',"
                                . " PRICE='0',"
                                . " PRICE_ORIG='0',"
                                . " PRICE_SUPP='0',"
                                . " DATE='" . $current_time . "'"
                            . " WHERE";
            }
        }
        unset($tmp_pieces);
        unset($query_pieces);
        unset($query_statement);
        /**
        foreach ($ws_ops_data as $ws) {
            $ws_code = $ws['data']['PRICE_CODE'];
            foreach ($ws['parts'] as $part) {
                $query_statement = "CALL pricesNullify('" . $part['BKeyTmp'] . "', '" . $part['AKEY'] . "', '" . $ws_code . "', '" . time() . "');";
                $this->db_tdm->query($query_statement);
                $all_queries .= " " . $query_statement;
            }
        }
        */
        //$this->db_tdm->query($query_statement);
        return $all_queries;
    }
    
    public function nullifyOrigPrices($prices_ops_data) {
        $all_queries = "";
        $query_statement = "";
        $this->load->model('search/parts');
        if (is_array($prices_ops_data) && !empty($prices_ops_data)) {
            $query_pieces = [];
            foreach ($prices_ops_data as $ops_data) {
                $ops_data = json_decode($ops_data, true);
                $ws_code = $ops_data['WS']['PRICE_CODE'];
                foreach ($ops_data['PRICES'] as $price_data) {
                    $query_pieces[] = ""
                        . "(BRAND_ID='" . $this->db_tdm->escape($this->model_search_parts->brandReplace($price_data['BRAND'])) . "'"
                            . " AND"
                        . " AKEY='" . $this->db_tdm->escape($this->model_search_parts->inputFormat($price_data['ARTICLE'])) . "'"
                            . " AND"
                        . " CODE='" . $this->db_tdm->escape($ws_code) . "')";
                }
            }
            $current_time = time();
            $query_statement = " UPDATE"
                                    . " TDM_PRICES"
                                . " SET" 
                                    . " AVAILABLE='0',"
                                    . " DAY='0',"
                                    . " PRICE='0',"
                                    . " PRICE_ORIG='0',"
                                    . " PRICE_SUPP='0',"
                                    . " DATE='" . $current_time . "'"
                                . " WHERE";
            $pieces_count = count($query_pieces);
            $tmp_pieces = [];
            foreach ($query_pieces as $piece_key => $piece) {
                $tmp_pieces[] = $piece;
                if ((($piece_key + 1) % 250 === 0) || ($piece_key + 1) === $pieces_count) {
                    $query_statement .= implode(' OR ', $tmp_pieces) . ';';
                    $this->db_tdm->query($query_statement);
                    $all_queries .= " " . $query_statement;

                    $tmp_pieces = [];
                    $query_statement = " UPDATE"
                                    . " TDM_PRICES"
                                . " SET" 
                                    . " AVAILABLE='0',"
                                    . " DAY='0',"
                                    . " PRICE='0',"
                                    . " PRICE_ORIG='0',"
                                    . " PRICE_SUPP='0',"
                                    . " DATE='" . $current_time . "'"
                                . " WHERE";
                }
            }
            unset($tmp_pieces);
            unset($query_pieces);
            unset($query_statement);
        }
        return $all_queries;
    }
    
    public function setTiming($ws_ops_data) {
        $query_statement = "";
        $all_queries = "";
        foreach ($ws_ops_data as $ws) {
            $ws_id = $ws['data']['ID'];
            foreach ($ws['parts'] as $part) {
                $query_statement = " REPLACE INTO TDM_WS_ANALOG_TIME2 (WSID, BRAND_ID, AKEY, TIME) VALUES ('{$ws_id}', '{$this->model_search_parts->brandReplace($part['BRAND'])}', '{$part['AKEY']}', '" . time() . "');";
                $this->db_tdm->query($query_statement);
                $all_queries .= " " . $query_statement;
            }
        }
        return $all_queries;
    }
    
    public function updateSearchTimers($prices_ops_data, $article, $brand) {
        $all_queries = "";
        $this->load->model('search/parts');
        if (is_array($prices_ops_data) && !empty($prices_ops_data)) {
            foreach ($prices_ops_data as $ops_data) {
                $ops_data = json_decode($ops_data, true);
                $ws_id = $ops_data['WS']['ID'];
                foreach ($ops_data['PRICES'] as $price_data) {
                    $query_statement = ""
                        . " REPLACE INTO TDM_WS_ANALOG_TIME2"
                            . " (WSID, BRAND_ID, AKEY, TIME)"
                        . " VALUES ('"
                            . $ws_id . "', '"
                            . $this->model_search_parts->brandReplace($price_data['BRAND'])
                            . "', '"
                            . $this->model_search_parts->inputFormat($price_data['ARTICLE'])
                            . "', '"
                            . time() . "');";
                    $this->db_tdm->query($query_statement);
                    $all_queries .= " " . $query_statement;
                }
                // Запись о проценке оригинала (на случай, если по нему не было
                // получено прайсов)
                $query_statement = " REPLACE INTO TDM_WS_TIME2 (WSID, BRAND_ID, AKEY, TIME) VALUES ('{$ws_id}', '{$this->model_search_parts->brandReplace($brand)}', '{$article}', '" . time() . "');";
                $this->db_tdm->query($query_statement);
                $all_queries .= " " . $query_statement;
            }
        }
        return $all_queries;
    }
    
    public function storeNewPrices($data) {
        $this->load->model('search/parts');
        $all_queries = "";
        $a_unique_hashes = [];
        foreach ($data as $prices_data) {
            // Распаковка полученных данных
            $prices_data_dc = json_decode($prices_data, true);
            
            $a_prices = (array)$prices_data_dc['PRICES'];
            $a_ws = (array)$prices_data_dc['WS'];
            
            //
            foreach ($a_prices as $price_raw) {
                if ($price_raw['BRAND'] === '' || $price_raw['ARTICLE'] === '') {
                    continue;
                }
                
                $a_price = $price_raw;
                $a_price['BRAND'] = $this->model_search_parts->brandReplace($a_price['BRAND']);
                //$a_price['BKEY'] = $this->model_search_parts->inputFormat($a_price['BRAND']);
                $a_price['AKEY'] = $this->model_search_parts->inputFormat($a_price['ARTICLE']);
                $a_price['DAY'] = $this->model_search_parts->formatDayNumbers($a_price['DAY']);
                $a_price['AVAILABLE'] = $this->model_search_parts->formatOnlyNumbers($a_price['AVAILABLE']);
                $a_price['ALT_NAME'] = $this->model_search_parts->formatDeleteQuotes($a_price['ALT_NAME']);
                $a_price['TYPE'] = $a_ws['TYPE'];
                $a_price['SUPPLIER'] = $a_ws['NAME'];
                $a_price['CODE'] = $a_ws['PRICE_CODE'];
                $a_price['DATE'] = $this->model_search_parts->setPriceDate();
                
                if ((int)$a_ws['DAY_ADD'] !== 0) {
                    $a_price['DAY'] += (int)$a_ws['DAY_ADD'];
                }
                if ((int)$a_ws['PRICE_EXTRA'] > 0) {
                    $a_price['PRICE'] = round($a_price['PRICE'] + ($a_price['PRICE'] * (int)$a_ws['PRICE_EXTRA']) / 100, 2);
                }
                $a_price['PRICE'] = ceil($a_price['PRICE']);
                if ((int)$a_ws['PRICE_ADD'] > 0) {
                    $a_price['PRICE'] += (int)$a_ws['PRICE_ADD'];
                }
                
                // Проверка на получение дубликата прайса
                $check_hash = md5($a_price['BRAND'] . '_'
                        . $a_price['AKEY']
                        . $a_price['PRICE'] 
                        . $a_price['TYPE'] 
                        . $a_price['CURRENCY'] 
                        . $a_price['DAY'] 
                        . $a_price['AVAILABLE'] 
                        . $a_price['SUPPLIER'] 
                        . $a_price['STOCK'] 
                        . $a_price['OPTIONS']);
                if (in_array($check_hash, $a_unique_hashes)) {
                    continue;
                }
                $a_unique_hashes[] = $check_hash;
                
                $a_price['PHID'] = md5($a_price["AKEY"]
                        . $a_price["ARTICLE"] 
                        . $a_price["ALT_NAME"] 
                        . $a_price["BRAND"] 
                        . $a_price["PRICE"] 
                        . $a_price["TYPE"] 
                        . $a_price["CURRENCY"] 
                        . $a_price["DAY"] 
                        . $a_price["AVAILABLE"] 
                        . $a_price["SUPPLIER"] 
                        . $a_price["STOCK"] 
                        . $a_price["OPTIONS"] 
                        . $a_price["CODE"] 
                        . $a_price["DATE"]);
                $a_price['PRICE_SUPP'] = $a_price['PRICE'];
                
                $static_hash = md5($a_price['AKEY']
                        . $a_price['ARTICLE'] 
                        . $a_price['BRAND'] 
                        . $a_price['SUPPLIER'] 
                        . $a_price['STOCK'] 
                        . $a_price['CODE'] 
                        . $a_price['SUPPLIER_OPTIONS']);
                $dynamic_hash = md5($a_price['DAY']
                        . $a_price['PRICE']
                        . $a_price['AVAILABLE'] 
                        . $a_price['CURRENCY']);
                
                $query_statement = ''
                    . 'INSERT INTO TDM_PRICES'
                        . ' (AKEY, ARTICLE, ALT_NAME, AVAILABLE, BRAND_ID, CURRENCY,'
                        . ' DAY, PRICE, STOCK, OPTIONS, SUPPLIER_OPTIONS, '
                        . ' PRICE_ORIG, CODE, TYPE, DATE, SUPPLIER, PRICE_SUPP,'
                        . ' STATIC_MD5, DYNAMIC_MD5)'
                    . ' VALUES '
                        . '('
                        . '\'' . $this->db_tdm->escape($a_price['AKEY']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['ARTICLE']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['ALT_NAME']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['AVAILABLE']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['BRAND']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['CURRENCY']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['DAY']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['PRICE']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['STOCK']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['OPTIONS']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['SUPPLIER_OPTIONS']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['PRICE_ORIG']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['CODE']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['TYPE']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['DATE']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['SUPPLIER']) . '\','
                        . '\'' . $this->db_tdm->escape($a_price['PRICE']) . '\','
                        . '\'' . $this->db_tdm->escape($static_hash) . '\','
                        . '\'' . $this->db_tdm->escape($dynamic_hash) . '\''
                        . ')'
                    . ' ON DUPLICATE KEY UPDATE'
                        . ' AVAILABLE = ' . '\'' . $this->db_tdm->escape($a_price['AVAILABLE']) . '\','
                        . ' CURRENCY = ' . '\'' . $this->db_tdm->escape($a_price['CURRENCY']) . '\','
                        . ' DAY = ' . '\'' . $this->db_tdm->escape($a_price['DAY']) . '\','
                        . ' PRICE = ' . '\'' . $this->db_tdm->escape($a_price['PRICE']) . '\','
                        . ' PRICE_ORIG = ' . '\'' . $this->db_tdm->escape($a_price['PRICE_ORIG']) . '\','
                        . ' PRICE_SUPP = ' . '\'' . $this->db_tdm->escape($a_price['PRICE']) . '\''
                    . ';';
                $this->db_tdm->query($query_statement);
                $all_queries .= " " . $query_statement;
            }
        }
        return $all_queries;
    }
    
    public function getStoredPrices($a_parts) {
        $this->load->model('search/parts');
        $search_date = $this->model_search_parts->setPriceDate();
        $query_statement = ""
                . " SELECT"
                    . " PRODUCT_ID, AKEY, ARTICLE, ALT_NAME,"
                    . " BRAND_ID AS BRAND, TYPE, CURRENCY, DAY, AVAILABLE, SUPPLIER,"
                    . " STOCK, OPTIONS, CODE, DATE, PHID, PRICE"
                . " FROM"
                    . " TDM_PRICES"
                . " WHERE (";
        $query_pieces = [];
        foreach ($a_parts as $part) {
            $query_pieces[] = "(BRAND_ID='" . $this->db_tdm->escape($part['BKEY']) . "'"
                            . " AND"
                            . " AKEY='" . $this->db_tdm->escape($part['AKEY']) . "')";
        }
        $query_statement .= implode(" OR ", $query_pieces)
                        . ") AND ('DATE' > '" . $search_date . "'"
                        . " OR CODE IN (SELECT CODE FROM TDM_IM_SUPPLIERS));";
        $query = $this->db_tdm->query($query_statement);
        
        return $query->rows;
    }
    
    public function getFavoriteStocks() {
        $query_statement = "SELECT CODE FROM TDM_IM_SUPPLIERS WHERE FAVORITE='1';";
        $query = $this->db_tdm->query($query_statement);
        $a_stocks = [];
        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $a_stocks[] = $row['CODE'];
            }
        }
        return $a_stocks;
    }
    
    public function getNewAnalogs($ops_data) {
        $this->load->model('search/parts');
        $parts = [];
        foreach ($ops_data as $data) {
            $data = json_decode($data, true);
            foreach ($data['PRICES'] as $price_data) {
                $brand_key = $this->model_search_parts->brandReplace($price_data['BRAND']);
                $article_key = $this->model_search_parts->inputFormat($price_data['ARTICLE']);
                $part_key = $brand_key . '_' . $article_key;
                $parts[$part_key] = [
                    'PKEY' => $part_key,
                    'AKEY' => $article_key,
                    'BKEY' => $brand_key,
                    'ARTICLE' => $article_key,
                    'BRAND' => $this->model_search_parts->getNameByBrandId($brand_key),
                    'NAME' => $price_data['ALT_NAME'],
                    'WS_CODE' => $data['WS']['PRICE_CODE']
                ];
            }
        }
        return $parts;
    }
    
    public function filterOriginalPrices($ops_data) {
        $this->load->model('search/parts');
        $filtered_data = [];
        foreach ($ops_data as $data) {
            $data = json_decode($data, true);
            foreach ($data['PRICES'] as $price_key => $price_data) {
                $data['PRICES'][$price_key]['BKEY'] = $this->model_search_parts->brandReplace($price_data['BRAND']);
                $data['PRICES'][$price_key]['AKEY'] = $this->model_search_parts->inputFormat($price_data['ARTICLE']);
                $query_statement = ""
                    . " SELECT *"
                    . " FROM TDM_WS_ANALOG_TIME2"
                    . " WHERE"
                        . " WSID = '" . $data['WS']['ID'] . "'"
                        . " AND BRAND_ID = '" . $this->model_search_parts->brandReplace($price_data['BRAND']) . "'"
                        . " AND AKEY = '" . $this->model_search_parts->inputFormat($price_data['ARTICLE']) . "'"
                    . " UNION"
                    . " SELECT *"
                    . " FROM TDM_WS_TIME2"
                    . " WHERE"
                        . " WSID = '" . $data['WS']['ID'] . "'"
                        . " AND BRAND_ID = '" . $this->model_search_parts->brandReplace($price_data['BRAND']) . "'"
                        . " AND AKEY = '" . $this->model_search_parts->inputFormat($price_data['ARTICLE']) . "';";
                $query = $this->db_tdm->query($query_statement);
                if ($query->num_rows) {
                    unset($data['PRICES'][$price_key]);
                }
            }
            $filtered_data[] = json_encode($data);
        }
        return $filtered_data;
    }
    
    public function createLinks($request_data, $analogs_data) {
        if (!empty($request_data) && !empty($analogs_data)) {
            $query_statement = ''
                . ' INSERT IGNORE INTO TDM_LINKS_TMP'
                    . ' (BRAND_ID1, AKEY1, BRAND_ID2, AKEY2, SIDE, CODE, STATUS)'
                . ' VALUES ';
            $query_pieces = [];
            foreach ($analogs_data as $analog) {
                if ((string) $request_data['BKEY'] === (string) $analog['BKEY'] && $request_data['AKEY'] === $analog['AKEY']) {
                    continue;
                }
                $query_pieces[] = "('" 
                    . $request_data['BKEY'] . "', '"
                    . $request_data['AKEY'] . "', '"
                    . $analog['BKEY'] . "', '"
                    . $analog['AKEY'] . "', '"
                    . 0 . "', '"
                    . $analog['WS_CODE'] . "', '"
                    . 0 . "')";
            }
            if (empty($query_pieces) || !isset($query_pieces)) {
                return '';
            } else {
                $query_statement .= implode(", ", $query_pieces) . ";";
                $this->db_tdm->query($query_statement);
                return $query_statement;
            }
        } else {
            return '';
        }
    }
    
    public function checkSearchStatus($key) {
        $this->load->model('search/parts');
        $search_date = $this->model_search_parts->setPriceDate();
        $query = $this->db_tdm->query("SELECT * FROM TDM_ACTIVE_SEARCH WHERE search_key = '" . $this->db_tdm->escape($key) . "' AND TIME >= '" . $search_date . "';");
        if ($query->num_rows) {
            return $query->row['status'];
        } else {
            return 0;
        }
    }
    
    public function updateSearchStatus($key, $status) {
        $this->db_tdm->query("REPLACE INTO TDM_ACTIVE_SEARCH (search_key, status, TIME) VALUES ('" . $this->db_tdm->escape($key) . "', '" . (int)$status . "', '" . time() . "')");
    }
}