<?php
class RedisCache {
    /**
     * @var int Время протухания данных 
     */
	private $expire;
    /**
     * @var object Хэндлер соединения с Redis
     */
	private $cache;

    /**
     * Создание подключения к Redis и выбор базы
     * 
     * @param int $expire Время протухания данных
     */
	public function __construct($expire = 3600) {
		$this->expire = $expire;

		$this->cache = new \Redis();
		$this->cache->pconnect(REDIS_ADDRESS, REDIS_PORT);
        $this->cache->select(REDIS_CLI_ID);
	}

    /**
     * Получение данных по ключу
     * 
     * @param string $key Ключ
     * @return string|bool Данные|Ключ ошибки
     */
	public function get($key) {
		return $this->cache->get($key);
	}

    /**
     * Занесение данных под указанным ключом
     * 
     * @param string $key Ключ
     * @param string $value Данные
     * @return bool Параметр завершения операции
     */
	public function set($key, $value) {
		return $this->cache->set($key, $value);
	}
    
    /**
     * Удаление данных
     * 
     * @param type $key
     * @return int|bool Количество удаленных элементов|Код удаления
     */
	public function delete($key) {
		return $this->cache->delete($key);
	}
    
    /**
     * Проверка существования элемента с данным ключом
     * 
     * @param string $key Ключ поиска
     * @return bool Результат поиска
     */
    public function exists($key) {
        return $this->cache->exists($key);
    }
    
    /**
     * Получение данных по ключу из указанного хэша
     * 
     * @param string $key Ключ
     * @param string $hash_key Ключ хэша
     * @return string|bool Данные|Ключ ошибки
     */
    public function getHash($key, $hash_key) {
        return $this->cache->hGet($key, $hash_key);
    }
    
    /**
     * Занесение данных в указанный хэш под ключом
     * 
     * @param string $key Ключ
     * @param string $hash_key Ключ хэша
     * @param string $value Данные
     * @return bool Параметр завершения операции
     */
    public function setHash($key, $hash_key, $value) {
        return $this->cache->hSet($key, $hash_key, $value);
    }
    
    /**
     * Удаление данных из хэша
     * 
     * @param string $key Ключ
     * @param string $hash_key Ключ хэша
     * @return int|bool Количество удаленных элементов|Код удаления
     */
    public function deleteHash($key, $hash_key) {
        return $this->cache->hDel($key, $hash_key);
    }
    
    /**
     * Проверка существования элемента с данным ключом в указанном хэше
     * 
     * @param string $key Ключ
     * @param string $hash_key Ключ хэша
     * @return bool Результат поиска
     */
    public function existsHash($key, $hash_key) {
        return $this->cache->hExists($key, $hash_key);
    }
    
    /**
     * Сброс данных Redis в текущей БД
     */
    public function resetDb() {
        $this->cache->flushDb();
    }
}