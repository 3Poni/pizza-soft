<?php

namespace vendor\Database\Contracts;

interface DriverContract {
    public function getAll();
    public function getById($id);
    public function create($item);
    public function update($id, $upd_field, $upd_value);
    public function delete($id);
}