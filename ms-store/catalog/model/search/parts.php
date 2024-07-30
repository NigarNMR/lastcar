<?php
class ModelSearchParts extends Model {
    /**
     * Форматирование строки с удалением запрещенных символов
     * 
     * @param string $input Исходная строка
     * @return string Отформатированная строка
     */
    public function inputFormat($input) {
        //Замена спецсимволов
        $output = mb_strtoupper($input);
        $output = trim($output);
        $output = str_replace("\xc3\x8b", "E", $output);
        $output = str_replace("\xc3\x96", "O", $output);
        $output = str_replace("\xc3\x92", "O", $output);
        $output = str_replace("\xc3\x84", "A", $output);
        $output = str_replace("\xc3\x9c", "U", $output);
        $output = str_replace("O'", "O", $output);
        $output = str_replace("\xe2\x84\x96", "", $output);
        
        //Удаление неподходящих символов из $output
        $output = preg_replace("/[^A-Z\xd0\x90-\xd0\xaf0-9a-z\xd0\xb0-\xd1\x8f]/", "", $output);
        
        return $output;
    }
    
    /**
     * Получает из переменной значение дней в целочисленном виде
     * 
     * @param string $val
     * @return string
     */
    public function formatDayNumbers($val)
    {
      // Проверяем не начинается ли строка с "-"
      if (0 < strpos($val, "-"))
      {
        // Вычленяем значение с 0 позиции по первый встреченный "-"
        $val = substr($val, 0, strpos($val, "-"));
      }
      // Удаляем все символы не являющиеся цифрами
      $val = preg_replace("/[^0-9]/", "", $val);
      return intval($val);
    }
    
    /**
     * Удаляет из входного параметра все, что не является цифрами и возвращает его
     * 
     * @param string $val
     * @return int
     */
    public function formatOnlyNumbers($val)
    {
      // Удаляем все символы не являющиеся цифрами
      $val = preg_replace("/[^0-9]/", "", $val);
      return intval($val);
    }
    
    /**
     * Удаляет кавычки из входного параметра и возвращает его
     * 
     * @param string $val
     * @return string
     */
    public function formatDeleteQuotes($val)
    {
      $val = str_replace("\"", "", $val);
      $val = str_replace("'", "", $val);
      return $val;
    }
    
    /**
     * Создание даты для прайса
     * 
     * @param type $stmp
     * @return type
     */
    public function setPriceDate($stmp = 0) {
      if ($stmp == 0)
      {
        $stmp = time();
      }
      $date = mktime(0, 0, 0, date("n", $stmp), date("j", $stmp), date("Y", $stmp));
      return $date;
    }
    
    /**
     * Форматирование списка
     * 
     * @param array $parts_list Полученный список деталей
     * @return array Отформатированный список
     */
    public function partslistFormat($parts_list) {
        $output_list = array();
        foreach ($parts_list as $part) {
            $output_list[] = array($part["BKEY"], $part["AKEY"], $part["NAME"], $part["ID"]);
        }
        return $output_list;
    }
    
    /**
     * Замена бренда из группы основным брендом
     * 
     * @param string $brand Заменяемый бренд
     * @return string Возвращаемый бренд
     */
    public function brandReplace($brand) {
        // Подключение Redis
        $this->load->library('rediscache');

        // Проверка существования таблицы замен в кэше
        if ($this->rediscache->exists('aliasTable')) {
            // Если кэш найден, то получить данные из кэша
            $aliases_table = json_decode($this->rediscache->get('aliasTable'), true);
        } else {
            // Если кэш не найден, то получить таблицу из БД и внести в кэш
            $aliases_table = $this->getAliasesTable();
            $this->rediscache->delete('aliasTable');
            $this->rediscache->set('aliasTable', json_encode($aliases_table));
        }

        // Проверка нахождения бренда в таблице
        if (isset($aliases_table[$brand]) && !empty($aliases_table[$brand])) {
            // Если бренд найден, то получить его замену
            $brandId = $aliases_table[$brand];
        } else {
            // Если бренд не найден, то добавить новый бренд в БД и создать запись-замену на себя
            $alias_parent_id = 0;
            $alias_check_status = 0;
            $this->brandAdd($brand, $alias_parent_id, $alias_check_status);

            $aliases_table = $this->getAliasesTable();
            $this->rediscache->delete('aliasTable');
            $this->rediscache->set('aliasTable', json_encode($aliases_table));

            if (isset($aliases_table[$brand]) && !empty($aliases_table[$brand])) {
                // Если бренд найден, то получить его замену
                $brandId = $aliases_table[$brand];
            } else {
                $brandId = 0;
            }
        }

        return $brandId;
    }

    public function getNameByBrandId($id) {
        $id = intval($id);

        // Подключение Redis
        $this->load->library('rediscache');

        // Проверка существования таблицы замен в кэше
        if ($this->rediscache->exists('brandTable')) {
            // Если кэш найден, то получить данные из кэша
            $brandTable = json_decode($this->rediscache->get('brandTable'), true);
        } else {
            // Если кэш не найден, то получить таблицу из БД и внести в кэш
            $brandTable = $this->getBrandTable();
            $this->rediscache->delete('brandTable');
            $this->rediscache->set('brandTable', json_encode($brandTable));
        }

        if (isset($brandTable[$id]) && !empty($brandTable[$id])) {
            // Если бренд найден, то получить его замену
            $brand = $brandTable[$id];
        } else {
            $brand = '';
        }

        return $brand;
    }

