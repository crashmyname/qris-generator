<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Login Fields
    |--------------------------------------------------------------------------
    | Field yang digunakan untuk login. Bisa diisi satu atau beberapa.
    */
    'login_fields' => ['username'],

    /*
    |--------------------------------------------------------------------------
    | Password Hashing
    |--------------------------------------------------------------------------
    | Tipe hashing password. Saat ini hanya support bcrypt.
    */
    'password_hash' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Remember Me
    |--------------------------------------------------------------------------
    | Aktifkan fitur remember me (simpan user ID di cookie).
    */
    'remember_me' => true,
    'remember_days' => 7, // Hari untuk remember me aktif (default: 7)

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    | Nama key untuk menyimpan user login dalam session.
    */
    'session_key' => 'user',

    /*
    |--------------------------------------------------------------------------
    | Regenerate Session ID
    |--------------------------------------------------------------------------
    | Set true untuk regenerasi session ID saat login/logout demi keamanan.
    */
    'regenerate_session' => true,

    /*
    |--------------------------------------------------------------------------
    | Redirect Paths
    |--------------------------------------------------------------------------
    | Path redirect saat login berhasil/gagal, dan logout.
    */
    'redirect' => [
        'login_success' => '/dashboard',
        'login_failed'  => '/login?error=true',
        'logout'        => '/login',
    ],

    /*
    |--------------------------------------------------------------------------
    | Login Throttling
    |--------------------------------------------------------------------------
    | Untuk membatasi jumlah percobaan login (mencegah brute force).
    */
    'throttle' => [
        'enabled' => true,
        'max_attempts' => 5,
        'decay_minutes' => 1,
    ],
];
