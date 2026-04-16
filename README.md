# Ajeer Technical Challenge

## Setup
1. `git clone https://github.com/ms-dev97/Ajeer-Technical-Challenge.git` and cd to repo
2. `composer install`
3. `cp .env.example .env && php artisan key:generate`
4. Add Mysql credential in `.env`
5. `php artisan migrate --seed`
6. `php artisan serve`