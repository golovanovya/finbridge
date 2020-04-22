<p align="center">
    <h1 align="center">Findbridge test job</h1>
    <br>
</p>

#TECHNICAL TASK

Данное тестовое задание не оплачивается и преследует следующие цели:
 Анализ реальных знаний и навыков кандидата, оценка его компетентности.
 Понимание направления мыслей по поставленной задаче.
 Оценка темпа и производительности кандидата.
В рамках тестового задания необходимо реализовать 2 микросервиса:
1. Эмулятор платежной системы:
Рандомно генерирует от 1 до 10 запросов в формате:
{ id: идентификатор транзакции sum: сумма (от 10р. до 500р.)
commision: коммиссия (от 0,5% до 2%) order_number: идентификатор
клиента (от 1 до 20) } Сохраняет данные локально.
Делает цифровую подпись (механизм на усмотрение соискателя) Отправляет
пакетом с интервалом 20 секунд на второй сервис Повторяет циклично
2. Эмулятор приема платежа:
Получает запрос
Проверяет подпись
Парсит пакет данных и добавляет данные в очередь обработки Высчитывает
сумму с коммиссией Сохраняет в локальную базу в формате:
{ id: идентификатор транзакции user_id: идентификатор клиента sum: сумма с
учетом коммиссии } и добавляет запись в таблицу user_wallet либо
наращивает сумму в записи в формате: { user_id: идентификатор
пользователя sum: сумма на счету }
Требования:
 - использование PHP
 - использование любого web framework
 - необходимо добавить описание запуска сервисов
 - необходимо выложить подготовленное задание на
https://gitlab.com

REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.2


INSTALLATION

### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist
    
Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install    
    
Start the container

    docker-compose up -d
    
You can then access the application through the following URL:

    http://127.0.0.1:8000

Start listen new tasks in queue

    docker-compose run --rm php php yii queue/listen

Add job to your crontab for running every 20 seconds

    * * * * * docker-compose run --rm php php yii client/run
    * * * * * (sleep 20 ; docker-compose run --rm php php yii client/run)
    * * * * * (sleep 40 ; docker-compose run --rm php php yii client/run)

**NOTES:** 
- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches


CONFIGURATION
-------------

### Configuration Environments

See comments in .nev.example file

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.
