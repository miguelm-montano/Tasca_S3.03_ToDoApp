<?php

class TaskStorage {

    private $filePath;
    private $data;

    public function __construct() {

        $this->filePath = __DIR__ . '/storage/data.json';
        $this->loadData();
    }

    private function loadData() {

        if (file_exists($this->filePath)) {
            $json = file_get_contents($this->filePath);
            $this->data = json_decode($json, true);
            if (!$this->data) {
                $this->data = ['users' => [], 'tasks' => []];
            }
        } else {
            $this->data = ['users' => [], 'tasks' => []];
        }
    }

    public function saveData() {
        
        file_put_contents($this->filePath, json_encode($this->data, JSON_PRETTY_PRINT));
    }

    public function getData() {

        return $this->data;
    }

    public function setData($data) {

         $this->data = $data;
        $this->saveData();
    }
}
?>