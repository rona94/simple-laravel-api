## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

## Installation
Run to install composer.
```
composer install
```

## Setup
Create `.env` and copy `.env.example` on the same directory
```
cp .env.example .env
```

Run this command
```
php artisan jwt:secret
```

## Database
Run to migrate data in database. Needed to create `laravel` database before running this code. 
```
php artisan migrate
```

## Development
Start the app in local development environment. You can open the project using this link: `http://localhost:8080/`
```
php artisan serve
```
