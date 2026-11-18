# Task Manager

A small Laravel app for managing tasks in projects. Tasks can be created, edited, deleted and reordered with drag and drop. Priority is updated automatically: the task at the top is #1, the next is #2, and so on. Each project has its own priority order.

## Requirements

- PHP 8.3 or higher
- Composer
- MySQL 8 or higher
- Laravel 11 or higher [I used Laravel Framework 13.43.0]

## Local setup

```bash
git clone https://github.com/MohamadAlchekh/Task_Manager.git
cd \Task_Manager-main
composer install
cp .env.example .env
php artisan key:generate
```

Create an empty MySQL database, then set the connection in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=[your user]
DB_PASSWORD=[your password]
```

Then run the migrations with the sample data and start the server:

```bash
php artisan migrate --seed
php artisan serve
```

Open http://127.0.0.1:8000. The seeder creates two projects with three tasks each. New projects can be added from the form at the top of the page.

Drag and drop uses SortableJS, loaded from a CDN, so the browser needs internet access.

## Tests

```bash
php artisan test
```
The database the tests use: in-memory SQLite

## Deployment

1. Requirements on the server: PHP 8.3+ with the `pdo_mysql` extension, Composer, MySQL.
2. Upload the code and run:
   ```bash
   composer install --no-dev --optimize-autoloader
   cp .env.example .env
   php artisan key:generate
   ```
3. In `.env`, set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, and the database settings.
4. Run `php artisan migrate --force`. Run `php artisan db:seed` only if you want the sample data.
5. Point the web server's document root to the `public/` directory.
6. Make `storage/` and `bootstrap/cache/` writable by the web server user.
7. Optional: `php artisan config:cache && php artisan route:cache && php artisan view:cache`.

## Design notes

- Priority is stored as a position per project (1, 2, 3...) and the server renumbers it after a reorder or a delete, inside a database transaction.
- Validation lives in Form Request classes.
- Reorder requests are checked so that only tasks of the given project are accepted.