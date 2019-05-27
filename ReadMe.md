### Развернуть проект.
`docker-compose -f docker-compose.dev.yml up -d`
### Установить приложения через Composer
`docker-compose -f docker-compose.dev.yml run --rm php composer install`
### Развернуть Базу данных (Postgres)

Создать базу данных postgres под именем `restapi`

`docker-compose -f docker-compose.dev.yml run --rm php bin/console doctrine:migrations:migrate`

## Функционал
Реализована функциональность загрузки изображений (jpeg, png, gif):
- **POST метод** Возможность загружать несколько файлов.
- **POST метод** Возможность принимать multipart/form-data запросы.
- **POST метод** Возможность принимать JSON запросы с BASE64 закодированными изображениями.
- **POST метод** Возможность загружать изображения по заданному URL

Путь для API запросв

`http://localhost:10501/api/upload`

_На странице http://localhost:10501 можно выполнить тестовые загрузки._


Для загрузки **multipart/form-data** форма должна быть примерно такой

`<form enctype="multipart/form-data" action="/api/upload" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
            Отправить этот файл: <input name="userfile" type="file">
            <br>
            <input type="submit" value="Отправить файл">
        </form>`
        
Для загрузки нескольких файлов, использовать XMLHttpRequest.

Для загрузки json base64 кодированных изображений использвать метод POST с передачей тела документа в зпросе.

Для загрузки изображения по URL следует на API адрес передать POST запрос с параметром

`url=http://domain/path/to/image.jpg`

Для просмотра списка изображений выполнить запрос
`http://localhost:10501/api/get/image` 

Для просмотра списка ошибок выполнить запрос
`http://localhost:10501/api/get/error`

Для удаления изображения выполнить запрос
`http://localhost:10501/api/delete/22`

где 22 - это ID изображения.





