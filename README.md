# JSON Transform API


## Requirements
* PHP >= 7.4
* [Composer](https://getcomposer.org/).

## Installation (Non Docker)

1. Clone the repo manually by running the following command in the terminal:

        git clone https://github.com/dungnacmc/json-transform.git
        
2. Install dependencies by running the following commands in the terminal:

        cd json-transform
        composer install

3. Generate .env file and create application key:

        php -r "copy('.env.example', '.env');"
        php artisan key:generate

4. Setup local directory permissions:
   
       Local user to own all the directories and files:
       
       sudo chown -R my-user:www-data /path/to/your/laravel/root/directory
       
       Both local user and the webserver permissions:
       
       sudo find /path/to/your/laravel/root/directory -type f -exec chmod 664 {} \;
       sudo find /path/to/your/laravel/root/directory -type d -exec chmod 775 {} \;
       
       Give the webserver the rights to read and write to storage and cache:
       
       sudo chgrp -R www-data storage bootstrap/cache
       sudo chmod -R ug+rwx storage bootstrap/cache

5. Run test:
   
       php artisan cache:clear (clear cache before run test)
       php artisan test
       vendor/bin/phpunit --coverage-html reports (require Xdebug extension)

Run in browser: http://localhost

## Installation (Docker)		
### Prerequisites
* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)

1. Clone the repo manually by running the following command in the terminal:
   
        git clone https://github.com/dungnacmc/json-transform.git

2. Build (first time):
  
        docker-compose build
        
3. Run containers:

        docker-compose up -d

4. Install dependencies:

        docker-compose exec php composer install
     
5. Generate .env file and create application key:

        docker-compose exec php php -r "copy('.env.example', '.env');"
        docker-compose exec php php artisan key:generate

6. Other commands:
   
        docker-compose exec php php artisan cache:clear (clear cache before run test)
   
        docker-compose exec php php artisan test (run test)
   
        docker-compose exec php vendor/bin/phpunit --coverage-html reports (run code coverage)

        docker-compose stop (stop running containers)
   
        docker-compose down (remove running containers)


Run in browser: https://localhost (click on Advanced then Proceed to localhost (unsafe) )

## API Document

Refer to file docs/API.md
