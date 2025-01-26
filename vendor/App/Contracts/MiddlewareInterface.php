<?php

namespace vendor\App\Contracts;

interface MiddlewareInterface
{
    public function process(): bool;
}