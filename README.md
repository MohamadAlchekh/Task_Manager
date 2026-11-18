# Task Manager

A small Laravel app for managing tasks in projects. Tasks can be created, edited, deleted and reordered with drag and drop. Priority is updated automatically: the task at the top is #1, the next is #2, and so on. Each project has its own priority order.

## Requirements

- PHP 8.3 or higher
- Composer
- MySQL 8 or higher
- Laravel 11 or higher 

## Local setup

```bash
git clone [repo url]
cd [folder name]
composer install
cp .env.example .env
php artisan key:generate
```

Create an empty MySQL database, then set the connection in `.env`:

Then run the migrations with the sample data and start the server:


Open the project. The seeder creates two projects with three tasks each. New projects can be added from the form at the top of the page.

Drag and drop uses SortableJS, loaded from a CDN, so the browser needs internet access.

## Tests

```bash
php artisan test
```

## Design notes

- Priority is stored as a position per project (1, 2, 3...) and the server renumbers it after a reorder or a delete, inside a database transaction.
- Validation lives in Form Request classes.
- Reorder requests are checked so that only tasks of the given project are accepted.