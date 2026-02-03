<?php

class UserStorage
{
    private $filePath;
    private $data;        

    public function __construct()
    {
        $this->filePath = ROOT_PATH . '/storage/storage.json';
        $this->loadData();
    }

    private function loadData()
    {
        if (file_exists($this->filePath)) {
            $json = file_get_contents($this->filePath);
            $this->data = json_decode($json, true);
            if (!$this->data || !isset($this->data['users'])) {
                $this->data = ['users' => []];
            }
        } else {
            $this->data = ['users' => []];
            $this->saveData();
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        $this->saveData();
    }

    private function saveData()
    {
        file_put_contents(
            $this->filePath,
            json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
