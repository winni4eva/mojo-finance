## Mojo Finance API
A simple laravel api powered by Sanctum that allows for customer account creation and transfer of funds between accounts

## Setup Default
    - Create a mysql database eg. mojo_finance
    - Update .env with database name and credentials
    - Run the seeder with command **php artisan db:seed**
    - Follow the api doc links to get an api token to start making requests

## Setup with Sail
    - Install docker
    - Run command from project root => ./vendor/bin/sail up
## Api Docs

### Auth Endpoints
**[Authentication Api](https://documenter.getpostman.com/view/9364284/2s93JnV7Jq/)**

### Account Endpoints
**[Account Api](https://documenter.getpostman.com/view/9364284/2s93JnUSGf/)**

### Transaction Endpoints
**[Transaction Api](https://documenter.getpostman.com/view/9364284/2s93JnV7Jr/)**
