<?php
class Logger {
    /**
     * @var object Хэндлер соединения с файлом
     */
    private $handle;

    /**
     * Закрытие хэндлера при инициализации логгера
     */
    public function __construct() {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
    }
    
    /**
     * Открытие файла логов
     * 
     * @param string $filename Имя файла
     * @param string $prefix Префикс к имени
     * @param string $extension Разрешение файла
     */
    public function open($filename, $prefix = 'log_', $extension = '.txt') {
        $this->handle = fopen(DIR_LOGS . $prefix . $filename . $extension, 'a');
    }
    
    /**
     * Запись обычного сообщения, без пометок
     * 
     * @param string $message Сообщение
     */
    public function write($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись сообщения чрезв. важности
     * 
     * @param string $message Сообщение
     */
    public function emergency($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[EMERGENCY]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись сообщения тревоги
     * 
     * @param string $message Сообщение
     */
    public function alert($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[ALERT]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись сообщения критического характера
     * 
     * @param string $message Сообщение
     */
    public function critical($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[CRITICAL]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись сообщения ошибки
     * 
     * @param string $message Сообщение
     */
    public function error($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[ERROR]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись сообщения предупреждающего характера
     * 
     * @param string $message Сообщение
     */
    public function warning($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[WARNING]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись сообщения - примечания
     * 
     * @param string $message Сообщение
     */
    public function notice($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[NOTICE]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись сообщения информационного характера
     * 
     * @param string $message Сообщение
     */
    public function info($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[INFO]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись сообщения отладки
     * 
     * @param string $message Сообщение
     */
    public function debug($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[DEBUG]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Запись содержащая данные
     * 
     * @param string $message Сообщение
     */
    public function rawData($message) {
        fwrite($this->handle, date('[Y-m-d G:i:s.u]') . ' ' . '[RAW DATA]' . ' - ' . print_r($message, true) . "\n");
    }
    
    /**
     * Закрытие и сохранение файла логов
     */
    public function close() {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
    }
    
    /**
     * Закрытие и сохранение файла логов по уничтожению экземпляра класса
     */
    public function __destruct() {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
    }
}