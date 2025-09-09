<?php

return [
    'default' => env('BROADCAST_DRIVER', 'pusher'),

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('1997447'),
            'secret' => env('e7fbf6d522d41b60ea58'),
            'app_id' => env('00f55c3b4185405ff234'),
            'options' => [
                'cluster' => env('ap1'),
                'useTLS' => true,
            ],
        ],

       
    ],
]; 