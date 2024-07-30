<?php
class ControllerSearchPrices extends Controller {
    public function index() {
        $this->load->language('checkout/cart');
        $this->load->model('search/parts');

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

        $data['article'] = $article;
        $data['brand'] = $brand;
        $data['url_collect'] = $this->url->link('search/prices/collect');
        $data['url_collect_prices'] = $this->url->link('search/prices');
        $data['url_analog_json'] = $this->url->link('search/prices/analogJson');
        $data['url_original_price_json'] = $this->url->link('search/prices/originalPriceJson');
        $data['url_analog_price_json'] = $this->url->link('search/prices/analogPriceJson');
        $data['url_final_price_json'] = $this->url->link('search/prices/finalPriceJson');
        $data['url_search_prices_manager'] = $this->url->link('search/prices_manager');

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

        $data['breadcrumbs'][] = array(
            'href' => NULL,
            'text' => $this->language->get('Поиск'),

        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('search/parts&article=' . $article),
            'text' => $this->language->get($article),

        );

        $data['breadcrumbs'][] = array(
            'href' => NULL,
            'text' => $this->language->get($this->model_search_parts->getNameByBrandId($brand)),

        );

        $this->response->setOutput($this->load->view('search/prices', $data));
    }

    public function collect() {
        $this->load->model('search/parts');
        $this->load->model('search/prices');
        $this->load->model('search/prices_gearman');
        // Подключение класса логгера
        $this->load->library('logger');
        // Подключение Redis
        $this->load->library('rediscache');


            $article = '11427512300';
            $brand = '143';


        if (empty($article) && empty($brand)) {
            if (isset($this->request->post['article'])) {
                $article = urldecode($this->request->post['article']);
            }

            if (isset($this->request->post['brand'])) {
                $brand = urldecode($this->request->post['brand']);
            }
        }

        $search_key = md5(md5($brand) . md5($article));

        sleep(1);
        $this->model_search_prices->updateSearchStatus($search_key, 2);
        sleep(5);
        $this->model_search_prices->updateSearchStatus($search_key, 3);
        sleep(20);
        $this->model_search_prices->updateSearchStatus($search_key, 4);
        sleep(5);
        $this->model_search_prices->updateSearchStatus($search_key, 5);




       /* $this->load->model('search/parts');
        $this->load->model('search/prices');
        $this->load->model('search/prices_gearman');
        // Подключение класса логгера
        $this->load->library('logger');
        // Подключение Redis
        $this->load->library('rediscache');

        if (isset($this->request->get['article'])) {
            $article = urldecode($this->request->get['article']);
        } else {
            $article = '';
        }

        // Получение brand_id
        if (isset($this->request->get['brand'])) {
            $brand = urldecode($this->request->get['brand']);
        } else {
            $brand = '0';
        }

        if (empty($article) && empty($brand)) {
            if (isset($this->request->post['article'])) {
                $article = urldecode($this->request->post['article']);
            }

            if (isset($this->request->post['brand'])) {
                $brand = urldecode($this->request->post['brand']);
            }
        }

        $search_key = md5(md5($brand) . md5($article));

        $this->logger->open(
            date('Ymd')
                . '_'
                . str_replace('/', '', $article)
                . '_'
                . str_replace('/', '', $brand),
            'SNB_',
            '.log');
        // Запись лога обращения к поиску
        $this->logger->notice('Запрос на поиск прайсов с серийным номером - ' . $article . ' и маркой - ' . $brand);

        // Сохранение старого значения артикула
        $article_original = $article;
        // Форматирование артикула
        $article = $this->model_search_parts->inputFormat($article);

        // Замена бренда по группе брендов
        // $brand = $this->model_search_parts->brandReplace($brand); // Замена не требуется т.к. это уже brand_id
        // Сохранение старого значения бренда
        $brand_original = $brand; // brand_original - тоже brand_id
        // Форматирование бренда
        // $brand = $this->model_search_parts->inputFormat($brand); // Форматирование не требуется т.к. brand_id

        $this->logger->info('Отформатированный серийный номер: ' . $article);
        $this->logger->info('Отформатированный бренд детали: ' . $brand);

        $cache_name = 'SEARCH_AB_' . $article . '_'. $brand;
        $this->logger->write('Имя кэша: ' . $cache_name);

        if ($this->rediscache->existsHash('SEARCH', $cache_name . '_TIMER') && (intval($this->rediscache->getHash('SEARCH', $cache_name . '_TIMER'))) > time()) {
            $this->logger->info('Кэш с именем ' . $cache_name . ' был найден.');
            // Если существует актуальный кэш, то вытащить его
            $result = $this->rediscache->getHash('SEARCH', $cache_name);
        } else {
            $this->logger->warning('Кэш с именем ' . $cache_name . ' не был найден');
            set_time_limit(0);
            $operational_data_list = array();

            // Заполнение массива настроек и генерация задания по БД TecDoc
            $connection_config = array(
              'SERVER' => $this->config->get('db_td_hostname'),
              'LOGIN' => $this->config->get('db_td_username'),
              'PASS' => $this->config->get('db_td_password'),
              'NAME' => $this->config->get('db_td_database'),
              'PORT' => $this->config->get('db_td_port'));
            // Массив данных передаваемых worker'у
            // Получаем наименование бренда по id для поиска в каталоге TD
            $brand_name = $this->model_search_parts->getNameByBrandId($brand);
            $brand_name = $this->model_search_parts->inputFormat($brand_name);
            $operational_data = json_encode(array('conType' => 'tecdoc', 'conOptions' => $connection_config, 'srchData' => array('article' => $article, 'brand' => $brand_name)));
            $operational_data_list[] = $operational_data;
            // Заполнение массива настроек и генерация задания по БД Модуля
            $connection_config = array(
              'SERVER' => $this->config->get('db_tdm_hostname'),
              'LOGIN' => $this->config->get('db_tdm_username'),
              'PASS' => $this->config->get('db_tdm_password'),
              'NAME' => $this->config->get('db_tdm_database'),
              'PORT' => $this->config->get('db_tdm_port'));
            // Массив данных передаваемых worker'у
            $operational_data = json_encode(array('conType' => 'tdmlinks', 'conOptions' => $connection_config, 'srchData' => array('article' => $article, 'brand' =>  $brand)));
            $operational_data_list[] = $operational_data;

            // Выбор аналогов по искомой детали
            $analogs_data = $this->model_search_prices_gearman->searchAnalogsGearman($operational_data_list);
            unset($connection_config);
            unset($operational_data);
            unset($operational_data_list);
            if (isset($analogs_data['parts'])) {
                $parts_np = $analogs_data['parts'];
            } else {
                $parts_np = [];
            }
            if (isset($analogs_data['part_ids'])) {
                $part_ids_np = $analogs_data['part_ids'];
            } else {
                $part_ids_np = [];
            }
            unset($analogs_data);

            $this->logger->info('Количество найденных аналогов - ' . count($parts_np));
            $this->logger->rawData('Найденные аналоги: ' . json_encode($parts_np, JSON_UNESCAPED_UNICODE));
            $this->logger->rawData('Ключи по найденным деталям: ' . json_encode($part_ids_np, JSON_UNESCAPED_UNICODE));

            $ops_data = [
                'PKEY' => $brand . '_' . $article,
                'BKEY' => $brand, // brand_id
                'BRAND'=> $brand_original, // brand_id
                'AKEY' => $article,
                'ARTICLE' => $article
            ];
            $ws_ops_data = $this->model_search_prices->getWsOriginalParts($ops_data);
            $this->logger->info('Данные для поиска по оригиналу: ' . json_encode($ws_ops_data));
            // Получение прайсов по запрашиваемой зч
            $results_buffer = $this->model_search_prices->getOriginalPrices($ws_ops_data);
            foreach ($results_buffer as $result) {
                $result_dc = json_decode($result, true);
                $this->logger->rawData('Результаты поиска: WS - ' . $result_dc['WS']['NAME'] . '; Данные - ' . json_encode($result_dc['PRICES'], JSON_UNESCAPED_UNICODE));
            }
            // Дополняем список аналогов по новым кроссам
            $new_analogs = $this->model_search_prices->getNewAnalogs($results_buffer);
            $parts_np = array_merge($parts_np, $new_analogs);
            // Создание записей кроссов
            $this->logger->info('Запрос на добавление новых кроссов: ' . $this->model_search_prices->createLinks($ops_data, $new_analogs));
            // Фильтрация прайсов по уже процененым аналогам
            $results_buffer = $this->model_search_prices->filterOriginalPrices($results_buffer);
            if (is_array($results_buffer) && !empty($results_buffer)) {
                foreach ($results_buffer as $result) {
                    $result_dc = json_decode($result, true);
                    $this->logger->rawData('Результаты поиска после фильтрации: WS - ' . $result_dc['WS']['NAME'] . '; Данные - ' . json_encode($result_dc['PRICES'], JSON_UNESCAPED_UNICODE));
                }
            }
            // Обнулить старые прайсы
            $this->logger->info('Запросы на обнуление старых цен после проценки оригинала: ' . $this->model_search_prices->nullifyOrigPrices($results_buffer));
            // Обновить таймеры проценок
            $this->logger->info('Запросы по созданию временных отметок по процененным оригиналу и аналогам от web-сервиса: ' . $this->model_search_prices->updateSearchTimers($results_buffer, $article, $brand_original));
            // Записать новые прайсы
            $this->logger->info('Запросы по добавлению новых прайсов по проценке оригинала: ' . $this->model_search_prices->storeNewPrices($results_buffer));

            // Заполнение и фильтрация массивов деталей по поставщикам
            $ws_ops_data = $this->model_search_prices->getWsParts($parts_np);
            $this->logger->info('Данные для поиска по аналогам: ' . json_encode($ws_ops_data, JSON_UNESCAPED_UNICODE));
            // Обнуление старых прайсов
            $this->logger->info('Запросы по обнулению старых цен: ' . $this->model_search_prices->nullifyPrices($ws_ops_data));
            // Создание записи о проценке
            $this->logger->info('Запросы по созданию временных отметок по процененным аналогам: ' . $this->model_search_prices->setTiming($ws_ops_data));

            // Получение прайсов по аналогам
            $results_buffer = $this->model_search_prices->getPrices($ws_ops_data);

            foreach ($results_buffer as $result_cd) {
                $result_dc = json_decode($result_cd, true);
                $this->logger->rawData('Результаты поиска: WS - ' . $result_dc['WS']['NAME'] . '; Данные - ' . json_encode($result_dc['PRICES'], JSON_UNESCAPED_UNICODE));
            }

            // Запись прайсов в БД
            $this->logger->info('Запросы по добавлению новых прайсов по проценке аналогов: ' . $this->model_search_prices->storeNewPrices($results_buffer));

            if (!isset($parts_np[$brand . '_' . $article]) || empty($parts_np[$brand . '_' . $article])) {
                $parts_np[$brand . '_' . $article] = [
                    'PKEY' => $brand . $article,
                    'AKEY' => $article,
                    'ARTICLE' => $article,
                    'BKEY' => $brand,
                    'BRAND' => $brand_original,
                    'NAME' => ''
                ];
            }


            if (count($parts_np)) {
                // Выбрать прайсы
                $a_prices = $this->model_search_prices->getStoredPrices($parts_np);
                $this->logger->rawData('Цены полученные из БД: ' . json_encode($a_prices, JSON_UNESCAPED_UNICODE));
            }

            /* Получение данных по деталям из TD

            // Получение списка избранных складов (поставщиков)
            $a_favorite_stocks = $this->model_search_prices->getFavoriteStocks();

            /* Разделение прайсов на группы
            $part_fields = array('brand','article','description', 'img_url', 'img_type', 'dopinfo_id', 'items' =>array('stock', 'availability', 'date', 'price', 'product_id'));

            $parts_recomend = array();
            $parts_request = array();
            $parts_analog_original = array();
            $parts_analog = array();

            // Рекомендуемое
            foreach ($a_prices as $price_key => $price_data) {
                if (in_array($price_data['CODE'], $a_favorite_stocks)) {
                    $part_key = $price_data['BKEY'] . $price_data['AKEY'];
                    if (!isset($parts_recomend[$part_key]) || empty($parts_recomend[$part_key])) {
                        $parts_recomend[$part_key] = [
                            $this->model_search_parts->getNameByBrandId($price_data['BRAND']),
                            $price_data['ARTICLE'],
                            $price_data['ALT_NAME'],
                            '',
                            '2',
                            '',
                            'items' => []
                        ];
                    }
                    $parts_recomend[$part_key]['items'][] = [
                        $price_data['CODE'],
                        $price_data['AVAILABLE'],
                        $price_data['DAY'],
                        $price_data['PRICE'],
                        $price_data['PRODUCT_ID']
                    ];
                    $a_prices[$price_key] = NULL;
                    unset($a_prices[$price_key]);
                }
            }
            // Сортировка цен каждой детали
            foreach ($parts_recomend as $part_key => $part_data) {
                $parts_recomend[$part_key]['items'] = $this->model_search_parts->dimentionalSort($part_data['items'],3);
            }
            ksort($parts_recomend);
            $parts_tmp = $parts_recomend;
            $parts_recomend = [];
            foreach ($parts_tmp as $part) {
                $parts_recomend[] = $part;
            }
            unset($parts_tmp);

            // Запрашиваемое
            foreach ($a_prices as $price_key => $price_data) {
                if ($price_data['BRAND'] === $brand && $price_data['AKEY'] === $article) {
                    $part_key = $price_data['BRAND'] . $price_data['AKEY'];
                    if (!isset($parts_request[$part_key]) || empty($parts_request[$part_key])) {
                        $parts_request[$part_key] = [
                            $this->model_search_parts->getNameByBrandId($price_data['BRAND']),
                            $price_data['ARTICLE'],
                            $price_data['ALT_NAME'],
                            '',
                            '2',
                            '',
                            'items' => []
                        ];
                    }
                    $parts_request[$part_key]['items'][] = [
                        $price_data['CODE'],
                        $price_data['AVAILABLE'],
                        $price_data['DAY'],
                        $price_data['PRICE'],
                        $price_data['PRODUCT_ID']
                    ];
                    $a_prices[$price_key] = NULL;
                    unset($a_prices[$price_key]);
                }
            }
            // Сортировка цен каждой детали
            foreach ($parts_request as $part_key => $part_data) {
                $parts_request[$part_key]['items'] = $this->model_search_parts->dimentionalSort($part_data['items'],3);
            }
            ksort($parts_request);
            $parts_tmp = $parts_request;
            $parts_request = [];
            foreach ($parts_tmp as $part) {
                $parts_request[] = $part;
            }
            unset($parts_tmp);

            // Оригинальные заменители
            foreach ($a_prices as $price_key => $price_data) {
                if ($price_data['BRAND'] === $brand) {
                    $part_key = $price_data['BRAND'] . $price_data['AKEY'];
                    if (!isset($parts_analog_original[$part_key]) || empty($parts_analog_original[$part_key])) {
                        $parts_analog_original[$part_key] = [
                            $this->model_search_parts->getNameByBrandId($price_data['BRAND']),
                            $price_data['ARTICLE'],
                            $price_data['ALT_NAME'],
                            '',
                            '2',
                            '',
                            'items' => []
                        ];
                    }
                    $parts_analog_original[$part_key]['items'][] = [
                        $price_data['CODE'],
                        $price_data['AVAILABLE'],
                        $price_data['DAY'],
                        $price_data['PRICE'],
                        $price_data['PRODUCT_ID']
                    ];
                    $a_prices[$price_key] = NULL;
                    unset($a_prices[$price_key]);
                }
            }
            // Сортировка цен каждой детали
            foreach ($parts_analog_original as $part_key => $part_data) {
                $parts_analog_original[$part_key]['items'] = $this->model_search_parts->dimentionalSort($part_data['items'],3);
            }
            ksort($parts_analog_original);
            $parts_tmp = $parts_analog_original;
            $parts_analog_original = [];
            foreach ($parts_tmp as $part) {
                $parts_analog_original[] = $part;
            }
            unset($parts_tmp);

            // Заменители
            foreach ($a_prices as $price_key => $price_data) {
                $part_key = $price_data['BRAND'] . $price_data['AKEY'];
                if (!isset($parts_analog[$part_key]) || empty($parts_analog[$part_key])) {
                    $parts_analog[$part_key] = [
                        $this->model_search_parts->getNameByBrandId($price_data['BRAND']),
                        $price_data['ARTICLE'],
                        $price_data['ALT_NAME'],
                        '',
                        '2',
                        '',
                        'items' => []
                    ];
                }
                $parts_analog[$part_key]['items'][] = [
                    $price_data['CODE'],
                    $price_data['AVAILABLE'],
                    $price_data['DAY'],
                    $price_data['PRICE'],
                    $price_data['PRODUCT_ID']
                ];
                $a_prices[$price_key] = NULL;
                unset($a_prices[$price_key]);
            }
            // Сортировка цен каждой детали
            foreach ($parts_analog as $part_key => $part_data) {
                $parts_analog[$part_key]['items'] = $this->model_search_parts->dimentionalSort($part_data['items'],3);
            }
            ksort($parts_analog);
            $parts_tmp = $parts_analog;
            $parts_analog = [];
            foreach ($parts_tmp as $part) {
                $parts_analog[] = $part;
            }
            unset($parts_tmp);

            /* Формирование кэша
            $a_result = [];
            $a_result['PARTS'] = [];
            $a_result['PARTS']['fields'] = $part_fields;
            $a_result['PARTS']['parts_recomend'] = [];
            $a_result['PARTS']['parts_recomend']['data'] = $parts_recomend;
            $a_result['PARTS']['parts_request'] = [];
            $a_result['PARTS']['parts_request']['data'] = $parts_request;
            $a_result['PARTS']['parts_analog_original'] = [];
            $a_result['PARTS']['parts_analog_original']['data'] = $parts_analog_original;
            $a_result['PARTS']['parts_analog'] = [];
            $a_result['PARTS']['parts_analog']['data'] = $parts_analog;

            $result = json_encode($a_result, JSON_UNESCAPED_UNICODE);
            $this->logger->rawData('Итоговый набор прайсов после проценки: ' . $result);

            // Занесение данных в кэш
            $this->rediscache->setHash('SEARCH', $cache_name, $result);
            $this->rediscache->setHash('SEARCH', $cache_name . '_TIMER', $this->model_search_parts->setPriceDate() + 24*60*60);
            $this->logger->info('Данные были занесены в кэш под именем: ' . $cache_name);
        }
        $this->logger->warning('Ключ активного поиска: ' . $search_key);
       $this->model_search_prices->updateSearchStatus($search_key, 2);

        $this->logger->rawData('Набор прайсов после форматирования: ' . $result);
        $this->logger->notice("Завершение поиска\r\n");
        // Штатное сохранение и закрытие файла
        $this->logger->close();

        $this->response->setOutput($result);*/
    }


