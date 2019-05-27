### Развернуть докер.
`docker-compose -f docker-compose.dev.yml up -d`
### Установить приложения через Composer
`docker-compose -f docker-compose.dev.yml run --rm php composer install`
### Развернуть Базу данных (MySql)
Создать базу данных mysql под именем `test_news`

В файле /blog/symfony/src/Command/PdoCommand.php
находится скрипт создающий БД/
Для запуска установки докера docker-compose -f docker-compose.dev.yml run --rm php bin/console app:create_tables

Админка по адресу http://localhost:10503/admin
логин admin@ya.ru
пароль admin





