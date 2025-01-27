<?php

/**
 *  Путь к приложению
 */
const PROJECT_PATH = __DIR__;

/**
 *  Значение "1" включает подробную информацию об ошибках/исключениях
 *
 *  Значение "0" выключает демонстрацию ошибок, оставляя только "500 Server ERROR",
 *
 */
const ERROR_HANDLER = 0;

/**
 *  Путь к директории с логами ошибок
 *
 * */
const ERROR_LOG_PATH = PROJECT_PATH . '/logs/';

/**
 *
 * Ключ для аутентификации
 *
 * */
const AUTH_KEY = '123456';

/**
 *  Конфигурация базы данных
 */
defined("DBPATH") or define("DBPATH", "/database/");
defined("DBDRIVER") or define("DBDRIVER", "file-json");