    public function collect1()
    {
        $this->load->model('search/parts');
        $this->load->model('search/prices');
        $this->load->model('search/prices_gearman');
        // Подключение класса логгера
        $this->load->library('logger');
        // Подключение Redis
        $this->load->library('rediscache');

        if (isset($this->request->get['article'])) {
            $article = urldecode($this->request->get['article']);
        } else {
            $article = '';
        }

        // Получение brand_id
        if (isset($this->request->get['brand'])) {
            $brand = urldecode($this->request->get['brand']);
        } else {
            $brand = '0';
        }

        if (empty($article) && empty($brand)) {
            if (isset($this->request->post['article'])) {
                $article = urldecode($this->request->post['article']);
            }

            if (isset($this->request->post['brand'])) {
                $brand = urldecode($this->request->post['brand']);
            }
        }

        $search_key = md5(md5($brand) . md5($article));

        $this->logger->open(
            date('Ymd')
            . '_'
            . str_replace('/', '', $article)
            . '_'
            . str_replace('/', '', $brand),
            'SNB_',
            '.log');
        // Запись лога обращения к поиску
        $this->logger->notice('Запрос на поиск прайсов с серийным номером - ' . $article . ' и маркой - ' . $brand);

        // Сохранение старого значения артикула
        $article_original = $article;
        // Форматирование артикула
        $article = $this->model_search_parts->inputFormat($article);

        // Замена бренда по группе брендов
        // $brand = $this->model_search_parts->brandReplace($brand); // Замена не требуется т.к. это уже brand_id
        // Сохранение старого значения бренда
        $brand_original = $brand; // brand_original - тоже brand_id
        // Форматирование бренда
        // $brand = $this->model_search_parts->inputFormat($brand); // Форматирование не требуется т.к. brand_id

        $this->logger->info('Отформатированный серийный номер: ' . $article);
        $this->logger->info('Отформатированный бренд детали: ' . $brand);

        $cache_name = 'SEARCH_AB_' . $article . '_' . $brand;
        $this->logger->write('Имя кэша: ' . $cache_name);

        if ($this->rediscache->existsHash('SEARCH', $cache_name . '_TIMER') && (intval($this->rediscache->getHash('SEARCH', $cache_name . '_TIMER'))) > time()) {
            $this->logger->info('Кэш с именем ' . $cache_name . ' был найден.');
            // Если существует актуальный кэш, то вытащить его
            $result = $this->rediscache->getHash('SEARCH', $cache_name);
        } else {
            $this->logger->warning('Кэш с именем ' . $cache_name . ' не был найден');
            set_time_limit(0);
            $operational_data_list = array();

            // Заполнение массива настроек и генерация задания по БД TecDoc
            $connection_config = array(
                'SERVER' => $this->config->get('db_td_hostname'),
                'LOGIN' => $this->config->get('db_td_username'),
                'PASS' => $this->config->get('db_td_password'),
                'NAME' => $this->config->get('db_td_database'),
                'PORT' => $this->config->get('db_td_port'));
            // Массив данных передаваемых worker'у
            // Получаем наименование бренда по id для поиска в каталоге TD
            $brand_name = $this->model_search_parts->getNameByBrandId($brand);
            $brand_name = $this->model_search_parts->inputFormat($brand_name);
            $operational_data = json_encode(array('conType' => 'tecdoc', 'conOptions' => $connection_config, 'srchData' => array('article' => $article, 'brand' => $brand_name)));
            $operational_data_list[] = $operational_data;
            // Заполнение массива настроек и генерация задания по БД Модуля
            $connection_config = array(
                'SERVER' => $this->config->get('db_tdm_hostname'),
                'LOGIN' => $this->config->get('db_tdm_username'),
                'PASS' => $this->config->get('db_tdm_password'),
                'NAME' => $this->config->get('db_tdm_database'),
                'PORT' => $this->config->get('db_tdm_port'));
            // Массив данных передаваемых worker'у
            $operational_data = json_encode(array('conType' => 'tdmlinks', 'conOptions' => $connection_config, 'srchData' => array('article' => $article, 'brand' => $brand)));
            $operational_data_list[] = $operational_data;

            // Выбор аналогов по искомой детали
            $analogs_data = $this->model_search_prices_gearman->searchAnalogsGearman($operational_data_list);
            unset($connection_config);
            unset($operational_data);
            unset($operational_data_list);
            if (isset($analogs_data['parts'])) {
                $parts_np = $analogs_data['parts'];
            } else {
                $parts_np = [];
            }
            if (isset($analogs_data['part_ids'])) {
                $part_ids_np = $analogs_data['part_ids'];
            } else {
                $part_ids_np = [];
            }
            unset($analogs_data);

            $this->logger->info('Количество найденных аналогов - ' . count($parts_np));
            $this->logger->rawData('Найденные аналоги: ' . json_encode($parts_np, JSON_UNESCAPED_UNICODE));
            $this->logger->rawData('Ключи по найденным деталям: ' . json_encode($part_ids_np, JSON_UNESCAPED_UNICODE));

            $ops_data = [
                'PKEY' => $brand . '_' . $article,
                'BKEY' => $brand, // brand_id
                'BRAND' => $brand_original, // brand_id
                'AKEY' => $article,
                'ARTICLE' => $article
            ];
            $ws_ops_data = $this->model_search_prices->getWsOriginalParts($ops_data);
            $this->logger->info('Данные для поиска по оригиналу: ' . json_encode($ws_ops_data));
            // Получение прайсов по запрашиваемой зч
            $results_buffer = $this->model_search_prices->getOriginalPrices($ws_ops_data);
            foreach ($results_buffer as $result) {
                $result_dc = json_decode($result, true);
                $this->logger->rawData('Результаты поиска: WS - ' . $result_dc['WS']['NAME'] . '; Данные - ' . json_encode($result_dc['PRICES'], JSON_UNESCAPED_UNICODE));
            }
            // Дополняем список аналогов по новым кроссам
            $new_analogs = $this->model_search_prices->getNewAnalogs($results_buffer);
            $parts_np = array_merge($parts_np, $new_analogs);
            // Создание записей кроссов
            $this->logger->info('Запрос на добавление новых кроссов: ' . $this->model_search_prices->createLinks($ops_data, $new_analogs));
            // Фильтрация прайсов по уже процененым аналогам
            $results_buffer = $this->model_search_prices->filterOriginalPrices($results_buffer);
            if (is_array($results_buffer) && !empty($results_buffer)) {
                foreach ($results_buffer as $result) {
                    $result_dc = json_decode($result, true);
                    $this->logger->rawData('Результаты поиска после фильтрации: WS - ' . $result_dc['WS']['NAME'] . '; Данные - ' . json_encode($result_dc['PRICES'], JSON_UNESCAPED_UNICODE));
                }
            }
            // Обнулить старые прайсы
            $this->logger->info('Запросы на обнуление старых цен после проценки оригинала: ' . $this->model_search_prices->nullifyOrigPrices($results_buffer));
            // Обновить таймеры проценок
            $this->logger->info('Запросы по созданию временных отметок по процененным оригиналу и аналогам от web-сервиса: ' . $this->model_search_prices->updateSearchTimers($results_buffer, $article, $brand_original));
            // Записать новые прайсы
            $this->logger->info('Запросы по добавлению новых прайсов по проценке оригинала: ' . $this->model_search_prices->storeNewPrices($results_buffer));

            // Заполнение и фильтрация массивов деталей по поставщикам
            $ws_ops_data = $this->model_search_prices->getWsParts($parts_np);
            $this->logger->info('Данные для поиска по аналогам: ' . json_encode($ws_ops_data, JSON_UNESCAPED_UNICODE));
            // Обнуление старых прайсов
            $this->logger->info('Запросы по обнулению старых цен: ' . $this->model_search_prices->nullifyPrices($ws_ops_data));
            // Создание записи о проценке
            $this->logger->info('Запросы по созданию временных отметок по процененным аналогам: ' . $this->model_search_prices->setTiming($ws_ops_data));

            // Получение прайсов по аналогам
            $results_buffer = $this->model_search_prices->getPrices($ws_ops_data);

            foreach ($results_buffer as $result_cd) {
                $result_dc = json_decode($result_cd, true);
                $this->logger->rawData('Результаты поиска: WS - ' . $result_dc['WS']['NAME'] . '; Данные - ' . json_encode($result_dc['PRICES'], JSON_UNESCAPED_UNICODE));
            }

            // Запись прайсов в БД
            $this->logger->info('Запросы по добавлению новых прайсов по проценке аналогов: ' . $this->model_search_prices->storeNewPrices($results_buffer));

            if (!isset($parts_np[$brand . '_' . $article]) || empty($parts_np[$brand . '_' . $article])) {
                $parts_np[$brand . '_' . $article] = [
                    'PKEY' => $brand . $article,
                    'AKEY' => $article,
                    'ARTICLE' => $article,
                    'BKEY' => $brand,
                    'BRAND' => $brand_original,
                    'NAME' => ''
                ];
            }


            if (count($parts_np)) {
                // Выбрать прайсы
                $a_prices = $this->model_search_prices->getStoredPrices($parts_np);
                $this->logger->rawData('Цены полученные из БД: ' . json_encode($a_prices, JSON_UNESCAPED_UNICODE));
            }

            /* Получение данных по деталям из TD */

            // Получение списка избранных складов (поставщиков)
            $a_favorite_stocks = $this->model_search_prices->getFavoriteStocks();

            /* Разделение прайсов на группы */
            $part_fields = array('brand', 'article', 'description', 'img_url', 'img_type', 'dopinfo_id', 'items' => array('stock', 'availability', 'date', 'price', 'product_id'));

            $parts_recomend = array();
            $parts_request = array();
            $parts_analog_original = array();
            $parts_analog = array();

            // Рекомендуемое
            foreach ($a_prices as $price_key => $price_data) {
                if (in_array($price_data['CODE'], $a_favorite_stocks)) {
                    $part_key = $price_data['BKEY'] . $price_data['AKEY'];
                    if (!isset($parts_recomend[$part_key]) || empty($parts_recomend[$part_key])) {
                        $parts_recomend[$part_key] = [
                            $this->model_search_parts->getNameByBrandId($price_data['BRAND']),
                            $price_data['ARTICLE'],
                            $price_data['ALT_NAME'],
                            '',
                            '2',
                            '',
                            'items' => []
                        ];
                    }
                    $parts_recomend[$part_key]['items'][] = [
                        $price_data['CODE'],
                        $price_data['AVAILABLE'],
                        $price_data['DAY'],
                        $price_data['PRICE'],
                        $price_data['PRODUCT_ID']
                    ];
                    $a_prices[$price_key] = NULL;
                    unset($a_prices[$price_key]);
                }
            }
            // Сортировка цен каждой детали
            foreach ($parts_recomend as $part_key => $part_data) {
                $parts_recomend[$part_key]['items'] = $this->model_search_parts->dimentionalSort($part_data['items'], 3);
            }
            ksort($parts_recomend);
            $parts_tmp = $parts_recomend;
            $parts_recomend = [];
            foreach ($parts_tmp as $part) {
                $parts_recomend[] = $part;
            }
            unset($parts_tmp);

            // Запрашиваемое
            foreach ($a_prices as $price_key => $price_data) {
                if ($price_data['BRAND'] === $brand && $price_data['AKEY'] === $article) {
                    $part_key = $price_data['BRAND'] . $price_data['AKEY'];
                    if (!isset($parts_request[$part_key]) || empty($parts_request[$part_key])) {
                        $parts_request[$part_key] = [
                            $this->model_search_parts->getNameByBrandId($price_data['BRAND']),
                            $price_data['ARTICLE'],
                            $price_data['ALT_NAME'],
                            '',
                            '2',
                            '',
                            'items' => []
                        ];
                    }
                    $parts_request[$part_key]['items'][] = [
                        $price_data['CODE'],
                        $price_data['AVAILABLE'],
                        $price_data['DAY'],
                        $price_data['PRICE'],
                        $price_data['PRODUCT_ID']
                    ];
                    $a_prices[$price_key] = NULL;
                    unset($a_prices[$price_key]);
                }
            }
            // Сортировка цен каждой детали
            foreach ($parts_request as $part_key => $part_data) {
                $parts_request[$part_key]['items'] = $this->model_search_parts->dimentionalSort($part_data['items'], 3);
            }
            ksort($parts_request);
            $parts_tmp = $parts_request;
            $parts_request = [];
            foreach ($parts_tmp as $part) {
                $parts_request[] = $part;
            }
            unset($parts_tmp);

            // Оригинальные заменители
            foreach ($a_prices as $price_key => $price_data) {
                if ($price_data['BRAND'] === $brand) {
                    $part_key = $price_data['BRAND'] . $price_data['AKEY'];
                    if (!isset($parts_analog_original[$part_key]) || empty($parts_analog_original[$part_key])) {
                        $parts_analog_original[$part_key] = [
                            $this->model_search_parts->getNameByBrandId($price_data['BRAND']),
                            $price_data['ARTICLE'],
                            $price_data['ALT_NAME'],
                            '',
                            '2',
                            '',
                            'items' => []
                        ];
                    }
                    $parts_analog_original[$part_key]['items'][] = [
                        $price_data['CODE'],
                        $price_data['AVAILABLE'],
                        $price_data['DAY'],
                        $price_data['PRICE'],
                        $price_data['PRODUCT_ID']
                    ];
                    $a_prices[$price_key] = NULL;
                    unset($a_prices[$price_key]);
                }
            }
            // Сортировка цен каждой детали
            foreach ($parts_analog_original as $part_key => $part_data) {
                $parts_analog_original[$part_key]['items'] = $this->model_search_parts->dimentionalSort($part_data['items'], 3);
            }
            ksort($parts_analog_original);
            $parts_tmp = $parts_analog_original;
            $parts_analog_original = [];
            foreach ($parts_tmp as $part) {
                $parts_analog_original[] = $part;
            }
            unset($parts_tmp);

            // Заменители
            foreach ($a_prices as $price_key => $price_data) {
                $part_key = $price_data['BRAND'] . $price_data['AKEY'];
                if (!isset($parts_analog[$part_key]) || empty($parts_analog[$part_key])) {
                    $parts_analog[$part_key] = [
                        $this->model_search_parts->getNameByBrandId($price_data['BRAND']),
                        $price_data['ARTICLE'],
                        $price_data['ALT_NAME'],
                        '',
                        '2',
                        '',
                        'items' => []
                    ];
                }
                $parts_analog[$part_key]['items'][] = [
                    $price_data['CODE'],
                    $price_data['AVAILABLE'],
                    $price_data['DAY'],
                    $price_data['PRICE'],
                    $price_data['PRODUCT_ID']
                ];
                $a_prices[$price_key] = NULL;
                unset($a_prices[$price_key]);
            }
            // Сортировка цен каждой детали
            foreach ($parts_analog as $part_key => $part_data) {
                $parts_analog[$part_key]['items'] = $this->model_search_parts->dimentionalSort($part_data['items'], 3);
            }
            ksort($parts_analog);
            $parts_tmp = $parts_analog;
            $parts_analog = [];
            foreach ($parts_tmp as $part) {
                $parts_analog[] = $part;
            }
            unset($parts_tmp);

            /* Формирование кэша */
            $a_result = [];
            $a_result['PARTS'] = [];
            $a_result['PARTS']['fields'] = $part_fields;
            $a_result['PARTS']['parts_recomend'] = [];
            $a_result['PARTS']['parts_recomend']['data'] = $parts_recomend;
            $a_result['PARTS']['parts_request'] = [];
            $a_result['PARTS']['parts_request']['data'] = $parts_request;
            $a_result['PARTS']['parts_analog_original'] = [];
            $a_result['PARTS']['parts_analog_original']['data'] = $parts_analog_original;
            $a_result['PARTS']['parts_analog'] = [];
            $a_result['PARTS']['parts_analog']['data'] = $parts_analog;

            $result = json_encode($a_result, JSON_UNESCAPED_UNICODE);
            $this->logger->rawData('Итоговый набор прайсов после проценки: ' . $result);

            // Занесение данных в кэш
            $this->rediscache->setHash('SEARCH', $cache_name, $result);
            $this->rediscache->setHash('SEARCH', $cache_name . '_TIMER', $this->model_search_parts->setPriceDate() + 24 * 60 * 60);
            $this->logger->info('Данные были занесены в кэш под именем: ' . $cache_name);
        }
        $this->logger->warning('Ключ активного поиска: ' . $search_key);
        $this->model_search_prices->updateSearchStatus($search_key, 2);

        $this->logger->rawData('Набор прайсов после форматирования: ' . $result);
        $this->logger->notice("Завершение   поиска ");
        $this->response->setOutput($result);

        // Штатное сохранение и закрытие файла
        $this->logger->close();
    }

