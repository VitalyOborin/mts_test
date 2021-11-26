# Тестовое задание shop.mts.ru
## Постановка задачи<a name="task"></a>

_Сделать минималистичное REST API интернет-магазина на Symfony_

_Есть таблица товаров (sku, количество). Написать экшн создания заказа, который будет получать на вход состав корзины, формировать новый заказ в БД и списывать остатки из таблицы товаров._

_Схема формирования заказов на усмотрение исполнителя._

## Установка и запуск с docker<a name="docker"></a>
```bash
git clone https://github.com/VitalyOborin/mts_test.git
cd mts_test
docker-compose up -d --build
```
При этом варианте запуска сервис будет доступен только по HTTP, так как не стал заморачиваться с SSL в nginx. 
Также не потребуется запускать команды для создания и заполнения БД, 
они выполняются при сборке.

## Работа с API<a name="api"></a>
https://127.0.0.1:8000/ - вывод ID доступных товаров (метод только GET)

https://127.0.0.1:8000/order/create - отправка заказа (метод только POST)

**При запуске через docker следует использовать протокол http.**

Пример данных для отправки в заказ через POST запрос (можно отправить через Postman или cURL)

`{"email":"email@domain.com","products":{"1":2,"3":4}}`

Команда для отправки через cURL

```bash
curl -k -X POST https://127.0.0.1:8000/order/create -d "{\"email\":\"email@domain.com\",\"products\":{\"1\":1,\"3\":4}}" -H "Content-Type: application/json"
```

в products в качестве ключа используется ID товара в базе данных, 
в качестве значения - количество товаров для добавления в заказ

Пример ответа API на добавление нового заказа:

`{"order_id":24}`

order_id - номер заказа

Списание остатков одновременно с созданием заказа