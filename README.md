## How to run

* start db & redis: `docker compose up`

* install database with all migrations and example data `php artisan migrate:fresh --seed`

* start local server: `php artisan serve`

* create admin user: `php artisan orchid:admin`

* create passport ID Keys: `php artisan passport:keys`

* create password grant client: `./artisan  passport:client --password`


* generate swagger docs from annotations: `./artisan l5-swagger:generate`
