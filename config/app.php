<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    | Field yang digunakan untuk memberikan nama app name kamu ya genks.
    */
    'name' => env('APP_NAME', 'Bpjs'),
    /*
    |--------------------------------------------------------------------------
    | env
    |--------------------------------------------------------------------------
    | Field yang digunakan untuk menjelaskan env kamu production atau development ya genks.
    */
    'env' => env('APP_ENV', 'production'),
    /*
    |--------------------------------------------------------------------------
    | debug
    |--------------------------------------------------------------------------
    | Field yang digunakan untuk melakukan debugging jika dibuat false tampilnya error 500 saja.
    */
    'debug' => (bool) env('APP_DEBUG', false),
    /*
    |--------------------------------------------------------------------------
    | url
    |--------------------------------------------------------------------------
    | Field yang digunakan untuk menjelaskan app url kamu.
    */
    'url' => env('APP_URL', 'http://localhost'),
    /*
    |--------------------------------------------------------------------------
    | timezone
    |--------------------------------------------------------------------------
    | Field yang digunakan untuk membuat set time zone kamu di mana.
    */
    'timezone' => env('TIMEZONE','Asia/Jakarta'),
    'locale' => 'id',
    'fallback_locale' => 'id',
    'faker_locale' => 'id_ID',
];