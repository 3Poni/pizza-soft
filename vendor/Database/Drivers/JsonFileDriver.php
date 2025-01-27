<?php

namespace vendor\Database\Drivers;


use vendor\Database\Contracts\DriverContract;

class JsonFileDriver implements DriverContract
{
    private $filePath;

    private string $fileName;
    private string $primary_key;
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        if(!defined("DBPATH")) throw new \Exception("DBPATH is not defined");
        $this->filePath = PROJECT_PATH . DBPATH . $this->fileName . '.json';
        // Ensure the file exists
        if (!is_dir(PROJECT_PATH . DBPATH)) {
            mkdir(PROJECT_PATH . DBPATH, 0777);
        }
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]), LOCK_EX);
        }
        $this->primary_key = $this->get_primary_key_name() . '_id';
    }

    private function readData()
    {
        $json = file_get_contents($this->filePath);
        return json_decode($json, true);
    }

    private function writeData($data)
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    }

    public function getAll()
    {
        return $this->readData();
    }

    public function getById($id)
    {
        $data = $this->readData();
        foreach ($data as $item) {
            if ($item[$this->primary_key] == $id) {
                return $item;
            }
        }
        return null;
    }

    public function create($item)
    {
        $data = $this->readData();
        // Generate a unique ID
        $id = self::get_primary_key($data);
        $item[$this->primary_key] = $id;

        $data[$id] = $item;
        $this->writeData($data);
        return $item;
    }

    private function get_primary_key_name(): string
    {
        return substr_replace($this->fileName, '', -1);
    }

    public static function get_primary_key($data, $min_length = 3, $max_length = 15): string
    {
        $existing_keys = array_keys($data);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);

        do {
            $length = rand($min_length, $max_length);
            $key = '';
            for ($i = 0; $i < $length; $i++) {
                $key .= $characters[rand(0,  $characters_length - 1)];
            }
        } while (in_array($key, $existing_keys));

        return $key;
    }

    public function update($id, $upd_field, $upd_value)
    {
        $data = $this->readData();

        foreach ($data as &$item) {
            if ($item[$this->primary_key] == $id) {
                $item[$upd_field] = $upd_value;
                $this->writeData($data);
                return $item;
            }
        }
        return null;
    }

    public function delete($id)
    {
        $data = $this->readData();
        foreach ($data as $key => $item) {
            if ($item[$this->primary_key] == $id) {
                unset($data[$key]);
                $this->writeData($data);
                return true;
            }
        }
        return false;
    }

}