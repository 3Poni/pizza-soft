<?php

namespace vendor\Validator;

use vendor\Response\Response;

class Validator
{
    private $errors = [];
    private array $rules_array = [];

    public function validate(array $data, array $rules)
    {
        foreach ($rules as $field => $rule) {
            $this->rules_array = explode('|', $rule);
            foreach ($this->rules_array as $single_rule) {
                $this->applyRule($field, $single_rule, $data);
                if(count($this->errors) > 0) (new Response())->setErrors($this->errors)->setStatusCode(400)->send();
            }
        }

        return empty($this->errors);
    }

    private function all($elems, $predicate) {
        foreach ($elems as $elem) {
            if (!call_user_func($predicate, $elem)) {
                return false;
            }
        }

        return true;
    }

    private function applyRule($field, $rule, $data)
    {
        $value = $data[$field] ?? null;

        if(array_search('array',$this->rules_array)) {
            if(is_string($value)) {
                $value = json_decode($value, true);
            }
        }

        if (strpos($rule, ':') !== false) {
            list($rule, $param) = explode(':', $rule);
        }

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->errors[$field][] = "The $field field is required.";
                }
                break;

            case 'array':
                if (!is_array($value)) {
                    $this->errors[$field][] = "The $field field must be an array.";
                }
                break;

            case 'min':
                if (count($value) < $param) {
                    $this->errors[$field][] = "The $field field must be at least $param characters.";
                }
                break;

            case 'max':
                if (count($value) > $param) {
                    $this->errors[$field][] = "The $field field must not exceed $param characters.";
                }
                break;

            case 'only_int':
                if (!$this->all($value,'is_int')) {
                    $this->errors[$field][] = "The $field must contain only integers.";
                }
                break;

        }
    }
}
