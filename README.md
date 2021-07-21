# JSON Transform API


## Requirements
* PHP >= 7.4
* [Composer](https://getcomposer.org/).

## Installation (Non Docker)

1. Clone the repo manually by running the following commands in the terminal:

		git clone https://github.com/dungnacmc/json-transform.git
		cd json-transform
		composer install
		php -r "copy('.env.example', '.env');"
		php artisan key:generate

## Installation (Docker)		
### Prerequisites
* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)

1. Build and run:

		docker-compose up --build
		
2. Stop running containers:

		docker-compose stop
		
3. Remove running containers:

		docker-compose down
		
Run in browser: https://localhost
