<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'broadcasting/auth', '*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:5174',
        'http://192.168.29.7:5174',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
]; 