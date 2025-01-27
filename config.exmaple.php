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
 *
 *  2 драйвера БД: file-json - для json Файла, mysql - для mysql
 *
 *  Для mysql также необходимо установить:
 *  DBHOST - хост бд (пример: "localhost")
 *  DBNAME - имя бд (пример: "test")
 *  DBUSER - юзер в бд (пример: "root")
 *  DBPASS - пароль в бд (пример: "123")
 */
defined("DBPATH") or define("DBPATH", "/database/");
defined("DBDRIVER") or define("DBDRIVER", "file-json");




