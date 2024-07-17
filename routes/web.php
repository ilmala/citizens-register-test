<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome', [
    'php_version' => PHP_VERSION,
    'laravel_version' => app()->version(),
]));
