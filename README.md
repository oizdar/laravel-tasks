## How to run

* start db & redis: `docker compose up`

* install database with all migrations`php artisan migrate:fresh`

* start local server: `php artisan serve`

* create admin user: `php artisan orchid:admin`

* create passport ID Keys: `php artisan passport:keys`

* generate swagger docs from annotations: `./artisan l5-swagger:generate`
