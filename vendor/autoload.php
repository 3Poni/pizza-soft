<?php

spl_autoload_register(function ($class){
    $class = str_replace("\\", '/', $class);
    $class = str_replace('\\', DIRECTORY_SEPARATOR, '..\\'.$class.'.php');
    include $class;
});