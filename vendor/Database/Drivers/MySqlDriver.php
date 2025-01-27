<?php

namespace vendor\Database\Drivers;

use PDO;
use PDOStatement;
use vendor\Database\Contracts\DriverContract;

class MySqlDriver implements DriverContract
{
    private PDO $pdo;
    private string $table;
    private string $id;
    public function __construct(string $table)
    {
        $this->table = $table;
        $this->id = substr_replace($this->table, '', -1);
        $this->pdo = new PDO('' . DBDRIVER . ':host=' . DBHOST . ';dbname=' . DBNAME . ';charset=utf8', '' . DBUSER . '', '' . DBPASS . '', [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    private function select(string $query): ?array
    {
        return $this->pdo->query($query)->fetchAll(PDO::FETCH_UNIQUE);
    }

    private function query(string $query, $params = [])
    {
        $query = $this->pdo->prepare($query);
        $this->pdo->beginTransaction();
        $query->execute($params);
        $this->pdo->commit();
        $fetched = $query->fetch(PDO::FETCH_ASSOC);

        return empty($fetched) ? null : $fetched;
    }

    public function getAll()
    {
        return $this->select("SELECT {$this->table}.{$this->id}_id as id, {$this->table}.* FROM {$this->table}");
    }

    public function getById($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE {$this->id}_id = ?", [$id]);
    }

    public function create($item)
    {
        $data = $this->getAll();
        $item["{$this->id}_id"] = JsonFileDriver::get_primary_key($data);
        // Не хорошо, но выбора не было, нужно было сделать быстро
        $values = [];
        $fields = [];
        $q_marks = [];
        foreach($item as $key => $value){
            $fields[] = $key;
            $values[] = $this->convert_value($value);
            $q_marks[] = '?';
        }
        $q_marks = implode(', ', $q_marks);
        $fields_string = implode(', ', $fields);

        $this->query("INSERT INTO {$this->table} ({$fields_string}) VALUES ({$q_marks})", $values);

        return $item;
    }

    private function convert_value($value)
    {
        if(is_array($value)){
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        if(is_bool($value)){
            return $value ? 1 : 0;
        }
        return $value;
    }

    public function update($id, $upd_field, $upd_value)
    {
        $upd_value = $this->convert_value($upd_value);

        return $this->query("UPDATE {$this->table} SET {$upd_field}=? WHERE {$this->id}_id=?",[$upd_value, $id]);
    }
}