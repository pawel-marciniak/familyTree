## Family tree

Sample app for creating family tree (genealogical tree).

## Requirements

Same as Laravel 8.x framework + (preferably) MySQL database,

## Installing

Run commands (in project root directory):
```
composer install
npm install
cp .env.example .env
php artisan key:generate
[fill database variables in .env file]
php artisan migrate
npm run dev
```

## Running

Run command:
`php artisan serve` and go to http://127.0.0.1:8000.

## Tests

Create `.env.testing` file from `.env` file and fill testing database data in it.
Then you can run tests by executing `php artisan test`.
