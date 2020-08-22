## About Project

This project contains Restful Apis(in latest Laravel 7) for Creating, Updating, Fetching and deleting departments and employees of a XYZ Company

## Initial Setup Steps
PHP 7.2 or higher and Mysql Version 5.7 or higher are required.
 * Clone Repo `git clone https://github.com/rahuldas11694/employee-dept.git`
 * Run `composer update`

 ## Config in .env file
```sh
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=emp_dept [Name of the database]
DB_USERNAME=root [Mysql username]
DB_PASSWORD=password [Mysql password]
```
 * After above .env db config Run migration file `php artisan migrate` This will create tables in your database
 * Run Laravel server `php artisan serve` and it will start the local server on port 8000 [http://localhost:8000]