    public function getBrandTable() {
        $sql="SELECT b__id AS ID, b__name AS NAME FROM  `TDM_BRANDS` WHERE 1 ";

        $query = $this->db_tdm->query($sql);

        foreach ($query->rows as $row) {
            $result[(int) $row['ID']] = (string) $row['NAME'];
        }

        return $result;
    }
    
    public function brandAdd($brand_name, $brand_parent_id, $brand_check_status) {
        $brand_name = $this->db_tdm->escape(trim($brand_name));
        
        // Выражения для проверки бренда на совпадение с уже имеющимся в таблице
        $sql_exp = 'SELECT 1 FROM TDM_BRANDS WHERE  b__name=\'' . $brand_name . '\';';
        $query = $this->db_tdm->query($sql_exp);
        
        if (!$query->num_rows) {
            // Если бренд не был найден выполнить добавление в таблицу
            $sql_exp = 'INSERT INTO TDM_BRANDS (b__name, b__parent_id, b__status) VALUES (\'' . $brand_name . '\',\'' . $brand_parent_id . '\', \'' . $brand_check_status . '\');';
            $this->db_tdm->query($sql_exp);
        }
    }
    
    public function getAliasesTable() {
        //Составление запроса к БД
        $sql_exp =/* 'SELECT '
                    . 'TB.b__name as b__name, '
                    . 'TP.b__name as b__parent_name '
                . 'FROM '
                    . 'TDM_BRANDS TB '
                . 'INNER JOIN '
                    . 'TDM_BRANDS TP '
                    . 'ON TB.b__parent_id=TP.b__id '
                . 'WHERE TB.b__parent_id<>\'0\' '
                . 'UNION '
                . 'SELECT '
                    . 'TB.b__name as b__name, '
                    . 'TP.b__name as b__parent_name '
                . 'FROM '
                    . 'TDM_BRANDS TB '
                . 'INNER JOIN '
                    . 'TDM_BRANDS TP '
                . 'ON TB.b__id=TP.b__id '
                . 'WHERE TB.b__parent_id=\'0\';'; */
            'SELECT '
            . 'TB.b__name as b__name, '
            . 'TP.b__name as b__parent_name,'
             . 'TB.b__id as b__id, '
             . 'TP.b__id as b__parent_id '
            . 'FROM '
            . 'TDM_BRANDS TB '
            . 'INNER JOIN '
            . 'TDM_BRANDS TP '
            . 'ON TB.b__parent_id=TP.b__id '
            . 'WHERE TB.b__parent_id<>\'0\' '
            . 'UNION '
            . 'SELECT '
            . 'TB.b__name as b__name, '
            . 'TP.b__name as b__parent_name, '
             . 'TB.b__parent_id as b__parent_id, '
             . 'TP.b__id as b__id '
            . 'FROM '
            . 'TDM_BRANDS TB '
            . 'INNER JOIN '
            . 'TDM_BRANDS TP '
            . 'ON TB.b__id=TP.b__id '
            . 'WHERE TB.b__parent_id=\'0\';';

        $query = $this->db_tdm->query($sql_exp);
        
        foreach ($query->rows as $row) {
            $result[(string) $row['b__name']] = (int) $row['b__parent_id'];
        }
        
        return $result;
    }
    
    public function getActiveWs() {
        $query = $this->db_tdm->query('SELECT * FROM TDM_WS WHERE ACTIVE = \'1\'');
        return $query->rows;
    }
    
    public function dimentionalSort($sorting_list, $key) {
        usort($sorting_list, $this->inputCompare($key));
        return $sorting_list;
    }
    
    /**
     * Сравнение строк по указанному ключу
     * 
     * @param int|string $key Ключ списка
     * @return int Результат сравнения
     */
    public function inputCompare($key) {
        return function ($a, $b) use ($key) {
            return strnatcmp(strtoupper($a[$key]), strtoupper($b[$key]));
        };
    }
    
    public function checkSearchStatus($key) {
        $this->load->model('search/parts');
        $search_date = $this->model_search_parts->setPriceDate();
        $query = $this->db_tdm->query("SELECT * FROM TDM_ACTIVE_SEARCH_PARTS WHERE search_key = '" . $this->db_tdm->escape($key) . "' AND TIME >= '" . $search_date . "';");
        if ($query->num_rows) {
            return $query->row['status'];
        } else {
            return 0;
        }
    }
    
    public function updateSearchStatus($key, $status) {
        $this->db_tdm->query("REPLACE INTO TDM_ACTIVE_SEARCH_PARTS (search_key, status, TIME) VALUES ('" . $this->db_tdm->escape($key) . "', '" . (int)$status . "', '" . time() . "')");
    }
}