    public function analogJson() {
        // JSON - не содержит прайсы только поля ['Бренд', 'Бренд ID', 'Номер детали', 'Описание детали']
        $analog_data[] = array( 'Tong yang',123, '11427512300','АВТОЗАПЧАСТЬ/FILTRON {BMW} ФИЛЬТР МАСЛЯНЫЙ');
        $analog_data[] = array( 'BMW',143, '1148712300','Выхлопная труба');
        $analog_data[] = array( 'Mеrsedez',423, '11427512300','Еще кактой-то фильтр');
        $this->response->setOutput(json_encode(['analog_data' => $analog_data]));
    }

    public function originalPriceJson() {

        $items = array();
        $items[] = array('emx', '50', 8, 365, '3cc00dac579dfdd64d5bffa65b6d0d83');
        $items[] = array('emx', '54', 5, 395, 'dc1798844d4dd86c3467d19400a7a30c');
        $items[] = array('amt', '54', 5, 395, '162c52eccfafe917251e2757ab946bd8');
        $originalPriceData[] = array( 'BMW',143,'11427512300','Фильтр масляный E39 M52 (картридж) BMW X5', 'http://77.120.224.229/images/56/46774.jpg', 1, 1028352, 'items' => $items);
        $originalPriceData[] = array( 'Tong yang',123,'11427512300','Фильтр масляный E39 M52 (картридж) BMW X5', 'http://77.120.224.229/images/56/46774.jpg', 1, 1028352, 'items' => $items);
        $originalPriceData[] = array( 'Mеrsedez',423,'11427512300','Фильтр масляный E39 M52 (картридж) BMW X5', 'http://77.120.224.229/images/56/46774.jpg', 1, 1028352, 'items' => $items);
        $this->response->setOutput(json_encode(['originalPriceData' => $originalPriceData]));
    }


