## Установка

Docker контейнеры должны работать в среде Linux

Скачать репозиторий

```bash
git clone https://github.com/3Poni/pizza-soft.git .
```

Далее скопировать config.example.php в рабочий config.php

Linux
```bash
cp config.example.php config.php
```

Далее запустим docker (команду выполнять из корня проекта)

```bash
docker-compose build
docker-compose up
```

После чего приложение будет доступно по адресу:

```bash
http://localhost:8000
```

## Использование

Обязательно отправлять 2 заголовка:

"Content-type: application/json"

"Accept: application/json"

Опционально:
"X-Auth-Key: key_example"

Описание ресурсов

|             Ресурс              | Метод  |                  Описание                   |    Ключ    |
|:-------------------------------:|:------:|:-------------------------------------------:|:----------:|
| /api/v1/orders          |  GET   |             Получить все заказы             | X-Auth-Key |
| /api/v1/orders/{id}       |  GET   |         Получить заказ по order_id          |    Нет     |
| /api/v1/orders/?done={boolean}  |  GET   | Получить все заказы с фильтром done 1 или 0 | X-Auth-Key |
| /api/v1/orders          |  POST  |            Добавить новый заказ             |    Нет     |
| /api/v1/orders/{order_id}/items |  POST  |           Добавить items в заказ            |    Нет     |
| /api/v1/orders/{order_id}/done  |  POST  |       Пометить заказ как выполненный        | X-Auth-Key |

Пример ответа:
```
"status": "success",
    "message": "",
    "data": {
        "order_id": "aac",
        "done": true,
        "items": [
            1,
            2,
            3,
        ]
    },
    "errors": []
```

Данные в теле запроса:

|    Ключ    | Значение  |                                           Описание                                           |
|:----------:|:---------:|:--------------------------------------------------------------------------------------------:|
|  "items"   |  строка   |   Массив из чисел (длина от 1 до 5000), которые представляют собой номера товаров в заказе   |
| "order_id" |  строка   |                       Уникальный id заказа (длина от 3 до 15 символов)                       |
|   "done"   | булев тип |                              Готовность заказа (true или false)                              |


Пример тела запроса при использовании методов POST, ресурс /api/v1/orders

```bash
{
        "items": "[1,2,3,4]",
}
```
