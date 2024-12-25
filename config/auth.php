<?php

return [
    'guards' => [
        'mahasiswa' => [
            'driver' => 'session',
            'provider' => 'mahasiswa',
        ],
        'pengurus' => [
            'driver' => 'session',
            'provider' => 'pengurus',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'mahasiswa' => [
            'driver' => 'eloquent',
            'model' => App\Models\Mahasiswa::class,
        ],
        'pengurus' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pengurus::class,
        ],
        'admins' => [ // Tambahkan provider untuk admin
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class, // Pastikan Anda memiliki model Admin
        ],
    ],

    'passwords' => [
        'mahasiswa' => [
            'provider' => 'mahasiswa',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'pengurus' => [
            'provider' => 'pengurus',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admin' => [ // Tambahkan konfigurasi reset password untuk admin
            'provider' => 'admin',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
