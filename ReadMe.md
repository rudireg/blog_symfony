### Deploy Docker
`docker-compose -f docker-compose.dev.yml up -d`

### Install Composer
`docker-compose -f docker-compose.dev.yml run --rm php composer install`

### Create MySql database
You must create database with name: `test_news`

### To create tables in database run the following command:
`docker-compose -f docker-compose.dev.yml run --rm php bin/console app:create_tables`

Here is an admin panel http://localhost:10503/admin
Login: admin@ya.ru
Password: admin





