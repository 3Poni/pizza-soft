<?php

namespace vendor\Database;

use Exception;
use vendor\Database\Contracts\DriverContract;
use vendor\Database\Drivers\JsonFileDriver;
use vendor\Database\Drivers\MySqlDriver;

class Model implements DriverContract
{
    public DriverContract $driver;
    public static string $table;
    public function __construct()
    {
        $this->defineTable();
        $this->defineDriver();
    }

    private function defineTable()
    {
        try {
            self::$table = static::$table;
        }catch (Exception $e){
            throw new \Exception('Model table is not defined');
        }
    }
    private function defineDriver()
    {
        if(!defined("DBDRIVER")) throw new Exception('DBDRIVER not defined');

        switch (DBDRIVER) {
            case 'mysql':
                $this->driver = new MySqlDriver(self::$table);
                break;
            case 'file-json':
                $this->driver = new JsonFileDriver(self::$table);
                break;
            default:
                throw new Exception('DBDRIVER is not supported');
        }
    }

    public function getAll()
    {
        return $this->driver->getAll();
    }

    public function getById($id)
    {
        return $this->driver->getById($id);
    }

    public function create($item)
    {
        return $this->driver->create($item);
    }

    public function update($id, $upd_field, $upd_value)
    {
        return $this->driver->update($id, $upd_field, $upd_value);
    }

    public function filterBy($field, $value)
    {
        $all_items = $this->driver->getAll();
        if($all_items !== null) {
            foreach ($all_items as $key => $item) {
                if($item[$field] != $value) unset($all_items[$key]);
            }
        }
        return $all_items;
    }

    public function delete($id)
    {
        return $this->driver->delete($id);
    }
}