    public function analogPriceJson() {

        $items = array();
        $items[] = array('int', '65', 7, 65, '3cc00dac579dfdd64d5bffa65b6d0d83');
        $items[] = array('lex', '34', 7, 95, 'dc1798844d4dd86c3467d19400a7a30c');
        $items[] = array('poo', '14', 7, 95, '162c52eccfafe917251e2757ab946bd8');
        $analogPriceData[] = array( 'BMW',143,'11427512300','Фильтр масляный E39 M52 (картридж) BMW X5', 'http://77.120.224.229/images/56/46774.jpg', 1, 1028352, 'items' => $items);
        $analogPriceData[] = array( 'Tong yang',123,'11427512300','Фильтр масляный E39 M52 (картридж) BMW X5', 'http://77.120.224.229/images/56/46774.jpg', 1, 1028352, 'items' => $items);
        $analogPriceData[] = array( 'Mеrsedez',423,'11427512300','Фильтр масляный E39 M52 (картридж) BMW X5', 'http://77.120.224.229/images/56/46774.jpg', 1, 1028352, 'items' => $items);
        $this->response->setOutput(json_encode(['$analogPriceData' =>  $analogPriceData]));
    }

    public function finalPriceJson() {

        $finalData = array();
        $finalData[]= analogJson();
        $finalData[]= originalPriceJson();
        $finalData[]= analogPriceJson();
        $this->response->setOutput(json_encode(['$finalData' => $finalData]));
    }


}