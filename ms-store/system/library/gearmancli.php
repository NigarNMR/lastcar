<?php
class GearmanCli {
    private $client;
    
    public function __construct() {
        $this->client = new GearmanClient();
    }
    
    public function addServer($address = '') {
        if (empty($address)) {
            $this->client->addServer();
        } else {
            $this->client->addServer($address);
        }
    }
    
    public function setCallbackCreated($callback_function) {
        $this->client->setCreatedCallback($callback_function);
    }
    
    public function setCallbackData($callback_function) {
        $this->client->setDataCallback($callback_function);
    }
    
    public function setCallbackStatus($callback_function) {
        $this->client->setStatusCallback($callback_function);
    }
    
    public function setCallbackCompleted($callback_function) {
        $this->client->setCompletedCallback($callback_function);
    }
    
    public function setCallbackFail($callback_function) {
        $this->client->setFailCallback($callback_function);
    }
    
    public function addTaskHigh($function_name, $data, $params) {
        $this->client->addTaskHigh($function_name, $data, $params);
    }
    
    public function runTasks() {
        $this->client->runTasks();
    }
}