## Mojo Finance API
A simple laravel api powered by Sanctum that allows for customer account creation and transfer of funds between accounts

## Setup Default
    - You should have php, mysql setup locally
    - Create a mysql database eg. mojo_finance
    - Update .env with database name and credentials
    - Update .env with email config
    - Run the seeder with command **php artisan db:seed**
    - Follow the api doc links to get an api token to start making requests

## Setup with Sail
    - Install docker
    - Start docker
    - Update .env with database name and credentials
    - Update .env with email config
    - Run command from project root => ./vendor/bin/sail artisan db:migrate
    - Run command from project root => ./vendor/bin/sail artisan db:seed
    - Run command from project root => ./vendor/bin/sail up

## Jobs (Horizon - Redis)
    - Run command from project root => ./vendor/bin/sail artisan horizon
    - Visit http://localhost/horizon to access the dashboard

## Nova 
    - Run command from project root => ./vendor/bin/sail npm i
    - Run command from project root => ./vendor/bin/sail npm run dev
    - Run command from project root => ./vendor/bin/sail artisan nova:install
    - Run comman from project root => ./vendor/bin/sail artisan migrate
    - Visit dashboard => http://localhost/nova
    - To setup user run command => ./vendor/bin/sail artisan nova:user


## Api Docs

### **[Authentication Api](https://documenter.getpostman.com/view/9364284/2s93JnV7Jq/)**

### **[Account Api](https://documenter.getpostman.com/view/9364284/2s93JnUSGf/)**

### **[Transaction Api](https://documenter.getpostman.com/view/9364284/2s93JnV7Jr/)**
