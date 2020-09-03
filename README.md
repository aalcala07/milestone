## About Milestone

Milestone is a simple Laravel package for tracking your goals.

## Requirements

- PHP >= 7.2
- Laravel >= 7.0
- One of the [four supported databases](https://laravel.com/docs/master/database#introduction) by Laravel

## Installation

> **Important:** This project requires you to have Laravel's user authentication installed before running. If you don't already have this in your project, check out [Laravel's  documentation](https://laravel.com/docs/master/authentication#introduction) first.

Install with composer from within your Laravel project's `src` directory:

```
composer require aalcala/milestone
```

## Updating Package

1) Update with composer:

```
composer update aalcala/milestone
```

2) Update assets and config:

```
php artisan vendor:publish --tag milestone-assets --force
php artisan vendor:publish --tag milestone-config --force
```