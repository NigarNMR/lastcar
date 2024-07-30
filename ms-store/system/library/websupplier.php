<?php
class Websupplier {
    /**
     * @var object Реализация сервиса для веб-поставщика 
     */
    private $adaptor;
    
    /**
     * Выбор реализации для указанного веб-поставщика
     * 
     * @param string $adaptor Реализация
     */
    public function selectSupplier($adaptor) {
        $class = 'Websuppliers\\' . $adaptor;
        
        if (class_exists($class)) {
            $this->adaptor = new $class;
        }
    }
    
    /**
     * Установка соединения с сервисом веб-поставщика
     * 
     * @param string $username Идентификатор пользователя
     * @param string $password Пароль пользователя
     * @return type
     */
    public function connect($username, $password) {
        return $this->adaptor->connect($username, $password);
    }
    
    /**
     * Возвращение данных о состоянии сервиса
     * 
     * @return type
     */
    public function ping() {
        return $this->adaptor->ping();
    }
    
    /**
     * Поиск запчастей по серийному номеру
     * 
     * @param string $article Серийный номер
     * @return array Результирующий массив ['Код поиска','Данные']
     */
    public function searchParts($article) {
        return $this->adaptor->searchParts($article);
    }
    
    /**
     * Поиск прайсов по серийному номеру и марке, включая аналоги
     * 
     * @param string $article Серийный номер
     * @param string $brand Марка
     * @return array Результирующий массив ['Код поиска','Данные']
     */
    public function searchPricesAll($article, $brand) {
        return $this->adaptor->searchPricesAll($article, $brand);
    }
    
    /**
     * Поиск прайсов по серийному номеру и марке, исключая аналоги
     * 
     * @param string $article Серийный номер
     * @param string $brand Марка
     * @return array Результирующий массив ['Код поиска','Данные']
     */
    public function searchPricesRequested($article, $brand) {
        return $this->adaptor->searchPricesRequested($article, $brand);
    }
}